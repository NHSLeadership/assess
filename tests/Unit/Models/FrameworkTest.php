<?php

use App\Models\Framework;
use Illuminate\Database\Eloquent\SoftDeletes;

test('framework has expected fillable attributes', function () {
    $framework = new Framework();

    expect($framework->getFillable())->toEqual([
        'name',
        'slug',
        'description',
        'instructions',
    ]);
});

test('framework uses soft deletes', function () {
    $traits = class_uses(Framework::class);

    expect($traits)->toHaveKey(SoftDeletes::class);
});

test('framework defines relationship methods', function () {
    $methods = get_class_methods(Framework::class);

    expect($methods)->toContain('nodes')
        ->and($methods)->toContain('questions')
        ->and($methods)->toContain('variantAttributes');
});
