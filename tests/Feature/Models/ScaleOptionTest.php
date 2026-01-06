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

    $user = makeAuthUser(['user_id' => '1000000000']);
    $auth0UserId = $user->user_id;

    $framework = Framework::factory()->create();

    // create node + question using helper
    $setup = createNodeWithQuestions(1, 'scale', ['framework' => $framework]);
    $question = $setup['questions']->first();

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

    createResponseForAssessment($assessment1, $rater, $question, $option);
    createResponseForAssessment($assessment2, $rater, $question, $option);

    expect($option->responses)->toHaveCount(2)
        ->and($option->responses->first())->toBeInstanceOf(Response::class);
});
