<?php

namespace App\Livewire;

use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\Node;
use App\Models\Rater;
use App\Notifications\AssessmentCompleted as AssessmentCompletedNotification;
use App\Services\FrameworkTraversalService;
use App\Traits\AssessmentHelperTrait;
use App\Traits\UserTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

class Summary extends Component
{
    use AssessmentHelperTrait;
    use UserTrait;

    public ?int $frameworkId = null;

    public ?int $assessmentId = null;
    public ?int $raterId = null;

    public ?int $requiredCount = null;

    public ?int $answeredRequiredCount = null;

    public function mount($frameworkId = null, $assessmentId = null, $raterId = null): void
    {
        $this->frameworkId = (int) $frameworkId;
        $this->assessmentId = (int) $assessmentId;
        $this->raterId = (int) $raterId;
    }

    #[Computed]
    public function framework(): ?Framework
    {
        if ($this->frameworkId === null || $this->frameworkId === 0) {
            return null;
        }

        return Framework::find($this->frameworkId);
    }

    #[Computed]
    public function frameworks(): Collection
    {
        return Framework::all();
    }

    public function continueAssessment(): void
    {
        $node = $this->getAssessmentResumeNode($this->assessmentId);
        if ($node instanceof \App\Models\Node) {
            // There are unanswered questions, so we should resume there
            $this->redirect(route('questions', [
                'assessmentId' => $this->assessmentId,
                'raterId' => $this->raterId,
                'nodeId' => $node?->id,
            ]));
        } else {
            $this->redirect(route('frameworks'));
        }
    }

    #[Computed]
    public function nodes(): ?Collection
    {
        if ($this->frameworkId === null || $this->frameworkId === 0) {
            return collect();
        }

        $nodes = app(FrameworkTraversalService::class)
            ->orderedHierarchyNodes((int) $this->frameworkId);

        return $nodes
            ->filter(fn (Node $node) => $this->nodeHasVisibleQuestions($node))
            ->values();
    }

    protected function nodeHasVisibleQuestions(Node $node): bool
    {
        $resolvedTexts = $this->resolvedQuestionTexts;

        $questionIds = $node->questions->pluck('id');

        return $questionIds->contains(
            fn ($id) => array_key_exists($id, $resolvedTexts)
        );
    }

    #[Computed]
    public function resolvedQuestionTexts(): array
    {
        return \App\Services\QuestionTextResolver::optionsFor(
            $this->assessment(),
            AssessmentRater::where('assessment_id', $this->assessment()->id)->where('rater_id', $this->raterId)->firstOrFail()
        );
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
        if (! is_numeric($nodeId)) {
            return null;
        }

        if (!empty($this->raterId)) {
            $url = URL::signedRoute('assessment-rater', [
                'assessmentId' => $this->assessmentId,
                'raterId' => $this->raterId,
            ]);

            $separator = str_contains($url, '?') ? '&' : '?';

            $url .= $separator . 'nodeId=' . $nodeId . '&edit=edit';
            return redirect($url);
        }

        return redirect()->route('questions', [
            'assessmentId' => $this->assessmentId,
            'nodeId' => $nodeId,
            'action' => 'edit',
        ]);
    }

    public function confirmSubmit(): RedirectResponse|Redirector|null
    {
        try {
            $assessment = $this->assessment();

            if (!$assessment instanceof \App\Models\Assessment) {
                session()->flash('error', __('alerts.errors.assessment-not-found'));
                $this->dispatch('scroll-to-top');
                return null;
            }

            if (!empty($this->raterId)) {

                $rater = $assessment->raters()
                    ->where('raters.id', $this->raterId)
                    ->firstOrFail();

                if (!is_null($rater->pivot->submitted_at)) {
                    session()->flash('error', __('alerts.errors.assessment-already-submitted'));
                    $this->dispatch('scroll-to-top');
                    return null;
                }

                $assessment->raters()->updateExistingPivot($this->raterId, [
                    'submitted_at' => now(),
                ]);
            } else {
                if (!is_null($assessment->submitted_at)) {
                    session()->flash('error', __('alerts.errors.assessment-already-submitted'));
                    $this->dispatch('scroll-to-top');
                    return null;
                }

                $assessment->update([
                    'submitted_at' => now(),
                ]);
            }

            return redirect()->route('assessment-completed', [
                'assessmentId' => $assessment->id
            ]);

        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', $e->getMessage());
            $this->dispatch('scroll-to-top');

            return null;
        }
    }

    #[Computed]
    public function rater()
    {
        if ($this->assessmentId === null || $this->assessmentId === 0 || empty($this->user()?->user_id)) {
            return null;
        }

        return Rater::where('subject_id', $this->user()?->user_id)
            ->whereHas('assessments', function ($q): void {
                $q->where('assessments.id', $this->assessmentId);
            })
            ->first();
    }

    public function viewReport()
    {
        return redirect()->route('assessment-report', [
            'frameworkId' => $this->frameworkId,
            'assessmentId' => $this->assessmentId,
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

    #[Title('Assessment summary')]
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.summary', [
            'title' => 'Areas',
        ]);
    }

    #[Computed]
    public function resolvedQuestionTexts1(): array
    {
        return \App\Services\QuestionTextResolver::optionsFor(
            $this->assessment(),
            $this->rater()?->pivot
        );
    }
}
