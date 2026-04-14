<?php

namespace App\Jobs;

use App\Enums\RetentionAction;
use App\Enums\RetentionActorType;
use App\Enums\RetentionReason;
use App\Mail\AssessmentExpiryWarningMail;
use App\Models\Assessment;
use App\Models\RetentionEvent;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendRetentionWarnings implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        Assessment::query()
            ->whereNotNull('id')
            ->get()
            ->each(function (Assessment $assessment) {
                $this->handleAssessment($assessment);
            });
    }

    protected function handleAssessment(Assessment $assessment): void
    {
        $expiresAt = $assessment->expiresAt();

        // Too early – not yet in warning window
        if (now()->lt($expiresAt->copy()->subDays(config('retention.warning_days')))) {
            return;
        }

        // Already warned
        if ($this->alreadyWarned($assessment, $expiresAt)) {
            return;
        }

        $this->sendWarning($assessment, $expiresAt);

        logger()->info('Retention warning sent', [
            'assessment_id' => $assessment->id,
            'expires_at' => $expiresAt->toDateString(),
        ]);
    }

    protected function alreadyWarned(Assessment $assessment, Carbon $expiresAt): bool
    {
        return RetentionEvent::query()
            ->where('subject_type', 'Assessment')
            ->where('subject_id', $assessment->id)
            ->where('action', RetentionAction::Warning)
            ->where('metadata->expires_at', $expiresAt->toDateString())
            ->exists();
    }

    protected function sendWarning(Assessment $assessment, Carbon $expiresAt): void
    {
        $recipient = $assessment->notificationRecipient();

        if (! $recipient) {

            logger()->warning('Retention warning skipped – no contactable subject', [
                'assessment_id' => $assessment->id,
                'user_identifier' => $assessment->user_id,
            ]);

            return;
        }

        Mail::to($recipient['email'])
            ->send(new AssessmentExpiryWarningMail($expiresAt));

        $this->recordWarningEvent($assessment, $expiresAt);
    }

    protected function recordWarningEvent(Assessment $assessment, Carbon $expiresAt): void
    {
        RetentionEvent::create([
            'subject_type' => 'Assessment',
            'subject_id'   => $assessment->id,
            'action'       => RetentionAction::Warning,
            'reason'       => RetentionReason::Policy,
            'actor_type'   => RetentionActorType::System,
            'actor_id'     => null,
            'metadata'     => [
                'warning_stage' => '30_days',
                'expires_at' => $expiresAt->toDateString(),
                'retention_years' => config('retention.retention_years'),
                'warning_window_days' => config('retention.warning_days'),
                'channel' => 'email',
            ],
        ]);
    }
}
