<?php

use App\Models\ScaleOption;

test('scale option has expected fillable attributes', function () {
    $option = new ScaleOption();

    expect($option->getFillable())->toEqual([
        'scale_id',
        'label',
        'value',
        'order',
        'description',
    ]);
});

test('scale option casts value and order to integers', function () {
    $option = new ScaleOption();

    expect($option->getCasts())->toHaveKey('value')
        ->and($option->getCasts()['value'])->toEqual('integer')
        ->and($option->getCasts())->toHaveKey('order')
        ->and($option->getCasts()['order'])->toEqual('integer');
});

test('scale option defines relationship methods', function () {
    $methods = get_class_methods(ScaleOption::class);

    expect($methods)->toContain('scale')
        ->and($methods)->toContain('responses');
});
