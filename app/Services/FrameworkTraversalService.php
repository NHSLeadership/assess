<?php

namespace App\Services;

use App\Models\Node;
use Illuminate\Support\Collection;

class FrameworkTraversalService
{
    /**
     * Load the entire framework graph once (ordered), annotate each node with
     * a has_active_questions boolean, and prepare fast lookup maps.
     *
     * @return array{0:Collection,1:Collection,2:Collection}
     *         [$all, $childrenMap, $roots]
     */
    private function loadFrameworkGraph(int $frameworkId): array
    {
        // Load all nodes in sibling order and add a fast boolean for active questions.
        // This avoids materializing question collections and eliminates per-node queries.
        $all = Node::query()
            ->where('framework_id', $frameworkId)
            ->with([
                'children' => fn ($q) => $q->orderBy('order')->orderBy('id'),
                // 'parent' is optional for this implementation; we navigate via parent_id + $all[]
            ])
            ->withExists([
                'questions as has_active_questions' => fn ($q) => $q->where('active', true),
            ])
            ->orderBy('order')
            ->orderBy('id')
            ->get()
            ->keyBy('id');

        // Build a parent_id -> children collection map
        $childrenMap = $all->groupBy('parent_id');

        // Root nodes: parent_id = null
        $roots = $childrenMap[null] ?? collect();

        return [$all, $childrenMap, $roots];
    }

    public function orderedQuestionNodesFromGraph(Collection $all, Collection $childrenMap, Collection $roots): Collection
    {
        $ordered = collect();

        $walk = function (Collection $nodes) use (&$walk, &$ordered, $childrenMap) {
            foreach ($nodes as $node) {
                if ($node->has_active_questions) {
                    $ordered->push($node);
                }

                $children = $childrenMap[$node->id] ?? collect();
                if ($children->isNotEmpty()) {
                    $walk($children);
                }
            }
        };

        $walk($roots);

        return $ordered->values();
    }

    /**
     * Return nodes in true depth-first order, restricted to nodes
     * that have at least one active question.
     */
    public function orderedQuestionNodes(int $frameworkId): Collection
    {
        [, $childrenMap, $roots] = $this->loadFrameworkGraph($frameworkId);

        $ordered = collect();

        $walk = function (Collection $nodes) use (&$walk, &$ordered, $childrenMap) {
            foreach ($nodes as $node) {
                if ($node->has_active_questions) {
                    $ordered->push($node);
                }

                $children = $childrenMap[$node->id] ?? collect();
                if ($children->isNotEmpty()) {
                    $walk($children);
                }
            }
        };

        $walk($roots);

        return $ordered->values();
    }

    /**
     * Return nodes in depth-first order, including ancestors of
     * question-bearing nodes (for summary / hierarchy display).
     */
    public function orderedHierarchyNodes(int $frameworkId): Collection
    {
        [$all, $childrenMap, $roots] = $this->loadFrameworkGraph($frameworkId);

        // Start with the ordered question nodes…
        $questionNodes = $this->orderedQuestionNodesFromGraph($all, $childrenMap, $roots);

        // …then compute the closure of all ancestors for those nodes.git status
        $keepIds = collect();
        foreach ($questionNodes as $node) {
            $current = $node;
            while ($current) {
                $keepIds->push($current->id);
                $current = $current->parent_id ? ($all[$current->parent_id] ?? null) : null;
            }
        }

        // Make membership checks O(1)
        $keep = $keepIds->unique()->flip();

        $ordered = collect();

        $walk = function (Collection $nodes) use (&$walk, &$ordered, $childrenMap, $keep) {
            foreach ($nodes as $node) {
                if ($keep->has($node->id)) {
                    $ordered->push($node);
                }

                $children = $childrenMap[$node->id] ?? collect();
                if ($children->isNotEmpty()) {
                    $walk($children);
                }
            }
        };

        $walk($roots);

        return $ordered->values();
    }
}
