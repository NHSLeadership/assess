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
    /**
     * Redirect to summary if the assessment has been submitted.
     *
     * @param Assessment|null $assessment
     * @param int|null $frameworkId
     * @param string|null $edit
     *
     * @return Redirector|RedirectResponse|null
     */
    protected function redirectIfSubmittedOrFinished(?Assessment $assessment, ?int $frameworkId, ?string $edit = null): Redirector|RedirectResponse|null
    {
        if (!$assessment) {
            return null;
        }

        $requiredCount = $assessment->framework?->questions()
            ->where('required', true)
            ->count();

        $responseCount = $assessment->responses()?->count() ?? 0;

        $allRequiredAnswered = $requiredCount > 0 && $responseCount === $requiredCount;
        $alreadySubmitted    = !is_null($assessment->submitted_at);

        if ( (empty($edit) && $allRequiredAnswered) || $alreadySubmitted) {
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
    protected function redirectIfInvalidAssessment(?int $frameworkId, ?int $assessmentId): Redirector|RedirectResponse|null
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
                return Node::with('questions')
                    ->whereHas('questions', function ($q) use ($assessmentId) {
                        $q->whereDoesntHave('responses', function ($r) use ($assessmentId) {
                            $r->where('assessment_id', $assessmentId);
                        });
                    })
                    ->orderBy('order')
                    ->first();
            }
            // First unanswered and required question's node
            return Node::with('questions')
                ->whereHas('questions', function ($q) use ($assessmentId) {
                    $q->where('required', 1)
                        ->whereDoesntHave('responses', function ($r) use ($assessmentId) {
                            $r->where('assessment_id', $assessmentId);
                        });
                })
                ->orderBy('order')
                ->first();
        }

        // Last answered node
        return Node::with('questions')
            ->whereHas('questions.responses', function ($q) use ($assessmentId) {
                $q->where('assessment_id', $assessmentId);
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

}

