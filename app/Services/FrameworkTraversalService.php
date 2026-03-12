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
}
