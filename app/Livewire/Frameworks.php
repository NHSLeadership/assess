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

class Frameworks extends Component
{
    use UserTrait;

    public ?string $frameworkId;
    
    #[Computed]
    public function framework(): ?Framework
    {
        if (empty($this->frameworkId)) {
            $framework = Framework::first();
            $this->frameworkId = $framework->id;

            return $framework;
        }

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
        if (empty($this->frameworkId)) {
            return $this->user()->assessments->sortByDesc('updated_at');
        }

        return $this->user()->assessments?->where('framework_id', $this->frameworkId)->sortByDesc('updated_at');
    }

    /**
     * Get the most relevant label for an assessment
     * @param $assessment
     * @return string
     */
    public function getAssessmentLabel($assessment): string
    {
        $raterRole = AssessmentRater::where('rater_id', $this->user()->rater_id ?? null)
            ->where('assessment_id', $assessment->id)
            ->value('role');

        // This logic needs to be changed if more rater types are added
        return $raterRole === RaterType::Self->value
            ? 'Self Assessment'
            : '360 Review';
    }

    /**
     * Display the most relevant date for an assessment
     * @param $assessment
     * @param bool $useAmPm
     * @return string
     */
    public function displayAssessmentDate($assessment, bool $useAmPm = false): string
    {
        try {
            $date = $assessment->submitted_at
                ?? $assessment->updated_at
                ?? $assessment->created_at;

            if (!$date) {
                return 'Not available';
            }

            $format = $useAmPm ? 'd F Y, g:i a' : 'd F Y, H:i';

            return \Carbon\Carbon::parse($date)->format($format);
        } catch (\Exception $e) {
            return 'Not available';
        }
    }

    #[Title('Frameworks')]
    public function render()
    {
        return view('livewire.frameworks');
    }
}
