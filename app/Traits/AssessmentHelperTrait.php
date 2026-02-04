<?php

namespace App\Traits;

use App\Models\Assessment;
use App\Models\Framework;
use App\Models\Node;
use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Computed;
use Livewire\Features\SupportRedirects\Redirector;

trait AssessmentHelperTrait
{
    use UserTrait;
    /**
     * Redirect to summary if the assessment has been submitted.
     *
     * @param Assessment|null $assessment
     * @param int|null $frameworkId
     * @param string|null $edit
     *
     * @return Redirector|RedirectResponse|null
     */
    public function redirectIfSubmittedOrFinished(?Assessment $assessment, ?int $frameworkId, ?string $edit = null): Redirector|RedirectResponse|null
    {
        if (!$assessment) {
            return null;
        }

        $totalQuestions = $assessment->framework
            ?->questions()
            ->count() ?? 0;
        $responseCount = $assessment->responses()
            ?->count() ?? 0;
        $allAnswered = $totalQuestions > 0 && $responseCount === $totalQuestions;

        $alreadySubmitted    = !is_null($assessment->submitted_at);
        if ((empty($edit) && $allAnswered) || $alreadySubmitted) {
            return redirect()->route('summary', [
                'frameworkId'  => $frameworkId,
                'assessmentId' => $assessment?->id,
            ]);
        }

        return null;
    }

    /**
     * Redirect to frameworks if the frameworkId or assessmentId is invalid.
     *
     * @param int|null $frameworkId
     * @param int|null $assessmentId
     * @return Redirector|RedirectResponse|null
     */
    public function redirectIfInvalidAssessment(?int $frameworkId, ?int $assessmentId): Redirector|RedirectResponse|null
    {
        // Validate frameworkId
        if (
            empty($frameworkId) ||
            !is_numeric($frameworkId) ||
            !Framework::whereKey((int) $frameworkId)->exists()
        ) {
            return redirect()->route('frameworks');
        }

        // Validate assessmentId
        if (
            !empty($assessmentId) &&
            (!is_numeric($assessmentId) ||
                !Assessment::whereKey($assessmentId)->exists())
        ) {
            return redirect()->route('frameworks');
        }

        return null;
    }

    /**
     * Get the next or last node for the assessment
     * to navigate to if the user is resuming an assessment.
     *
     * @param int|null $assessmentId
     * @param bool $next
     * @param bool $firstUnanswered
     * @return Node|null
     */
    public function getAssessmentResumeNode(?int $assessmentId = null, bool $next = true, bool $firstUnanswered = true ): ?Node
    {

        if ($next) {
            if ($firstUnanswered) {
                return Node::with(['questions' => function ($q) {
                    $q->where('active', true);
                }])
                    ->whereHas('questions', function ($q) use ($assessmentId) {
                        $q->where('active', true)
                            ->whereDoesntHave('responses', function ($r) use ($assessmentId) {
                                $r->where('assessment_id', $assessmentId);
                            });
                    })
                    ->orderBy('order')
                    ->first();
            }
            // First unanswered and required question's node
            return Node::with(['questions' => function ($q) {
                $q->where('active', true);
            }])
                ->whereHas('questions', function ($q) use ($assessmentId) {
                    $q->where('active', true)
                        ->where('required', 1)
                        ->whereDoesntHave('responses', function ($r) use ($assessmentId) {
                            $r->where('assessment_id', $assessmentId);
                        });
                })
                ->orderBy('order')
                ->first();
        }

        // Last answered node
        return Node::with(['questions' => function ($q) {
            $q->where('active', true);
        }])
            ->whereHas('questions', function ($q) use ($assessmentId) {
                $q->where('active', true)
                    ->whereHas('responses', function ($r) use ($assessmentId) {
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

        return Assessment::with(['raters'])
            ->where('id', $this->assessmentId)
            ->where('user_id', $this->user()?->user_id)
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

    public function loggedInRater(?Assessment $assessment = null): ?\App\Models\Rater
    {
        if (empty($assessment)) {
            return null;
        }

        return $assessment
            ->raters
            ->firstWhere('user_id', $this->user()?->user_id);
    }
}

