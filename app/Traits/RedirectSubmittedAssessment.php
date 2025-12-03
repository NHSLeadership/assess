<?php

namespace App\Traits;

use App\Models\Assessment;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

trait RedirectSubmittedAssessment
{
    /**
     * Redirect to summary if the assessment has been submitted.
     *
     * @param int $assessmentId
     * @param int $frameworkId
     * @return Redirector|RedirectResponse|null
     */
    protected function redirectIfSubmitted(int $assessmentId, int $frameworkId): Redirector|RedirectResponse|null
    {
        $assessment = Assessment::find($assessmentId);

        if ($assessment && $assessment->submitted_at) {
            return redirect()->route('summary', [
                'frameworkId'  => $frameworkId,
                'assessmentId' => $assessmentId,
            ]);
        }

        return null;
    }
}

