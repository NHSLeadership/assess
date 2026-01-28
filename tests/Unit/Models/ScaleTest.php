<?php

use App\Models\Scale;

test('scale has expected fillable attributes', function () {
    $scale = new Scale();

    expect($scale->getFillable())->toEqual([
        'name',
        'description',
    ]);
});

test('scale defines relationship methods', function () {
    $methods = get_class_methods(Scale::class);

    expect($methods)->toContain('options')
        ->and($methods)->toContain('questions');
});
