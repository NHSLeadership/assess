<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\Framework;
use App\Models\FrameworkVariantAttribute;
use App\Models\Node;
use App\Notifications\AssessmentCompleted as AssessmentCompletedNotification;
use App\Traits\AssessmentHelperTrait;
use App\Traits\UserTrait;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Summary extends Component
{
    use UserTrait;
    use AssessmentHelperTrait;

    public ?int $frameworkId = null;
    public ?int $assessmentId = null;

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

    #[Computed]
    public function assessment(): ?Assessment
    {
        if (empty($this->assessmentId)) {
            return null;
        }

        return Assessment::with(['raters'])
            ->where('id', $this->assessmentId)
            ->where('user_id', $this->user()?->user_id)
            ->firstOrFail();
    }

    public function continueAssessment()
    {
        $node = $this->getAssessmentResumeNode($this->assessmentId);
        if (!empty($node)) {
            // There are answered questions, so we should resume there
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
        return $this->assessment?->responses()?->get();
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

    public function confirmSubmit(): ?\Illuminate\Http\RedirectResponse
    {
        try {
            $assessment = $this->assessment();

            if (! $assessment) {
                $this->dispatch('alert', type: 'error', message: __('alerts.errors.assessment-not-found'));
                $this->dispatch('scroll-to-top');
                return null;

            }

            if (! is_null($assessment->submitted_at)) {
                $this->dispatch('alert', type: 'error', message: __('alerts.errors.assessment-already-submitted'));
                $this->dispatch('scroll-to-top');
                return null;
            }

            $assessment->submitted_at = now();
            $assessment->save();

            $this->user?->notify(new AssessmentCompletedNotification($assessment));

            return redirect()->route(
                'assessment-completed', ['assessmentId' => $assessment?->id]
            );
        } catch (\Throwable $e) {
            report($e); // log the error for debugging
            $this->dispatch(
                'alert',
                type: 'error',
                message: $e->getMessage(),
            );
            $this->dispatch('scroll-to-top');
            return null;
        }
    }

    public function render()
    {
        return view('livewire.summary', [
            'title' => 'Areas'
        ]);
    }
}
