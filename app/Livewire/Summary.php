<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\Framework;
use App\Models\FrameworkVariantAttribute;
use App\Models\Node;
use App\Models\Rater;
use App\Notifications\AssessmentCompleted as AssessmentCompletedNotification;
use App\Traits\AssessmentHelperTrait;
use App\Traits\UserTrait;
use Illuminate\Support\Collection;
use League\Csv\Exception;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Summary extends Component
{
    use UserTrait;
    use AssessmentHelperTrait;

    public ?int $frameworkId = null;
    public ?int $assessmentId = null;
    public ?int $requiredCount = null;
    public ?int $answeredRequiredCount = null;


    #[Computed]
    public function framework(): ?Framework
    {
        if (empty($this->frameworkId)) {
            return null;
        }

        return Framework::find($this->frameworkId);
    }

    #[Computed]
    public function frameworks(): Collection
    {
        return Framework::all();
    }

    public function continueAssessment()
    {
        $node = $this->getAssessmentResumeNode($this->assessmentId);
        if (!empty($node)) {
            // There are unanswered questions, so we should resume there
            $this->redirect(route('questions', [
                'assessmentId' => $this->assessmentId,
                'nodeId' => $node?->id
            ]));
        } else {
            $this->redirect(route('frameworks'));
        }
    }

    #[Computed]
    public function nodes(): ?Collection
    {
        return Node::where('framework_id', $this->frameworkId)->orderBy('order')->orderBy('id')->get();
        //return Node::where('framework_id', $this->frameworkId)->orderByRaw('coalesce(parent_id, id), `order`')->orderBy('order')->get();
    }


    #[Computed]
    public function responses(): ?Collection
    {
        return $this->assessment()?->responses()->get();
    }

    /**
     * Redirect to edit answers for a specific node
     */
    public function editAnswer($nodeId)
    {
        if (!is_numeric($nodeId)) {
            return null;
        }
        return redirect()->route('questions', [
            'assessmentId' => $this->assessmentId,
            'nodeId' => $nodeId,
            'edit' => 'edit',
        ]);
    }

    public function confirmSubmit(): \Illuminate\Http\RedirectResponse|\Livewire\Features\SupportRedirects\Redirector|null
    {
        try {
            $assessment = $this->assessment();
            if (! $assessment) {
                session()->flash('error', __('alerts.errors.assessment-not-found'));
                $this->dispatch('scroll-to-top');
                return null;

            }

            if (! is_null($assessment->submitted_at)) {
                session()->flash('error', __('alerts.errors.assessment-already-submitted'));
                $this->dispatch('scroll-to-top');
                return null;
            }

            $assessment->submitted_at = now();
            $assessment->save();

            // Disable notifications for now.
            //$this->user?->notify(new AssessmentCompletedNotification($assessment));

            return redirect()->route(
                'assessment-completed', ['assessmentId' => $assessment?->id]
            );
        } catch (\Throwable $e) {
            report($e); // log the error for debugging
            session()->flash('error', $e->getMessage());
            $this->dispatch('scroll-to-top');
            return null;
        }
    }

    #[Computed]
    public function rater()
    {
        if (empty($this->assessmentId) || empty($this->user()?->user_id)) {
            return null;
        }
        return Rater::where('user_id', $this->user()?->user_id)
            ->whereHas('assessments', function ($q) {
                $q->where('assessments.id', $this->assessmentId);
            })
            ->first();
    }

    public function viewReport()
    {
        return redirect()->route('assessment-report', [
            'frameworkId' => $this->frameworkId,
            'assessmentId' => $this->assessmentId
        ]);
    }

    #[Computed]
    public function requiredCount()
    {
        return $this->assessment?->framework
            ->questions()
            ->where('required', 1)
            ->count();
    }

    #[Computed]
    public function answeredRequiredCount()
    {
        return $this->responses
            ->filter(fn ($r) => $r->question?->required)
            ->count();
    }

    public function render()
    {
        return view('livewire.summary', [
            'title' => 'Areas'
        ]);
    }
}
