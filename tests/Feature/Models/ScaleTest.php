<?php
use App\Models\Scale;
use App\Models\ScaleOption;
use App\Models\Question;
use App\Models\Framework;
use App\Models\Node;
use App\Models\NodeType;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Helper to create a minimal valid Scale.
 */
function makeScale(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
{
    return Scale::factory()->create([
        'name'        => 'Performance Scale',
        'description' => 'Used for rating performance',
    ]);
}

test('scale can be created with factory', function () {
    $scale = makeScale();

    expect($scale->exists)->toBeTrue()
        ->and($scale->name)->toEqual('Performance Scale');
});

test('scale can have many options', function () {
    $scale = makeScale();

    ScaleOption::factory()->create([
        'scale_id' => $scale->id,
        'value'    => 1,
    ]);
    ScaleOption::factory()->create([
        'scale_id' => $scale->id,
        'value'    => 2,
    ]);
    ScaleOption::factory()->create([
        'scale_id' => $scale->id,
        'value'    => 3,
    ]);

    expect($scale->options)->toHaveCount(3)
        ->and($scale->options->first())->toBeInstanceOf(ScaleOption::class);
});

test('scale can have many questions', function () {
    $scale = makeScale();

    $framework = Framework::factory()->create();
    $nodeType  = NodeType::factory()->create();
    $node      = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
    ]);

    Question::factory()->count(2)->create([
        'node_id'  => $node->id,
        'scale_id' => $scale->id,
    ]);

    expect($scale->questions)->toHaveCount(2)
        ->and($scale->questions->first())->toBeInstanceOf(Question::class);
});
