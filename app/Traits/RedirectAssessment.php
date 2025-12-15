<?php

namespace App\Traits;

use App\Models\Assessment;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

trait RedirectAssessment
{
    /**
     * Redirect to summary if the assessment has been submitted.
     *
     * @param int|null $assessmentId
     * @param int|null $frameworkId
     * @param string|null $edit
     *
     * @return Redirector|RedirectResponse|null
     */
    protected function redirectIfSubmittedOrFinished(?int $assessmentId, ?int $frameworkId, ?string $edit = null): Redirector|RedirectResponse|null
    {
        $assessment = Assessment::find($assessmentId);

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
                'assessmentId' => $assessmentId,
            ]);
        }

        return null;
    }
}

