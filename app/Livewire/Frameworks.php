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
    function displayProgress(?Assessment $assessment): string
    {
        if (!$assessment) {
            return 'Not available';
        }

        $total = (int) ($assessment->framework?->questions?->count() ?? 0);

        if ($total <= 0) {
            return 'Not available';
        }

        $answered = (int) ($assessment->responses?->count() ?? 0);
        $percentage = (int) round(($answered / $total) * 100);

        return sprintf('%d/%d (%d%%)', $answered, $total, $percentage);
    }

    #[Title('Frameworks')]
    public function render()
    {
        return view('livewire.frameworks');
    }

    public function startNewAssessment()
    {
        //Redirect if not permitted to do an assessment for this framework now
        $this->redirectIfAssessmentNotPermitted($this->frameworkId);
    }
}
