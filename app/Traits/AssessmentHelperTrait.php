<?php

namespace App\Traits;

use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\Node;
use App\Models\Question;
use App\Models\Rater;
use App\Models\RaterGroup;
use App\Models\Response;
use App\Services\QuestionTextResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Features\SupportRedirects\Redirector;
use Throwable;

trait AssessmentHelperTrait
{
    use UserTrait;

    /**
     * Redirect to summary if the assessment has been submitted.
     */
    public function redirectIfSubmittedOrFinished(?Assessment $assessment, ?int $frameworkId, ?string $edit = null): Redirector|RedirectResponse|null
    {
        if (!$assessment instanceof \App\Models\Assessment) {
            return null;
        }

        $totalQuestions = count(
            QuestionTextResolver::optionsFor(
                $assessment,
                $this->assessmentRater()
            )
        );

        if ($totalQuestions > 0) {
            $currentRaterId = $this->currentRaterId($assessment);
            $responseCount = $currentRaterId
                ? $assessment->responses()
                    ->where('rater_id', $currentRaterId)
                    ->count()
                : 0;
        }
        $allAnswered = $totalQuestions > 0 && ($responseCount ?? 0) === $totalQuestions;
        $alreadySubmitted = ! empty($this->raterId)
            ? ! is_null($this->assessmentRater()?->submitted_at)
            : ! is_null($assessment->submitted_at);
        if ((in_array($edit, [null, '', '0'], true) && $allAnswered) || $alreadySubmitted) {

            if (!empty($this->raterId)) {
                if (!empty($this->assessmentRater()?->submitted_at)) {
                    $url = URL::signedRoute('assessment-rater-completed', [
                        'assessmentId' => $assessment->id,
                        'raterId' => $this->raterId
                    ]);
                    return redirect()->to($url);
                } else {
                    $url = URL::signedRoute('assessment-rater-summary', [
                        'frameworkId' => $this->assessment()?->framework->id,
                        'assessmentId' => $this->assessmentId,
                        'raterId' => $this->raterId,
                    ]);
                    return redirect()->to($url);
                }
            }
            return redirect()->route('summary', [
                'frameworkId' => $frameworkId,
                'assessmentId' => $assessment?->id,
            ]);
        }
        return null;
    }

    /**
     * Redirect to frameworks if the frameworkId or assessmentId is invalid.
     */
    public function redirectIfInvalidAssessment(?int $frameworkId, ?int $assessmentId): Redirector|RedirectResponse|null
    {
        // Validate frameworkId
        if (
            $frameworkId === null || $frameworkId === 0 ||
            ! is_numeric($frameworkId) ||
            ! Framework::whereKey((int) $frameworkId)->exists()
        ) {
            return redirect()->route('frameworks');
        }

        // Validate assessmentId
        if (
            $assessmentId !== null && $assessmentId !== 0 &&
            (! is_numeric($assessmentId) ||
                ! Assessment::whereKey($assessmentId)->exists())
        ) {
            return redirect()->route('frameworks');
        }

        return null;
    }

    /**
     * Get the next or last node for the assessment
     * to navigate to if the user is resuming an assessment.
     */
    public function getAssessmentResumeNode(?int $assessmentId = null, bool $next = true, bool $firstUnanswered = true): ?Node
    {

        if ($next) {
            if ($firstUnanswered) {
                return Node::with(['questions' => function ($q): void {
                    $q->where('active', true);
                }])
                    ->whereHas('questions', function ($q) use ($assessmentId): void {
                        $q->where('active', true)
                            ->whereDoesntHave('responses', function ($r) use ($assessmentId): void {
                                $r->where('assessment_id', $assessmentId);
                            });
                    })
                    ->orderBy('order')
                    ->first();
            }

            // First unanswered and required question's node
            return Node::with(['questions' => function ($q): void {
                $q->where('active', true);
            }])
                ->whereHas('questions', function ($q) use ($assessmentId): void {
                    $q->where('active', true)
                        ->where('required', 1)
                        ->whereDoesntHave('responses', function ($r) use ($assessmentId): void {
                            $r->where('assessment_id', $assessmentId);
                        });
                })
                ->orderBy('order')
                ->first();
        }

        // Last answered node
        return Node::with(['questions' => function ($q): void {
            $q->where('active', true);
        }])
            ->whereHas('questions', function ($q) use ($assessmentId): void {
                $q->where('active', true)
                    ->whereHas('responses', function ($r) use ($assessmentId): void {
                        $r->where('assessment_id', $assessmentId);
                    });
            })
            ->orderBy('order', 'desc')
            ->first();
    }

    #[Computed]
    public function assessment(): ?Assessment
    {
        if (empty($this->assessmentId)) {
            return null;
        }
        if (!empty($this->raterId)) {
            $userId = Assessment::find($this->assessmentId)?->user_id;
        } else {
            $userId = $this->user()?->user_id;
        }

        $query = Assessment::query();

        if (!empty($this->raterId)) {
            $query->with(['raters' => function ($q) {
                $q->where('raters.id', $this->raterId);
            }]);
        }

        return $query
            ->where('id', $this->assessmentId)
            ->where('user_id', $userId)
            ->firstOrFail();

    }

    public function redirectIfAssessmentNotPermitted(int $frameworkId, ?int $assessmentId = null): Redirector|RedirectResponse|null
    {
        $months = (int) config('app.assessment_min_interval_months');
        $latest = $this->getLatestAssessmentForFramework($frameworkId);

        if ($this->userCanStartAssessment($frameworkId)) {
            return null;
        }

        if (! $latest) {
            return null;
        }

        // Case 1: Draft exists
        // Draft exists
        if (is_null($latest->submitted_at)) {

            // If user is trying to continue the same draft → allow
            if ($assessmentId && $assessmentId === $latest->id) {
                return null;
            }

            // Otherwise → block starting a new one
            session()->flash('error', __('alerts.errors.assessment-in-progress'));
            session()->flash('error-title', __('alerts.errors.assessment-in-progress-title'));

            return redirect()->route('frameworks');
        }

        // Case 2: Cooldown applies
        $newDate = $latest->submitted_at
            ->addMonths($months)
            ->format('j F Y');

        session()->flash('error', __('alerts.errors.assessment-not-permitted-now', [
            'months' => $months,
            'newDate' => $newDate,
        ]));
        session()->flash('error-title', __('alerts.errors.assessment-not-permitted-now-title'));

        return redirect()->route('frameworks');
    }

    public function userCanStartAssessment(int $frameworkId): bool
    {
        $months = (int) config('app.assessment_min_interval_months');
        $latest = $this->getLatestAssessmentForFramework($frameworkId);
        if (! $latest) {
            return true;
        }

        // Draft exists → block
        if (is_null($latest->submitted_at)) {
            return false;
        }

        // Submitted → apply cooldown
        return $latest->submitted_at
            ->addMonths($months)
            ->isPast();
    }

    public function getLatestAssessmentForFramework(int $frameworkId): ?Assessment
    {
        return $this->user->assessments()
            ->where('framework_id', $frameworkId)
            ->orderByDesc('created_at')
            ->first();
    }

    public function loggedInRater(?Assessment $assessment = null): ?Rater
    {
        if (!$assessment instanceof \App\Models\Assessment) {
            return null;
        }

        return $assessment
            ->raters
            ->firstWhere('user_id', $this->user()?->user_id);
    }

    public function assessmentRater(): ?AssessmentRater
    {
        if ($this->cachedAssessmentRater !== null) {
            return $this->cachedAssessmentRater;
        }
        if (empty($this->raterId) || empty($this->assessmentId)) {
            return null;
        }

        return $this->cachedAssessmentRater = AssessmentRater::query()
            ->where('assessment_id', $this->assessmentId)
            ->where('rater_id', $this->raterId)
            ->first();
    }

    public function addGroup(): void
    {
        try {
            $this->newGroupName = trim((string) $this->newGroupName);

            $this->validate($this->groupRules());

            $group = RaterGroup::create([
                'subject_id' => $this->user()?->user_id,
                'name' => $this->newGroupName,
            ]);

            $this->refreshGroupList();

            $this->groupId = $group->id;
            $this->newGroupName = null;
            $this->showNewGroup = false;

        } catch (ValidationException $e) {
            throw $e;

        } catch (Throwable $e) {
            report($e);

            $this->addError(
                'newGroupName',
                'Unable to create the group. Please try again.'
            );

        }
    }

    public function cancelAddGroup(): void
    {
        $this->showNewGroup = false;
        $this->newGroupName = null;

        $this->resetErrorBag('newGroupName');
    }

    public function groupRules(): array
    {
        return [
            'newGroupName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rater_groups', 'name')
                    ->where(fn ($query) => $query->where(
                        'subject_id',
                        $this->user()?->user_id
                    )),
            ],
        ];
    }

    public function refreshGroupList(): void
    {
        $this->raterGroupList = RaterGroup::query()
            ->where('subject_id', $this->user()?->user_id)
            ->pluck('name', 'id')
            ->toArray();
    }

    public function responsesCount(int $assessmentId, ?int $raterId = null, bool $requiredOnly = false): int
    {
        if (empty($raterId)) {
            return 0;
        }
//        return Response::query()
//            ->where('assessment_id', $assessmentId)
//            ->where('rater_id', $raterId)
//            ->whereHas('question', fn ($query) => $query->where('required', true))
//            ->count();

        return Response::query()
            ->where('assessment_id', $assessmentId)
            ->where('rater_id', $raterId)
            ->when(
                $requiredOnly,
                fn ($query) => $query->whereHas(
                    'question',
                    fn ($query) => $query->where('required', true)
                )
            )
            ->count();

    }

    public function requiredQuestionsCount(
        Assessment $assessment,
        ?int $raterId = null
    ): int
    {
        $assessmentRater = AssessmentRater::query()
            ->where('assessment_id', $assessment->id)
            ->where('rater_id', $raterId)
            ->first();

        $questionIds = array_keys(
            QuestionTextResolver::optionsFor($assessment, $assessmentRater)
        );

        return Question::query()
            ->whereIn('id', $questionIds)
            ->where('required', true)
            ->count();
    }

    protected function currentRaterId(Assessment $assessment): ?int
    {
        return ! empty($this->raterId)
            ? $this->raterId
            : Rater::where('subject_id', $assessment?->user_id)->orderBy('id')->first()?->id;
    }

    public function assessmentCompletedDate(): ?\Illuminate\Support\Carbon
    {
        if (!empty($this->raterId)) {
            return \App\Models\AssessmentRater::query()
                ->where('assessment_id', $this->assessmentId)
                ->where('rater_id', $this->raterId)
                ->first()
                ?->submitted_at;
        }
        return $this->assessment()?->submitted_at;
    }

}
