<?php

namespace App\Livewire;

use App\Models\Assessment;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Traits\UserTrait;
class AssessmentCompleted extends Component
{
    use UserTrait;

    public ?int $assessmentId = null;

    public function mount()
    {
        if (empty($this->assessmentId) || ! is_numeric($this->assessmentId)) {
            return redirect()->route('frameworks');
        }

    }

    #[Computed]
    public function assessment(): object|null
    {
        return $this->user()->assessments()->find($this->assessmentId);
    }
    public function render()
    {
        return view('livewire.assessment-completed');
    }
}
