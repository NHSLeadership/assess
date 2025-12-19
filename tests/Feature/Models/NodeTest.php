<?php

use App\Models\Framework;
use App\Models\Node;
use App\Models\NodeType;
use App\Models\Question;
use App\Models\Signpost;
use App\Models\FrameworkVariantAttribute;
use App\Models\FrameworkVariantOption;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


function makeNode(array $overrides = []): \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
{
    $framework = Framework::factory()->create();
    $nodeType  = NodeType::factory()->create();

    return Node::factory()->create(array_merge([
        'name'         => 'Root Node',
        'description'  => 'Top level node',
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
    ], $overrides));
}

test('node can be created with factory', function () {
    $node = makeNode();

    expect($node->exists)->toBeTrue()
        ->and($node->name)->toEqual('Root Node');
});

test('node belongs to a framework', function () {
    $node = makeNode();

    expect($node->framework)->toBeInstanceOf(Framework::class)
        ->and($node->framework->id)->toEqual($node->framework_id);
});

test('node belongs to a node type', function () {
    $node = makeNode();

    expect($node->type)->toBeInstanceOf(NodeType::class)
        ->and($node->type->id)->toEqual($node->node_type_id);
});

test('node can have a parent and children', function () {
    $parent = makeNode(['name' => 'Parent Node']);
    $child  = makeNode(['parent_id' => $parent->id, 'name' => 'Child Node']);

    expect($child->parent)->toBeInstanceOf(Node::class)
        ->and($child->parent->id)->toEqual($parent->id);

    expect($parent->children)->toHaveCount(1)
        ->and($parent->children->first()->id)->toEqual($child->id);
});

test('node can have many questions', function () {
    $node = makeNode();
    Question::factory()->count(2)->create(['node_id' => $node->id]);

    expect($node->questions)->toHaveCount(2)
        ->and($node->questions->first())->toBeInstanceOf(Question::class);
});

test('node can have many signposts', function () {
    $node     = makeNode();
    $attr     = FrameworkVariantAttribute::factory()->create(['framework_id' => $node->framework_id]);
    $option   = FrameworkVariantOption::factory()->create(['framework_variant_attribute_id' => $attr->id]);

    Signpost::factory()->count(2)->create([
        'node_id'                   => $node->id,
        'framework_variant_option_id' => $option->id,
    ]);

    expect($node->signposts)->toHaveCount(2)
        ->and($node->signposts->first())->toBeInstanceOf(Signpost::class);
});
