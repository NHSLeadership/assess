<?php

namespace App\Livewire;

use App\Traits\AssessmentHelperTrait;
use Livewire\Component;

class AssessmentCompleted extends Component
{
    use AssessmentHelperTrait;

    public ?int $assessmentId = null;

    public function mount()
    {
        if ($this->assessmentId === null || $this->assessmentId === 0 || ! is_numeric($this->assessmentId)) {
            return redirect()->route('frameworks');
        }

        if ($this->assessment()?->submitted_at === null) {
            return redirect()->route('frameworks');
        }

    }

    public function viewReport()
    {
        return redirect()->route('assessment-report', [
            'frameworkId' => $this->assessment?->framework?->id,
            'assessmentId' => $this->assessmentId,
        ]);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.assessment-completed');
    }
}
