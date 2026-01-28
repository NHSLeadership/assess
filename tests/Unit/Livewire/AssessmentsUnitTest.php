<?php

use App\Livewire\Assessments;
use App\Models\Node;

test('headingHierarchy returns empty array when no currentNode', function () {
    $component = new class extends Assessments {
        public function exposeHeadingHierarchy()
        {
            return $this->headingHierarchy();
        }
    };

    $component->currentNode = null;

    expect($component->exposeHeadingHierarchy())->toBe([]);
});

test('headingHierarchy builds correct hierarchy for parent chain', function () {

    // Build real Node model instances (no DB)
    $grandparent = new Node;
    $grandparent->name = 'Grandparent';
    $grandparent->colour = 'blue';
    $grandparent->parent = null;
    $grandparent->type = null;

    $parent = new Node;
    $parent->name = 'Parent';
    $parent->colour = 'green';
    $parent->parent = $grandparent;
    $parent->type = null;

    $child = new Node;
    $child->name = 'Child';
    $child->colour = 'red';
    $child->parent = $parent;
    $child->type = null;

    // Fake component exposing protected method
    $component = new class($child) extends Assessments {
        public function __construct($node)
        {
            $this->currentNode = $node;
        }

        public function exposeHeadingHierarchy()
        {
            return $this->headingHierarchy();
        }
    };

    $result = $component->exposeHeadingHierarchy();

    expect($result)->toHaveCount(3)
        ->and($result[0]['name'])->toBe('Grandparent')
        ->and($result[1]['name'])->toBe('Parent')
        ->and($result[2]['name'])->toBe('Child');
});
