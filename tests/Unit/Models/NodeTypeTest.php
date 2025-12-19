<?php

use App\Models\NodeType;

test('node type has expected fillable attributes', function () {
    $nodeType = new NodeType();

    expect($nodeType->getFillable())->toEqual(['name']);
});

test('node type defines nodes relationship method', function () {
    $methods = get_class_methods(NodeType::class);

    expect($methods)->toContain('nodes');
});

