<?php

use App\Models\Rater;
use App\Models\User;
use App\Models\Framework;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Response;
use App\Models\Scale;
use App\Models\ScaleOption;
use App\Models\Question;
use App\Models\Node;
use App\Models\NodeType;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Helper to create a Rater with a linked User.
 */
function makeRater(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
{
    $user = makeAuthUser(['user_id' => '1000000000']);

    return Rater::factory()->create([
        'user_id' => $user->id,
    ]);
}

test('rater can be created with factory', function () {
    $rater = makeRater();

    expect($rater->exists)->toBeTrue()->toEqual(User::class);
});

test('rater user relationship returns null when no local users table exists', function () {
    $rater = makeRater();

    expect($rater->user)->toBeNull();
});

test('rater can belong to many assessments with pivot data', function () {
    $rater = makeRater();

    $framework = Framework::factory()->create();
    $user = makeAuthUser(['user_id' => '1000000000']);

    $assessment1 = Assessment::factory()->create(['framework_id' => $framework->id, 'user_id' => $user->id]);
    $assessment2 = Assessment::factory()->create(['framework_id' => $framework->id, 'user_id' => $user->id]);

    // Attach with pivot data
    $rater->assessments()->attach($assessment1->id, [
        'role'    => 'manager',
        'is_self' => false,
    ]);

    $rater->assessments()->attach($assessment2->id, [
        'role'    => 'self',
        'is_self' => true,
    ]);

    expect($rater->assessments)->toHaveCount(2)
        ->and($rater->assessments->first()->pivot)->toBeInstanceOf(AssessmentRater::class)
        ->and($rater->assessments->first()->pivot->role)->not->toBeNull()
        ->and($rater->assessments->first()->pivot->is_self)->not->toBeNull();
});

test('rater can have many responses', function () {
    $rater = makeRater();

    // Minimal valid setup for a Response
    $framework = Framework::factory()->create();
    $nodeType  = NodeType::factory()->create();
    $node      = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
    ]);

    $question = Question::factory()->create(['node_id' => $node->id]);

    $assessment1 = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id'      => $rater->user_id,
    ]);
    $assessment2 = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id'      => $rater->user_id,
    ]);

    $scale = Scale::factory()->create();
    $scaleOption = ScaleOption::factory()->create(['scale_id' => $scale->id]);

    $response1 = Response::factory()->create([
        'question_id'     => $question->id,
        'assessment_id'   => $assessment1->id,
        'rater_id'        => $rater->id,
        'scale_option_id' => $scaleOption->id,
    ]);
    $response2 = Response::factory()->create([
        'question_id'     => $question->id,
        'assessment_id'   => $assessment2->id,
        'rater_id'        => $rater->id,
        'scale_option_id' => $scaleOption->id,
    ]);

    expect($rater->responses)->toHaveCount(2)
        ->and($rater->responses->first())->toBeInstanceOf(Response::class);
});
