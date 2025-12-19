<?php

use App\Models\Framework;
use App\Models\FrameworkVariantAttribute;
use App\Models\FrameworkVariantOption;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('framework variant attribute belongs to a framework', function () {
    $framework = Framework::factory()->create();

    $attr = FrameworkVariantAttribute::factory()->create([
        'framework_id' => $framework->id,
        'key'          => 'size',
        'label'        => 'Size',
        'order'        => 1,
    ]);

    expect($attr->framework->id)->toEqual($framework->id);
});

test('framework variant attribute has many options ordered by order', function () {
    $framework = Framework::factory()->create();

    $attr = FrameworkVariantAttribute::factory()->create([
        'framework_id' => $framework->id,
        'key'          => 'color',
        'label'        => 'Color',
        'order'        => 1,
    ]);

    $option1 = FrameworkVariantOption::factory()->create([
        'framework_variant_attribute_id' => $attr->id,
        'order' => 2,
    ]);

    $option2 = FrameworkVariantOption::factory()->create([
        'framework_variant_attribute_id' => $attr->id,
        'order' => 1,
    ]);

    $options = $attr->options()->get();

    expect($options->first()->id)->toEqual($option2->id) // order=1 comes first
    ->and($options->last()->id)->toEqual($option1->id);
});
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
