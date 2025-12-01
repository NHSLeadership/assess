<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\Node;
use App\Traits\FormFieldValidationRulesTrait;
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

    public ?string $assessmentId;

    protected ?int $perPage = 1;
    protected ?string $pageName = 'assessmentId';
    protected ?bool $simplePagination = true;

    public Node|null $currentNode;

    public function mount($assessmentId)
    {
        if (empty($this->assessmentId) || ! is_numeric($this->assessmentId)) {
            return redirect()->route('frameworks');
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
        return $this->assessment?->framework?->nodes()
            //->whereNotNull('parent_id')
            ->orderBy('order')->orderBy('order');
    }


    #[Computed]
    public function startedAreas(): ?Collection
    {
        if (empty($this->assessment)) {
            return null;
        }

        return $this->assessment?->framework?->nodes()->whereHas('questions')->get();
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
