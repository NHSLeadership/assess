<?php

use App\Models\ScaleOption;
use App\Models\Scale;
use App\Models\Response;
use App\Models\Assessment;
use App\Models\Framework;
use App\Models\Rater;
use App\Models\Node;
use App\Models\NodeType;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Helper to create a minimal valid ScaleOption with its dependencies.
 */
function makeScaleOption(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
{
    $scale = Scale::factory()->create();

    return ScaleOption::factory()->create([
        'scale_id' => $scale->id,
        'label'    => 'Good',
        'value'    => 3,
        'order'    => 1,
    ]);
}

test('scale option can be created with factory', function () {
    $option = makeScaleOption();

    expect($option->exists)->toBeTrue()
        ->and($option->label)->toEqual('Good')
        ->and($option->value)->toEqual(3);
});

test('scale option belongs to a scale', function () {
    $option = makeScaleOption();

    expect($option->scale)->not->toBeNull()
        ->and($option->scale->id)->toEqual($option->scale_id);
});

test('scale option can have many responses', function () {
    $option = makeScaleOption();

    $user = \App\Models\User::factory()->make([
        'user_id' => '1000000000',
    ]);
    $auth0UserId = $user->user_id;

    $framework = Framework::factory()->create();
    $nodeType  = NodeType::factory()->create();
    $node      = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
    ]);

    $question = Question::factory()->create([
        'node_id' => $node->id,
    ]);

    $assessment1 = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id'      => $auth0UserId,
    ]);
    $assessment2 = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id'      => $auth0UserId,
    ]);

    $rater = Rater::factory()->create([
        'user_id' => $auth0UserId,
    ]);

    $response1 = Response::factory()->create([
        'question_id'     => $question->id,
        'assessment_id'   => $assessment1->id,
        'rater_id'        => $rater->id,
        'scale_option_id' => $option->id,
    ]);
    $response2 = Response::factory()->create([
        'question_id'     => $question->id,
        'assessment_id'   => $assessment2->id,
        'rater_id'        => $rater->id,
        'scale_option_id' => $option->id,
    ]);

    expect($option->responses)->toHaveCount(2)
        ->and($option->responses->first())->toBeInstanceOf(Response::class);
});
