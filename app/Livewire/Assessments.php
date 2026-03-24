<?php

namespace App\Livewire;

use App\Models\Node;
use App\Services\FrameworkTraversalService;
use App\Traits\AssessmentHelperTrait;
use App\Traits\FormFieldValidationRulesTrait;
use App\Traits\UserTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Assessments extends Component
{
    use AssessmentHelperTrait;
    use FormFieldValidationRulesTrait;
    use UserTrait;
    use WithPagination;

    public ?int $assessmentId;

    protected int $perPage = 1;
    protected string $pageName = 'nodePage';

    public ?Node $currentNode = null;

    public ?int $nodeId = null;

    public ?string $edit = null;

    public function mount($assessmentId, $nodeId = null, $edit = null)
    {

        // Assign parameters to public properties
        $this->assessmentId = (int) $assessmentId;
        $this->nodeId = $nodeId ? (int) $nodeId : null;
        $this->edit = $edit;

        if (empty($this->assessmentId) || ! is_numeric($this->assessmentId)) {
            return redirect()->route('frameworks');
        }

        // Redirect if not permitted to do an assessment for this framework now
        $this->redirectIfAssessmentNotPermitted($this->assessment?->framework?->id, $this->assessmentId);

        // Redirect already submitted assignments to summary page
        $this->redirectIfSubmittedOrFinished($this->assessment(), $this->assessment?->framework?->id, $this->edit);

        // Set initial current node so headings are correct on first render
        $nodes = $this->nodes();

        if ($nodes && $nodes->isNotEmpty()) {
            $this->currentNode = $this->nodeId
                ? $nodes->firstWhere('id', $this->nodeId)
                : $nodes->first();
        }
    }

    #[Computed]
    public function nodes(): ?Collection
    {
        if (empty($this->assessment?->framework)) {
            return null;
        }

        return app(FrameworkTraversalService::class)
            ->orderedQuestionNodes($this->assessment->framework->id);
    }

    protected function paginatedNodes(): ?LengthAwarePaginator
    {
        $nodes = $this->nodes();

        if (! $nodes || $nodes->isEmpty()) {
            return null;
        }

        $page = LengthAwarePaginator::resolveCurrentPage($this->pageName);

        $items = $nodes->forPage($page, $this->perPage)->values();

        return new LengthAwarePaginator(
            $items,
            $nodes->count(),
            $this->perPage,
            $page,
            [
                'pageName' => $this->pageName,
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
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

    #[On('questions-next-node')]
    public function currentQuestionNode($nodeId = null): void
    {
        // Keep node within current framework
        if (! $nodeId || ! $this->assessment?->framework) {
            $this->currentNode = null;
            return;
        }

        $this->currentNode = $this->assessment
            ->framework
            ->nodes()
            ->whereKey($nodeId)
            ->first();
    }

    /**
     * Build the heading hierarchy for the current node
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
                'name' => $n->name ?? '',
                'colour' => $n->colour ?? 'blue',
                'headingTag' => $headingTag,
                'headingClass' => $headingClass,
                'type' => $n->type?->name ?? '',
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.assessments', [
            'paginatedNodes' => $this->paginatedNodes(),
        ]);
    }
}
