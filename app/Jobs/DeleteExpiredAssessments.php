<?php

namespace App\Jobs;

use App\Enums\RetentionAction;
use App\Enums\RetentionActorType;
use App\Enums\RetentionReason;
use App\Models\Assessment;
use App\Models\RetentionEvent;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteExpiredAssessments implements ShouldQueue
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

        // Not expired
        if (now()->lt($expiresAt)) {
            return;
        }

        // Must have warning before deleting
        $warningEvent = $this->getWarningEvent($assessment, $expiresAt);

        if (! $warningEvent) {
            logger()->warning('Retention deletion blocked – no prior warning', [
                'assessment_id' => $assessment->id,
                'expired_at' => $expiresAt->toDateString(),
            ]);
            return;
        }

        // Enforce minimum delay since warning
        $minDeleteAt = $warningEvent->created_at
            ->clone()
            ->addDays(config('retention.minimum_days_after_warning'));

        if (now()->lt($minDeleteAt)) {
            logger()->info('Retention deletion delayed – minimum warning period not elapsed', [
                'assessment_id' => $assessment->id,
                'warning_sent_at' => $warningEvent->created_at->toDateTimeString(),
                'earliest_delete_at' => $minDeleteAt->toDateTimeString(),
            ]);
            return;
        }

        // Already deleted
        if ($this->alreadyDeleted($assessment, $expiresAt)) {
            return;
        }

        logger()->info('Deleting assessment by retention policy', [
            'assessment_id' => $assessment->id,
            'expired_at' => $expiresAt->toDateString(),
        ]);

        $this->deleteAssessment($assessment, $expiresAt);

        logger()->info('Assessment deleted by retention policy', [
            'assessment_id' => $assessment->id,
            'expired_at' => $expiresAt->toDateString(),
        ]);
    }

    protected function alreadyDeleted(Assessment $assessment, Carbon $expiresAt): bool
    {
        return RetentionEvent::query()
            ->where('subject_type', 'Assessment')
            ->where('subject_id', $assessment->id)
            ->where('action', RetentionAction::Deleted)
            ->exists();
    }

    protected function getWarningEvent(Assessment $assessment, Carbon $expiresAt): ?RetentionEvent
    {
        return RetentionEvent::query()
            ->where('subject_type', 'Assessment')
            ->where('subject_id', $assessment->id)
            ->where('action', RetentionAction::Warning)
            ->orderByDesc('created_at')
            ->first();
    }

    protected function deleteAssessment(Assessment $assessment, Carbon $expiresAt): void
    {
        $assessment->delete();

        $this->recordDeletionEvent($assessment, $expiresAt);
    }

    protected function recordDeletionEvent(Assessment $assessment, Carbon $expiresAt): void
    {
        RetentionEvent::create([
            'owner' => (string) $assessment->user_id,
            'subject_type' => 'Assessment',
            'subject_id'   => $assessment->id,
            'action'       => RetentionAction::Deleted,
            'reason'       => RetentionReason::Policy,
            'actor_type'   => RetentionActorType::System,
            'actor_id'     => null,
            'metadata'     => [
                'last_update' => $assessment->effectiveLastUpdatedAt()->toDateString(),
                'retention_years' => config('retention.retention_years'),
                'expired_at' => $expiresAt->toDateString(),
            ],
        ]);
    }
}
