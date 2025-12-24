<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\Node;
use App\Traits\FormFieldValidationRulesTrait;
use App\Traits\AssessmentHelperTrait;
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
    use AssessmentHelperTrait;

    public ?int $assessmentId;

    protected ?int $perPage = 1;
    protected ?string $pageName = 'assessmentId';
    protected ?bool $simplePagination = true;

    public Node|null $currentNode = null;

    public ?int $nodeId = null;
    public ?string $edit = null;

    public function mount($assessmentId, $nodeId = null, $edit = null)
    {
        if (empty($this->assessmentId) || ! is_numeric($this->assessmentId)) {
            return redirect()->route('frameworks');
        }

        //Redirect if not permitted to do an assessment for this framework now
        $this->redirectIfAssessmentNotPermitted($this->assessment?->framework?->id, $this->assessmentId);

        //Redirect already submitted assignments to summary page
        $this->redirectIfSubmittedOrFinished($this->assessment(), $this->assessment?->framework?->id, $this->edit);

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

    /**
     * Build the heading hierarchy for the current node
     *
     * @return array
     */
    #[Computed]
    protected function headingHierarchy(): array
    {
        if (! $this->currentNode instanceof Node) {
            return [];
        }
        $stack = [];
        $current = $this->currentNode;

        while ($current) {
            $stack[] = $current;
            $current = $current?->parent;
        }

        $stack = array_reverse($stack);

        return collect($stack)?->map(function ($n, $index) {
            $headingTag = match ($index) {
                0 => 'h1',
                1 => 'h2',
                2 => 'h3',
                3 => 'h4',
                default => 'h5',
            };

            $headingClass = match ($index) {
                0 => 'nhsuk-heading-l',
                1 => 'nhsuk-heading-m',
                2 => 'nhsuk-heading-s',
                3 => 'nhsuk-heading-xs',
                default => 'nhsuk-heading-xs',
            };

            return [
                'name'        => $n->name ?? '',
                'colour'      => $n->colour ?? 'blue',
                'headingTag'  => $headingTag,
                'headingClass'=> $headingClass,
                'type'        => $n->type?->name ?? '',
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.assessments', [
            'paginatedNodes' => $this->nodes()?->paginate($this->perPage, pageName: $this->pageName),
        ]);
    }
}
