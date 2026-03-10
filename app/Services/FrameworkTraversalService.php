<?php

namespace App\Services;

use App\Models\Node;
use Illuminate\Support\Collection;

class FrameworkTraversalService
{
    /**
     * Return nodes in depth-first order, respecting sibling order.
     * Depth is controlled globally via ENV variable.
     *
     * @param  int  $frameworkId
     * @param  bool $withQuestions
     * @param  bool $activeOnly
     * @return Collection<Node>
     */
    public function orderedNodes(
        int $frameworkId,
        bool $withQuestions = true,
        bool $activeOnly = true,
    ): Collection {
        $depth = max(1, (int) (config('app.framework_node_depth') ?? 3));

        $with = $this->buildWith($depth, $withQuestions, $activeOnly);

        $roots = Node::query()
            ->where('framework_id', $frameworkId)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->orderBy('id')
            ->with($with)
            ->get();

        $ordered = collect();

        $walk = function (Collection $nodes) use (&$walk, &$ordered) {
            foreach ($nodes as $node) {
                $ordered->push($node);

                if ($node->relationLoaded('children') && $node->children->isNotEmpty()) {
                    $walk($node->children);
                }
            }
        };

        $walk($roots);

        return $ordered;
    }

    /**
     * Return only nodes that have at least one active question, in depth-first order.
     */
    public function orderedQuestionNodes(int $frameworkId): Collection
    {
        return $this->orderedNodes($frameworkId, withQuestions: true, activeOnly: true)
            ->filter(fn (Node $node) => $node->relationLoaded('questions') && $node->questions->isNotEmpty())
            ->values();
    }

    /**
     * Return ordered question IDs across the whole framework, in depth-first order.
     */
    public function orderedQuestionIds(int $frameworkId): Collection
    {
        $ids = collect();

        $nodes = $this->orderedNodes($frameworkId, withQuestions: true, activeOnly: true);

        foreach ($nodes as $node) {
            if ($node->relationLoaded('questions') && $node->questions->isNotEmpty()) {
                foreach ($node->questions as $q) {
                    $ids->push($q->id);
                }
            }
        }

        return $ids;
    }

    /**
     * Build the eager-load array for children/questions to the given depth.
     */
    protected function buildWith(int $depth, bool $withQuestions, bool $activeOnly): array
    {
        $with = [];

        $questionsClosure = function ($q) use ($activeOnly) {
            if ($activeOnly) {
                $q->where('active', true);
            }
            $q->orderBy('order');
        };

        if ($withQuestions) {
            $with['questions'] = $questionsClosure;
        }

        $relation = 'children';

        for ($i = 0; $i < $depth; $i++) {
            $with[$relation] = function ($q) {
                $q->orderBy('order')->orderBy('id');
            };

            if ($withQuestions) {
                $with[$relation . '.questions'] = $questionsClosure;
            }

            $relation .= '.children';
        }

        return $with;
    }
}