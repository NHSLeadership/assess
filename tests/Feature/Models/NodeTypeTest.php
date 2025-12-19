<?php

use App\Models\NodeType;
use App\Models\Node;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('node type can be created with factory', function () {
    $nodeType = NodeType::factory()->create([
        'name' => 'Question Type',
    ]);

    expect($nodeType->exists)->toBeTrue()
        ->and($nodeType->name)->toEqual('Question Type');
});

test('node type can have many nodes', function () {
    $nodeType = NodeType::factory()->create();
    $framework = \App\Models\Framework::factory()->create();

    Node::factory()->count(3)->create([
        'node_type_id' => $nodeType->id,
        'framework_id' => $framework->id,
    ]);

    expect($nodeType->nodes)->toHaveCount(3);
    expect($nodeType->nodes->first())->toBeInstanceOf(Node::class);
});
