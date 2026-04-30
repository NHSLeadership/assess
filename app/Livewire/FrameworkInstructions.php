<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\Framework;
use App\Traits\AssessmentHelperTrait;
use App\Traits\HasPageTitle;
use Livewire\Attributes\Computed;
use Livewire\Component;

class FrameworkInstructions extends Component
{
    use AssessmentHelperTrait;
    use HasPageTitle;

    public ?int $frameworkId = null;

    public ?int $assessmentId = null;

    public function mount(?int $frameworkId = null, ?int $assessmentId = null): void
    {
        $this->frameworkId = $frameworkId;
        $this->assessmentId = $assessmentId;

        // Redirect if not permitted to do an assessment for this framework now
        $this->redirectIfAssessmentNotPermitted($this->frameworkId, $this->assessmentId);

        $this->redirectIfInvalidAssessment($frameworkId, $assessmentId);

        $this->pageTitle = __('pages.instructions.title');

    }

    #[Computed]
    public function framework(): ?Framework
    {
        return $this->frameworkId
            ? Framework::find($this->frameworkId)
            : null;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.framework-instructions');
    }
}
