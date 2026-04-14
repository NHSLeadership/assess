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

        // Not expired yet
        if (now()->lt($expiresAt)) {
            return;
        }

        // Already deleted?
        if ($this->alreadyDeleted($assessment, $expiresAt)) {
            return;
        }

        $this->deleteAssessment($assessment, $expiresAt);
    }

    protected function alreadyDeleted(Assessment $assessment, Carbon $expiresAt): bool
    {
        return RetentionEvent::query()
            ->where('subject_type', 'Assessment')
            ->where('subject_id', $assessment->id)
            ->where('action', RetentionAction::Deleted)
            ->where('metadata->expires_at', $expiresAt->toDateString())
            ->exists();
    }

    protected function deleteAssessment(Assessment $assessment, Carbon $expiresAt): void
    {
        // Delete dependent data first if needed
        $assessment->responses()->delete();

        // Then delete the assessment itself
        $assessment->delete();

        $this->recordDeletionEvent($assessment, $expiresAt);
    }

    protected function recordDeletionEvent(Assessment $assessment, Carbon $expiresAt): void
    {
        RetentionEvent::create([
            'subject_type' => 'Assessment',
            'subject_id'   => $assessment->id,
            'action'       => RetentionAction::Deleted,
            'reason'       => RetentionReason::Policy,
            'actor_type'   => RetentionActorType::System,
            'actor_id'     => null,
            'metadata'     => [
                'expires_at' => $expiresAt->toDateString(),
                'retention_years' => config('retention.retention_years'),
                'deleted_at' => now()->toDateTimeString(),
            ],
        ]);
    }
}
