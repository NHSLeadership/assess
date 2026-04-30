<?php

namespace App\Livewire;

use App\Enums\RetentionAction;
use App\Enums\RetentionActorType;
use App\Enums\RetentionReason;
use App\Models\Assessment;
use App\Models\Framework;
use App\Models\RetentionEvent;
use App\Settings\Retention;
use App\Traits\AssessmentHelperTrait;
use App\Traits\UserTrait;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Log;
use Throwable;

class Frameworks extends Component
{
    use AssessmentHelperTrait;
    use UserTrait;

    public ?int $frameworkId = null;

    public ?int $pendingDeleteId = null;

    public function mount(): void
    {
        // Set default framework if none selected
        if ($this->frameworkId === null || $this->frameworkId === 0) {
            $framework = Framework::first();

            if ($framework) {
                $this->frameworkId = $framework->id;
            }

        }
    }

    public function retainExpiringAssessments(): void
    {
        $expiring = $this->assessments()
            ->filter(fn ($assessment) => $assessment->isWithinExpiryWarningWindow());

        if ($expiring->isEmpty()) {
            return;
        }

        $years = app(Retention::class)->retention_years;

        foreach ($expiring as $assessment) {
            RetentionEvent::create([
                'owner' => (string) $assessment->user_id,
                'subject_type' => 'Assessment',
                'subject_id'   => $assessment->id,
                'action'       => RetentionAction::Extend,
                'reason'       => RetentionReason::UserAction,
                'actor_type'   => RetentionActorType::User,
                'actor_id'     => $this->user()?->user_id,
                'metadata'     => [
                    'old_last_update' => $assessment->effectiveLastUpdatedAt()->toDateString(),
                    'new_last_update' => now()->toDateString(),
                    'extension_period' => $years . ' ' . \Illuminate\Support\Str::plural('year', $years),
                ],
            ]);

            $expiring->each->touch();
        }

        session()->flash(
            'success',
            __('Expiring assessments have been kept for another :count :unit.', [
                'count' => $years,
                'unit'  => \Illuminate\Support\Str::plural('year', $years),
            ])
        );
    }

    public function askDelete(int $id): void
    {
        $this->pendingDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->pendingDeleteId = null;
    }

    public function confirmDelete(): void
    {
        $id = $this->pendingDeleteId;
        if (! $id) {
            return;
        }

        try {
            $assessment = Assessment::findOrFail($id);
            $assessment->delete();
            session()->flash('success', __('Assessment deleted.'));
        } catch (Throwable $e) {
            Log::error('Error deleting assessment', [
                'assessment_id' => $id,
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
            session()->flash('error', __('Failed to delete assessment. Please try again.'));
        } finally {
            $this->pendingDeleteId = null;
        }
    }

    #[Computed]
    public function framework(): ?Framework
    {
        return Framework::find($this->frameworkId);
    }

    #[Computed]
    public function frameworks(): ?Collection
    {
        return Framework::all();
    }

    #[Computed]
    public function assessments(): ?Collection
    {

        return $this->user()
            ->assessments()
            ->where('framework_id', $this->frameworkId)
            ->addSelect([
                'last_response_at' => DB::table('responses')
                    ->selectRaw('MAX(updated_at)')
                    ->whereColumn('assessment_id', 'assessments.id'),
            ])
            ->orderByDesc('last_response_at')
            ->with('responses')
            ->get();
    }

    /**
     * Display the most relevant date for an assessment
     */
    public function displayAssessmentDate($assessment, bool $useAmPm = false, bool $showTime = false): string
    {
        try {
            if (! $assessment) {
                return 'Not available';
            }

            $date = $assessment->effectiveLastUpdatedAt();

            if ($date === null) {
                return 'Not available';
            }

            $date = $date instanceof Carbon ? $date : Carbon::parse($date);

            $format = 'j F Y';
            if ($showTime) {
                $format .= $useAmPm ? ', g:i a' : ', H:i';
            }

            return $date->format($format);
        } catch (\Throwable) {
            return 'Not available';
        }
    }

    /**
     * Calculate the percentage of questions answered in a step
     */
    public function displayProgress(?Assessment $assessment): string
    {
        if (!$assessment instanceof \App\Models\Assessment) {
            return 'Not available';
        }

        // Count only ACTIVE questions in the framework
        $total = (int) ($assessment->framework?->questions()
            ->where('active', true)
            ->count() ?? 0);

        if ($total <= 0) {
            return 'Not available';
        }

        // Count only responses that belong to ACTIVE questions
        $answered = (int) ($assessment->responses()
            ->whereHas('question', fn ($q) => $q->where('active', true))
            ->count());

        $percentage = (int) round(($answered / $total) * 100);

        return sprintf('%d/%d (%d%%)', $answered, $total, $percentage);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.frameworks')
            ->title(__('pages.frameworks.title'));
    }

    public function startNewAssessment(): void
    {
        $months = (int) config('app.assessment_min_interval_months');
        $latest = $this->getLatestAssessmentForFramework($this->frameworkId);

        // No assessments at all → allowed
        if (!$latest instanceof \App\Models\Assessment) {
            $this->redirect(route('instructions', [
                'frameworkId' => $this->frameworkId,
            ]));

            return;
        }

        // Draft exists → block
        if (is_null($latest->submitted_at)) {
            session()->flash('error', __('alerts.errors.assessment-in-progress'));
            session()->flash('error-title', __('alerts.errors.assessment-in-progress-title'));

            return;
        }

        // Completed assessment → check cooldown
        $cooldownEnds = $latest->submitted_at->clone()->addMonths($months);

        if ($cooldownEnds->isFuture()) {
            session()->flash('error', __('alerts.errors.assessment-not-permitted-now', [
                'months' => $months,
                'newDate' => $cooldownEnds->format('j F Y'),
            ]));
            session()->flash('error-title', __('alerts.errors.assessment-not-permitted-now-title'));

            return;
        }

        // Completed and cooldown passed → allowed
        $this->redirect(route('instructions', [
            'frameworkId' => $this->frameworkId,
        ]));
    }
}
