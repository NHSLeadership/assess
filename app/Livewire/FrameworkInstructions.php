<?php

namespace App\Livewire;

use App\Models\Assessment;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Framework;
use App\Traits\RedirectAssessment;

class FrameworkInstructions extends Component
{
    use RedirectAssessment;

    public ?int $frameworkId = null;
    public ?int $assessmentId = null;

    public function mount(?int $frameworkId = null, ?int $assessmentId = null)
    {
        $this->frameworkId = $frameworkId;
        $this->assessmentId = $assessmentId;

        $this->redirectIfInvalidAssessment($frameworkId, $assessmentId);
    }

    #[Computed]
    public function framework(): ?Framework
    {
        return $this->frameworkId
            ? Framework::find($this->frameworkId)
            : null;
    }

    #[Computed]
    public function assessment(): ?Assessment
    {
        return $this->assessmentId
            ? Assessment::find($this->assessmentId)
            : null;
    }

    public function render()
    {
        return view('livewire.framework-instructions');
    }
}