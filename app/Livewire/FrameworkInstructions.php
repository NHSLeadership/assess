<?php

namespace App\Livewire;

use App\Models\Assessment;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Framework;
use App\Traits\AssessmentHelperTrait;

class FrameworkInstructions extends Component
{
    use AssessmentHelperTrait;

    public ?int $frameworkId = null;
    public ?int $assessmentId = null;

    public function mount(?int $frameworkId = null, ?int $assessmentId = null)
    {
        $this->frameworkId = $frameworkId;
        $this->assessmentId = $assessmentId;

        //Redirect if not permitted to do an assessment for this framework now
        $this->redirectIfAssessmentNotPermitted($this->frameworkId, $this->assessmentId);

        $this->redirectIfInvalidAssessment($frameworkId, $assessmentId);
    }

    #[Computed]
    public function framework(): ?Framework
    {
        return $this->frameworkId
            ? Framework::find($this->frameworkId)
            : null;
    }
    

    public function render()
    {
        return view('livewire.framework-instructions');
    }
}