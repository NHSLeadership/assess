<?php

namespace App\Livewire;

use App\Enums\RaterType;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\Rater;
use App\Traits\UserTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Traits\AssessmentHelperTrait;

class Frameworks extends Component
{
    use UserTrait;
    use AssessmentHelperTrait;

    public ?string $frameworkId = null;

    public function mount()
    {
        // Set default framework if none selected
        if (empty($this->frameworkId)) {
            $framework = Framework::first();
            $this->frameworkId = $framework->id;
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
                    ->whereColumn('assessment_id', 'assessments.id')
            ])
            ->orderByDesc('last_response_at')
            ->with('responses')
            ->get();
    }

    /**
     * Display the most relevant date for an assessment
     * @param $assessment
     * @param bool $useAmPm
     * @param bool $showTime
     * @return string
     */
    public function displayAssessmentDate($assessment, bool $useAmPm = false, bool $showTime = false): string
    {
        try {
            // If submitted, always use submitted_at
            if ($assessment->submitted_at) {
                $date = $assessment->submitted_at;
            } else {
                // Otherwise, fallback to latest response date
                $latestResponse = $assessment->responses()
                    ->orderByDesc('updated_at')
                    ->first();

                $date = $latestResponse->updated_at
                    ?? $assessment->updated_at
                    ?? $assessment->created_at;
            }

            if (!$date) {
                return 'Not available';
            }

            $format = 'j F Y';
            if ($showTime) {
                $format .= $useAmPm ? ', g:i a' : ', H:i';
            }

            return \Carbon\Carbon::parse($date)->format($format);

        } catch (\Exception $e) {
            return 'Not available';
        }
    }

    /**
     * Calculate the percentage of questions answered in a step
     *
     * @param Assessment|null $assessment
     * @return string
     */
    public function displayProgress(?Assessment $assessment): string
    {
        if (!$assessment) {
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
            ->count() ?? 0);

        $percentage = (int) round(($answered / $total) * 100);

        return sprintf('%d/%d (%d%%)', $answered, $total, $percentage);
    }

    #[Title('Frameworks')]
    public function render()
    {
        return view('livewire.frameworks');
    }

    public function startNewAssessment(): void
    {
        $months = (int) config('app.assessment_min_interval_months');
        $latest = $this->getLatestAssessmentForFramework($this->frameworkId);

        // No assessments at all → allowed
        if (! $latest) {
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
