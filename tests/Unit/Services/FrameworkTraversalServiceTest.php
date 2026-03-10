<?php

use App\Enums\ResponseType;
use App\Models\Framework;
use App\Models\Node;
use App\Models\NodeType;
use App\Models\Question;
use App\Models\Scale;
use App\Services\FrameworkTraversalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

uses(RefreshDatabase::class, WithFaker::class);
//uses(RefreshDatabase::class);

function makeTree(): array
{
    $framework = Framework::factory()->create();
    $nodeType  = NodeType::factory()->create();
    $scale     = Scale::factory()->create();

    /**
     * Tree structure:
     *
     * Root A
     * ├── Child A1
     * │   └── Grandchild A1a
     * └── Child A2
     *
     * Root B
     */
    $rootA = Node::factory()->create([
        'framework_id'  => $framework->id,
        'node_type_id'  => $nodeType->id,
        'order'         => 1,
        'parent_id'     => null,
    ]);

    $childA1 = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
        'order'        => 1,
        'parent_id'    => $rootA->id,
    ]);

    $grandchildA1a = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
        'order'        => 1,
        'parent_id'    => $childA1->id,
    ]);

    $childA2 = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
        'order'        => 2,
        'parent_id'    => $rootA->id,
    ]);

    $rootB = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
        'order'        => 2,
        'parent_id'    => null,
    ]);

    return compact(
        'framework',
        'rootA',
        'childA1',
        'grandchildA1a',
        'childA2',
        'rootB',
        'scale'
    );
}

it('returns nodes in true depth-first order', function () {
    extract(makeTree());

    $service = app(FrameworkTraversalService::class);

    $nodes = $service->orderedNodes($framework->id);

    expect($nodes->pluck('id')->all())->toEqual([
        $rootA->id,
        $childA1->id,
        $grandchildA1a->id,
        $childA2->id,
        $rootB->id,
    ]);
});

it('returns only nodes that have questions when using orderedQuestionNodes', function () {
    extract(makeTree());

    // Only add questions to two nodes
    Question::factory()->create([
        'node_id'       => $childA1->id,
        'response_type' => ResponseType::TYPE_SCALE->value,
        'scale_id'      => $scale->id,
        'active'        => true,
    ]);

    Question::factory()->create([
        'node_id'       => $rootB->id,
        'response_type' => ResponseType::TYPE_SCALE->value,
        'scale_id'      => $scale->id,
        'active'        => true,
    ]);

    $service = app(FrameworkTraversalService::class);

    $nodes = $service->orderedQuestionNodes($framework->id);

    expect($nodes->pluck('id')->all())->toEqual([
        $childA1->id,
        $rootB->id,
    ]);
});

it('orderedQuestionIds returns question IDs in traversal order', function () {
    extract(makeTree());

    $q1 = Question::factory()->create([
        'node_id'       => $childA1->id,
        'response_type' => ResponseType::TYPE_SCALE->value,
        'scale_id'      => $scale->id,
        'order'         => 1,
        'active'        => true,
    ]);

    $q2 = Question::factory()->create([
        'node_id'       => $grandchildA1a->id,
        'response_type' => ResponseType::TYPE_SCALE->value,
        'scale_id'      => $scale->id,
        'order'         => 1,
        'active'        => true,
    ]);

    $q3 = Question::factory()->create([
        'node_id'       => $rootB->id,
        'response_type' => ResponseType::TYPE_SCALE->value,
        'scale_id'      => $scale->id,
        'order'         => 1,
        'active'        => true,
    ]);

    $service = app(FrameworkTraversalService::class);

    $ids = $service->orderedQuestionIds($framework->id);

    expect($ids->all())->toEqual([
        $q1->id,
        $q2->id,
        $q3->id,
    ]);
});

it('ignores inactive questions', function () {
    extract(makeTree());

    $active = Question::factory()->create([
        'node_id'       => $childA1->id,
        'response_type' => ResponseType::TYPE_SCALE->value,
        'scale_id'      => $scale->id,
        'active'        => true,
    ]);

    Question::factory()->create([
        'node_id'       => $childA1->id,
        'response_type' => ResponseType::TYPE_SCALE->value,
        'scale_id'      => $scale->id,
        'active'        => false,
    ]);

    $service = app(FrameworkTraversalService::class);

    $ids = $service->orderedQuestionIds($framework->id);

    expect($ids->all())->toEqual([$active->id]);
});
