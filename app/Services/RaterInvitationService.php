<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Rater;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\RaterInvitationMail;
use InvalidArgumentException;

class RaterInvitationService
{
    public function send(Assessment $assessment, Rater $rater): void
    {
        if (blank($rater->email)) {
            throw new \InvalidArgumentException('Rater must have an email address to be invited.');
        }

        $assessmentRater = AssessmentRater::query()
            ->where('assessment_id', $assessment->id)
            ->where('rater_id', $rater->id)
            ->first();

        if (! $assessmentRater) {
            throw new InvalidArgumentException(
            'Rater must be attached to the assessment before an invitation can be sent.'
            );
        }

        if ($assessmentRater->submitted_at !== null) {
            throw new InvalidArgumentException(
            'A completed rater cannot be invited again.'
            );
        }

        // Generate signed URL
        $url = URL::signedRoute(
            'assessment-rater',
            [
                'assessmentId' => $assessment->id,
                'raterId' => $rater->id,
            ]
        );

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
                    role: ucfirst($assessmentRater->type->value),
                    groupName: $assessmentRater->group?->name,
                )
            );

        // Set invited_at timestamp
        $assessmentRater->update([
            'invited_at' => now(),
        ]);
    }
}
