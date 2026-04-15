<?php

namespace App\Jobs;

use App\Enums\RetentionAction;
use App\Enums\RetentionActorType;
use App\Enums\RetentionReason;
use App\Mail\AssessmentExpiryWarningMail;
use App\Models\Assessment;
use App\Models\RetentionEvent;
use App\Settings\Retention;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendRetentionWarnings implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $settings = app(Retention::class);

        Assessment::query()
            ->whereNotNull('id')
            ->get()
            ->each(function (Assessment $assessment) use ($settings) {
                $this->handleAssessment($assessment, $settings);
            });
    }

    protected function handleAssessment(Assessment $assessment, Retention $settings): void
    {
        $expiresAt = $assessment->expiresAt();

        // Too early – not yet in warning window
        if (now()->lt($expiresAt->copy()->subDays($settings->expiry_warning_days))) {
            return;
        }

        // Already warned
        if ($this->alreadyWarned($assessment, $expiresAt)) {
            return;
        }

        $this->sendWarning($assessment, $expiresAt, $settings);

        logger()->info('Retention warning sent', [
            'assessment_id' => $assessment->id,
            'expires_at' => $expiresAt->toDateString(),
        ]);
    }

    protected function alreadyWarned(Assessment $assessment): bool
    {
        return RetentionEvent::query()
            ->where('subject_type', 'Assessment')
            ->where('subject_id', $assessment->id)
            ->where('action', RetentionAction::Warning)
            ->exists();
    }

    protected function sendWarning(Assessment $assessment, Carbon $expiresAt, Retention $settings): void
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

        $this->recordWarningEvent($assessment, $expiresAt, $settings);
    }

    protected function recordWarningEvent(Assessment $assessment, Carbon $expiresAt, Retention $settings): void
    {
        RetentionEvent::create([
            'owner' => (string) $assessment->user_id,
            'subject_type' => 'Assessment',
            'subject_id'   => $assessment->id,
            'action'       => RetentionAction::Warning,
            'reason'       => RetentionReason::Policy,
            'actor_type'   => RetentionActorType::System,
            'actor_id'     => null,
            'metadata'     => [
                'last_update' => $assessment->effectiveLastUpdatedAt()->toDateString(),
                'expires_at' => $expiresAt->toDateString(),
                'retention_years' => $settings->retention_years,
                'warning_window_days' => $settings->expiry_warning_days,
                'channel' => 'email',
            ],
        ]);
    }
}
