<?php

namespace App\Livewire;

use App\Models\Node;
use App\Services\FrameworkTraversalService;
use App\Traits\FormFieldValidationRulesTrait;
use App\Traits\AssessmentHelperTrait;
use App\Traits\UserTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Assessments extends Component
{
    use FormFieldValidationRulesTrait;
    use WithPagination;
    use UserTrait;
    use AssessmentHelperTrait;

    public ?int $assessmentId = null;

    protected int $perPage = 1;
    protected string $pageName = 'assessmentId';

    public ?Node $currentNode = null;
    public ?int $nodeId = null;
    public ?string $edit = null;

    public function mount($assessmentId, $nodeId = null, $edit = null)
    {
        $this->assessmentId = is_numeric($assessmentId) ? (int) $assessmentId : null;
        $this->nodeId = is_numeric($nodeId) ? (int) $nodeId : null;
        $this->edit = $edit;

        if (! $this->assessmentId) {
            return redirect()->route('frameworks');
        }

        // Redirect if not permitted to do an assessment for this framework now
        $this->redirectIfAssessmentNotPermitted(
            $this->assessment()?->framework?->id,
            $this->assessmentId
        );

        // Redirect already submitted assessments to summary page
        $this->redirectIfSubmittedOrFinished(
            $this->assessment(),
            $this->assessment()?->framework?->id,
            $this->edit
        );

        // If no nodeId was supplied in the URL, resume to first unanswered node.
        if (! $this->nodeId) {
            $resumeNode = $this->getAssessmentResumeNode($this->assessmentId);

            if ($resumeNode instanceof Node) {
                $this->nodeId = $resumeNode->id;
            }
        }

        // Now move the *node paginator* to the page that contains $this->nodeId
        if ($this->nodeId) {
            $index = $this->questionNodes->search(fn ($n) => $n->id === $this->nodeId);

            if ($index !== false) {
                $page = intdiv((int) $index, $this->perPage) + 1;

                $this->gotoPage($page, $this->pageName);
            }
        }
    }

    #[On('assessment-next-node')]
    public function nextNode(): void
    {
        $this->nextPage(pageName: $this->pageName);
    }

    #[On('assessment-prev-node')]
    public function previousNode(): void
    {
        $this->previousPage(pageName: $this->pageName);
    }

    #[Computed]
    public function questionNodes(): Collection
    {
        $frameworkId = $this->assessment()?->framework_id;

        if (! $frameworkId) {
            return collect();
        }

        return app(FrameworkTraversalService::class)
            ->orderedQuestionNodes($frameworkId, depth: config('app.framework_node_depth'));
    }

    #[Computed]
    public function paginatedNodes(): LengthAwarePaginator
    {
        $items = $this->questionNodes;

        $page = $this->getPage($this->pageName);
        $slice = $items->forPage($page, $this->perPage)->values();

        return new LengthAwarePaginator(
            $slice,
            $items->count(),
            $this->perPage,
            $page,
            [
                'pageName' => $this->pageName,
                'path'     => request()->url(),
                'query'    => request()->query(),
            ]
        );
    }

    #[Computed]
    public function responses(): Collection
    {
        return $this->assessment()?->responses()->get() ?? collect();
    }

    public function backPage(): void
    {
        $this->previousPage();
    }

    #[On('questions-next-node')]
    public function currentQuestionNode($nodeId = null): void
    {
        if (is_numeric($nodeId)) {
            $this->currentNode = Node::find((int) $nodeId);
        }
    }

    /**
     * Build the heading hierarchy for the current node.
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
            $current = $current->parent;
        }

        return collect(array_reverse($stack))
            ->map(function (Node $node, int $index) {
                return [
                    'name'         => $node->name ?? '',
                    'colour'       => $node->colour ?? 'blue',
                    'type'         => $node->type?->name ?? '',
                    'headingTag'   => match ($index) {
                        0 => 'h1',
                        1 => 'h2',
                        2 => 'h3',
                        3 => 'h4',
                        default => 'h5',
                    },
                    'headingClass' => match ($index) {
                        0 => 'nhsuk-heading-l',
                        1 => 'nhsuk-heading-m',
                        2 => 'nhsuk-heading-s',
                        default => 'nhsuk-heading-xs',
                    },
                ];
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.assessments', [
            'paginatedNodes' => $this->paginatedNodes,
        ]);
    }
}
