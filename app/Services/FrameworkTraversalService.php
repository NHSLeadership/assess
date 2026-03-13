<?php

namespace App\Services;

use App\Models\Node;
use Illuminate\Support\Collection;

class FrameworkTraversalService
{
    /**
     * Return nodes in true depth-first order, restricted to nodes
     * that have at least one active question.
     */
    public function orderedQuestionNodes(int $frameworkId): Collection
    {
        // Start from root nodes
        $roots = Node::query()
            ->where('framework_id', $frameworkId)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $ordered = collect();

        $walk = function (Collection $nodes) use (&$walk, &$ordered) {
            foreach ($nodes as $node) {

                // Include node if it has at least one active question
                if ($node->questions()->where('active', true)->exists()) {
                    $ordered->push($node);
                }

                // Always traverse children (fetch if not already loaded)
                $children = $node->relationLoaded('children')
                    ? $node->children
                    : $node->children()
                        ->orderBy('order')
                        ->orderBy('id')
                        ->get();

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
        // First get all question-bearing nodes (leaf competencies)
        $questionNodes = $this->orderedQuestionNodes($frameworkId);

        // Collect all ancestors + the nodes themselves
        $nodeIds = collect();

        foreach ($questionNodes as $node) {
            $current = $node;

            while ($current) {
                $nodeIds->push($current->id);
                $current = $current->parent;
            }
        }

        $nodeIds = $nodeIds->unique()->values();

        // Now do a full depth-first walk, but only keep relevant branches
        $roots = Node::query()
            ->where('framework_id', $frameworkId)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $ordered = collect();

        $walk = function (Collection $nodes) use (&$walk, &$ordered, $nodeIds) {
            foreach ($nodes as $node) {
                if ($nodeIds->contains($node->id)) {
                    $ordered->push($node);
                }

                $children = $node->relationLoaded('children')
                    ? $node->children
                    : $node->children()
                        ->orderBy('order')
                        ->orderBy('id')
                        ->get();

                if ($children->isNotEmpty()) {
                    $walk($children);
                }
            }
        };

        $walk($roots);

        return $ordered->values();
    }
}
