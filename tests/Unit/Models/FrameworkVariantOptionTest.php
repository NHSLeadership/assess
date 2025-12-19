<?php

use App\Models\FrameworkVariantOption;

test('framework variant option has fillable attributes', function () {
    $option = new FrameworkVariantOption([
        'framework_variant_attribute_id' => 1,
        'value' => 'red',
        'label' => 'Red',
        'order' => 2,
    ]);

    expect($option->framework_variant_attribute_id)->toEqual(1)
        ->and($option->value)->toEqual('red')
        ->and($option->label)->toEqual('Red')
        ->and($option->order)->toEqual(2);
});
