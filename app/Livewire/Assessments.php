<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\Node;
use App\Traits\FormFieldValidationRulesTrait;
use App\Traits\RedirectAssessment;
use App\Traits\UserTrait;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Collection;
use Livewire\WithPagination;

class Assessments extends Component
{
    use FormFieldValidationRulesTrait;
    use WithPagination;
    use UserTrait;
    use RedirectAssessment;

    public ?int $assessmentId;

    protected ?int $perPage = 1;
    protected ?string $pageName = 'assessmentId';
    protected ?bool $simplePagination = true;

    public Node|null $currentNode;

    public ?int $nodeId = null;
    public ?string $edit = null;

    public function mount($assessmentId, $nodeId = null, $edit = null)
    {
        if (empty($this->assessmentId) || ! is_numeric($this->assessmentId)) {
            return redirect()->route('frameworks');
        }

        //Redirect already submitted assignments to summary page
        if (!$this->edit) {
            $this->redirectIfSubmittedOrFinished($this->assessmentId, $this->assessment?->framework?->id);
        }
    }

    #[Computed]
    public function assessment(): ?Assessment
    {
        if (empty($this->assessmentId)) {
            return null;
        }

        return Assessment::find($this->assessmentId);
    }

    #[Computed]
    public function nodes()
    {
        if (empty($this->assessment)) {
            return null;
        }
        return $this->assessment?->framework?->nodes()->whereHas('questions')
            ->orderBy('order');
    }

    #[Computed]
    public function data()
    {
        if (empty($this->assessmentId)) {
            return null;
        }

        return $this->user?->data?->where('assessment_id', $this->assessmentId)->get();
    }

    public function backPage()
    {
        $this->previousPage();
    }

    #[Computed]
    public function responses(): Collection
    {
        return $this->user->assessments()->where('id', $this->assessmentId)->get();
    }

    #[On('questions-next-node')]
    public function currentQuestionNode($nodeId = null)
    {
        $this->currentNode = Node::find($nodeId);
    }
    public function render()
    {
        return view('livewire.assessments', [
            'paginatedNodes' => $this->nodes()?->paginate($this->perPage, pageName: $this->pageName),
        ]);
    }
}
