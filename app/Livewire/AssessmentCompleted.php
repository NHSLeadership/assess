<?php

namespace App\Livewire;

use App\Traits\AssessmentHelperTrait;
use Livewire\Component;

class AssessmentCompleted extends Component
{
    use AssessmentHelperTrait;

    /** int|null */
    public $assessmentId = null;

    public function mount()
    {
        if (empty($this->assessmentId) || ! is_numeric($this->assessmentId)) {
            return redirect()->route('frameworks');
        }

        if ($this->assessment()?->submitted_at === null) {
            return redirect()->route('frameworks');
        }

    }

    public function viewReport()
    {
        return redirect()->route('assessment-report', [
            'frameworkId' => $this->assessment()?->framework?->id,
            'assessmentId' => $this->assessmentId,
        ]);
    }

    public function render()
    {
        return view('livewire.assessment-completed');
    }
}
