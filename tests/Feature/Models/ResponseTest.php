<?php

use App\Models\Response;
use App\Models\Assessment;
use App\Models\Framework;
use App\Models\Rater;
use App\Models\User;
use App\Models\Node;
use App\Models\NodeType;
use App\Models\Question;
use App\Models\Scale;
use App\Models\ScaleOption;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Helper to create a minimal valid Response with all dependencies.
 */
function makeResponse(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
{
    $user = makeAuthUser(['user_id' => '1000000000']);

    $rater = Rater::factory()->create([
        'user_id' => $user->user_id,
    ]);

    // Framework + Assessment
    $framework  = Framework::factory()->create();
    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id'      => $rater->user_id,
    ]);

    // Node + Question
    $nodeType = NodeType::factory()->create();
    $node     = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
    ]);

    $question = Question::factory()->create([
        'node_id' => $node->id,
    ]);

    // Scale + Option
    $scale       = Scale::factory()->create();
    $scaleOption = ScaleOption::factory()->create(['scale_id' => $scale->id]);

    // Response
    return Response::factory()->create([
        'assessment_id'   => $assessment->id,
        'rater_id'        => $rater->id,
        'question_id'     => $question->id,
        'scale_option_id' => $scaleOption->id,
        'textarea'        => 'Sample text',
    ]);
}

test('response can be created with factory', function () {
    $response = makeResponse();

    expect($response->exists)->toBeTrue()
        ->and($response->textarea)->toEqual('Sample text');
});

test('response belongs to an assessment', function () {
    $response = makeResponse();

    expect($response->assessment)->not->toBeNull()
        ->and($response->assessment->id)->toEqual($response->assessment_id);
});

test('response belongs to a rater', function () {
    $response = makeResponse();

    expect($response->rater)->not->toBeNull()
        ->and($response->rater->id)->toEqual($response->rater_id);
});

test('response belongs to a question', function () {
    $response = makeResponse();

    expect($response->question)->not->toBeNull()
        ->and($response->question->id)->toEqual($response->question_id);
});

test('response belongs to a scale option', function () {
    $response = makeResponse();

    expect($response->scaleOption)->not->toBeNull()
        ->and($response->scaleOption->id)->toEqual($response->scale_option_id);
});
