<?php

use App\Models\Framework;
use App\Models\FrameworkVariantAttribute;
use App\Models\FrameworkVariantOption;
use App\Models\Signpost;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('framework variant option belongs to an attribute', function () {
    $framework = Framework::factory()->create();

    $attr = FrameworkVariantAttribute::factory()->create([
        'framework_id' => $framework->id,
        'key'          => 'color',
        'label'        => 'Color',
        'order'        => 1,
    ]);

    $option = FrameworkVariantOption::factory()->create([
        'framework_variant_attribute_id' => $attr->id,
        'value' => 'blue',
        'label' => 'Blue',
        'order' => 1,
    ]);

    expect($option->attribute->id)->toEqual($attr->id);
});

test('framework variant option has many signposts', function () {
    $framework = Framework::factory()->create();
    $nodeType = \App\Models\NodeType::factory()->create();
    $node = \App\Models\Node::factory()->create([
        'framework_id'  => $framework->id,
        'node_type_id'  => $nodeType->id,
    ]);
    $attr = FrameworkVariantAttribute::factory()->create(['framework_id' => $framework->id]);
    $option = FrameworkVariantOption::factory()->create(['framework_variant_attribute_id' => $attr->id]);

    $signpost1 = Signpost::factory()->create(['framework_variant_option_id' => $option->id, 'node_id' => $node->id]);
    $signpost2 = Signpost::factory()->create(['framework_variant_option_id' => $option->id, 'node_id' => $node->id]);

    expect($option->signposts)->toHaveCount(2)
        ->and($option->signposts->pluck('id'))->toContain($signpost1->id, $signpost2->id);
});

test('framework variant option can access its framework via attribute', function () {
    $framework = Framework::factory()->create();
    $attr = FrameworkVariantAttribute::factory()->create(['framework_id' => $framework->id]);
    $option = FrameworkVariantOption::factory()->create(['framework_variant_attribute_id' => $attr->id]);

    expect($option->framework->id)->toEqual($framework->id);
});
