<?php

namespace App\Traits;

use App\Models\Assessment;
use App\Models\Framework;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

trait RedirectAssessment
{
    /**
     * Redirect to summary if the assessment has been submitted.
     *
     * @param int|null $assessmentId
     * @param int|null $frameworkId
     * @return Redirector|RedirectResponse|null
     */
    protected function redirectIfSubmittedOrFinished(?int $assessmentId, ?int $frameworkId): Redirector|RedirectResponse|null
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

        if ($allRequiredAnswered || $alreadySubmitted) {
            return redirect()->route('summary', [
                'frameworkId'  => $frameworkId,
                'assessmentId' => $assessmentId,
            ]);
        }

        return null;
    }
    
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
}

