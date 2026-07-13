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
        if (blank($rater->email)) {
            throw new \InvalidArgumentException('Rater must have an email address to be invited.');
        }
        $isAttached = $assessment->raters()
            ->where('raters.id', $rater->id)
            ->exists();
        if (! $isAttached) {
            throw new \InvalidArgumentException('Rater must be attached to the assessment before an invitation can be sent.');
        }

        // Generate signed URL
        $url = URL::signedRoute(
            'assessment-rater',
            [
                'assessmentId' => $assessment->id,
                'raterId' => $rater->id,
            ]
        );

        // Send email
        $assessmentRater = $assessment->raters()
            ->where('raters.id', $rater->id)
            ->firstOrFail();

        $selfRater = Rater::query()
            ->where('subject_id', $assessment->user_id)
            ->orderBy('id')
            ->first();

        Mail::to($rater->email)
            ->send(
                new RaterInvitationMail(
                    assessment: $assessment,
                    rater: $rater,
                    url: $url,
                    subjectName: $selfRater?->name ?? 'Unknown',
                    role: ucfirst($assessmentRater->pivot->type->value),
                    groupName: $assessmentRater->pivot->group?->name,
                )
            );

        // Set invited_at timestamp
        $assessment->raters()->updateExistingPivot($rater->id, [
            'invited_at' => now(),
        ]);
    }
}
