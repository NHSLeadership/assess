<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Traits\AssessmentHelperTrait;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\Title;
use Livewire\Component;

class AssessmentCompleted extends Component
{
    use AssessmentHelperTrait;

    /** int|null */
    public $assessmentId;

    /** int|null */
    public $raterId;

    public function mount()
    {
        if (empty($this->assessmentId) || ! is_numeric($this->assessmentId)) {
            return redirect()->route('frameworks');
        }

        if ($this->assessment()?->submitted_at === null) {
            return redirect()->route('frameworks');
        }
    }

    public function viewReport(): \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector

    {
        if (!empty($this->raterId)) {
            $url = URL::signedRoute('assessment-rater-report', [
                'frameworkId' => $this->assessment()?->framework->id,
                'assessmentId' => $this->assessmentId,
                'raterId' => $this->raterId,
            ]);
            return redirect()->to($url);
        } else {
            return redirect()->route('assessment-report', [
                'frameworkId' => $this->assessment()?->framework?->id,
                'assessmentId' => $this->assessmentId,
            ]);
        }
    }

    #[Title('Assessment completed')]
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.assessment-completed');
    }
}
