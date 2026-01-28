<?php

use App\Models\Node;

test('node has expected fillable attributes', function () {
    $node = new Node();

    expect($node->getFillable())->toEqual([
        'framework_id',
        'parent_id',
        'node_type_id',
        'name',
        'description',
        'colour',
        'order',
    ]);
});

test('node defines framework relationship method', function () {
    $methods = get_class_methods(Node::class);

    expect($methods)->toContain('framework');
});

test('node defines type relationship method', function () {
    $methods = get_class_methods(Node::class);

    expect($methods)->toContain('type');
});

test('node defines parent and children relationship methods', function () {
    $methods = get_class_methods(Node::class);

    expect($methods)->toContain('parent')
        ->and($methods)->toContain('children');
});

test('node defines questions and signposts relationship methods', function () {
    $methods = get_class_methods(Node::class);

    expect($methods)->toContain('questions')
        ->and($methods)->toContain('signposts');
});
