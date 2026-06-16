<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Rater;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\RaterInvitationMail;

class RaterInvitationService
{
    public function send(Assessment $assessment, Rater $rater): void
    {
        // Generate signed URL
        $url = URL::signedRoute(
            'assessment-rater',
            [
                'assessmentId' => $assessment->id,
                'raterId' => $rater->id,
            ]
        );

        // Send email
        Mail::to($rater->email)
            ->send(new RaterInvitationMail($assessment, $rater, $url));

        // Set invited_at timestamp
        $assessment->raters()->updateExistingPivot($rater->id, [
            'invited_at' => now(),
        ]);
    }
}
