<?php

use App\Enums\ResponseType;
use App\Models\Assessment;
use App\Models\Framework;
use App\Models\Node;
use App\Models\NodeType;
use App\Models\Question;
use App\Models\QuestionVariant;
use App\Models\Rater;
use App\Models\Response;
use App\Models\Scale;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Helper to create a Question with all required dependencies.
 */
function makeQuestion(): Collection|Model
{
    $framework = Framework::factory()->create();
    $nodeType = NodeType::factory()->create();

    $node = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
    ]);

    return Question::factory()->create([
        'node_id' => $node->id,
        'response_type' => ResponseType::TYPE_TEXTAREA->value,
    ]);
}

test('question can be created with factory', function () {
    $question = makeQuestion();

    expect($question->exists)->toBeTrue()
        ->and($question->node)->toBeInstanceOf(Node::class);
});

test('question belongs to a node', function () {
    $question = makeQuestion();

    expect($question->node)->toBeInstanceOf(Node::class)
        ->and($question->node->id)->toEqual($question->node_id);
});

test('question belongs to a scale', function () {
    $question = makeQuestion();
    $scale = Scale::factory()->create();

    $question->update(['scale_id' => $scale->id]);

    expect($question->scale)->toBeInstanceOf(Scale::class)
        ->and($question->scale->id)->toEqual($scale->id);
});

test('question can have many variants', function () {
    $question = makeQuestion();

    QuestionVariant::factory()->count(3)->create([
        'question_id' => $question->id,
    ]);

    expect($question->variants)->toHaveCount(3)
        ->and($question->variants->first())->toBeInstanceOf(QuestionVariant::class);
});

test('question can have many responses', function () {
    // Create a scale-type question using the helper
    $setup = createNodeWithQuestions(1, 'scale');
    $question = $setup['questions']->first();

    $user = makeAuthUser(['user_id' => '1000000000']);
    $assessment1 = Assessment::factory()->create([
        'framework_id' => $setup['framework']->id,
        'user_id' => $user->id,
    ]);
    $assessment2 = Assessment::factory()->create([
        'framework_id' => $setup['framework']->id,
        'user_id' => $user->id,
    ]);

    $rater = Rater::factory()->create(['user_id' => $user->user_id]);
    $scaleSetup = createScaleWithOption();
    $scaleOption = $scaleSetup['scaleOption'];

    createResponseForAssessment($assessment1, $rater, $question, $scaleOption);
    createResponseForAssessment($assessment2, $rater, $question, $scaleOption);

    expect($question->responses)->toHaveCount(2)
        ->and($question->responses->first())->toBeInstanceOf(Response::class);
});

test('question component accessor returns correct component from enum', function () {
    $question = makeQuestion();

    expect($question->component)->toEqual(ResponseType::TYPE_TEXTAREA->component());
});

test('question name accessor returns prefixed id', function () {
    $question = makeQuestion();

    expect($question->name)->toEqual('question_'.$question->id);
});
