<?php

use App\Models\FrameworkVariantAttribute;

test('framework variant attribute has fillable attributes', function () {
    $attr = new FrameworkVariantAttribute([
        'framework_id' => 1,
        'key'          => 'color',
        'label'        => 'Color',
        'order'        => 2,
    ]);

    expect($attr->framework_id)->toEqual(1)
        ->and($attr->key)->toEqual('color')
        ->and($attr->label)->toEqual('Color')
        ->and($attr->order)->toEqual(2);
});
