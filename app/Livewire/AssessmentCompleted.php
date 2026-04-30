<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Traits\AssessmentHelperTrait;
use App\Traits\HasPageTitle;
use Livewire\Component;

class AssessmentCompleted extends Component
{
    use AssessmentHelperTrait;
    use HasPageTitle;

    /** int|null */
    public $assessmentId;

    public function mount()
    {
        if (empty($this->assessmentId) || ! is_numeric($this->assessmentId)) {
            return redirect()->route('frameworks');
        }

        if ($this->assessment()?->submitted_at === null) {
            return redirect()->route('frameworks');
        }

        $this->pageTitle = __('pages.assessment-completed.title');
    }

    public function viewReport()
    {
        return redirect()->route('assessment-report', [
            'frameworkId' => $this->assessment()?->framework?->id,
            'assessmentId' => $this->assessmentId,
        ]);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.assessment-completed');
    }
}
