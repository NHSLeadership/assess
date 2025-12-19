<?php

use App\Models\Signpost;
use App\Models\Node;
use App\Models\NodeType;
use App\Models\Framework;
use App\Models\FrameworkVariantAttribute;
use App\Models\FrameworkVariantOption;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Helper to create a minimal valid Signpost with its dependencies.
 */
function makeSignpost(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
{
    // Framework + Node
    $framework = Framework::factory()->create();
    $nodeType  = NodeType::factory()->create();

    $node = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
    ]);

    // Attribute + Option
    $attribute = FrameworkVariantAttribute::factory()->create([
        'framework_id' => $framework->id,
    ]);

    $option = FrameworkVariantOption::factory()->create([
        'framework_variant_attribute_id' => $attribute->id,
    ]);

    // Signpost
    return Signpost::factory()->create([
        'node_id'                       => $node->id,
        'framework_variant_option_id'   => $option->id,
        'min_value'                     => 1,
        'max_value'                     => 5,
        'guidance'                      => 'Example guidance text',
    ]);
}

test('signpost can be created with factory', function () {
    $signpost = makeSignpost();

    expect($signpost->exists)->toBeTrue()
        ->and($signpost->min_value)->toEqual(1)
        ->and($signpost->max_value)->toEqual(5)
        ->and($signpost->guidance)->toEqual('Example guidance text');
});

test('signpost belongs to a node', function () {
    $signpost = makeSignpost();

    expect($signpost->node)->not->toBeNull()
        ->and($signpost->node->id)->toEqual($signpost->node_id);
});

test('signpost belongs to a framework variant option', function () {
    $signpost = makeSignpost();

    expect($signpost->frameworkVariantOption)->not->toBeNull()
        ->and($signpost->frameworkVariantOption->id)
        ->toEqual($signpost->framework_variant_option_id);
});
