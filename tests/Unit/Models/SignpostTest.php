<?php

use App\Models\Signpost;

test('signpost has expected fillable attributes', function () {
    $signpost = new Signpost();

    expect($signpost->getFillable())->toEqual([
        'node_id',
        'framework_variant_option_id',
        'min_value',
        'max_value',
        'guidance',
    ]);
});

test('signpost casts min_value and max_value to integers', function () {
    $signpost = new Signpost();

    expect($signpost->getCasts())->toHaveKey('min_value')
        ->and($signpost->getCasts()['min_value'])->toEqual('integer')
        ->and($signpost->getCasts())->toHaveKey('max_value')
        ->and($signpost->getCasts()['max_value'])->toEqual('integer');
});

test('signpost defines relationship methods', function () {
    $methods = get_class_methods(Signpost::class);

    expect($methods)->toContain('node')
        ->and($methods)->toContain('frameworkVariantOption');
});
