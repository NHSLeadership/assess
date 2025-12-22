<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\AssessmentHelperFake;
use App\Models\Framework;
use App\Models\Assessment;
use App\Models\Question;
use App\Models\Response;

uses(RefreshDatabase::class);

test('redirectIfInvalidAssessment redirects when frameworkId is invalid', function () {
    $helper = new AssessmentHelperFake;

    $response = $helper->redirectIfInvalidAssessment(999, null);

    expect($response->getTargetUrl())->toBe(route('frameworks'));
});



test('redirectIfInvalidAssessment redirects when assessmentId is invalid', function () {
    $framework = Framework::factory()->create();

    $helper = new AssessmentHelperFake;

    $response = $helper->redirectIfInvalidAssessment($framework->id, 999);

    expect($response->getTargetUrl())->toBe(route('frameworks'));
});


test('redirectIfInvalidAssessment returns null when IDs are valid', function () {
    $user = \App\Models\User::factory()->make(['user_id' => '1000000000']);
    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()->create(['framework_id' => $framework->id, 'user_id' => $user->id]);

    $helper = new AssessmentHelperFake;

    $result = $helper->redirectIfInvalidAssessment($framework->id, $assessment->id);

    expect($result)->toBeNull();
});


test('redirectIfSubmittedOrFinished redirects when all required questions are answered', function () {
    $user = \App\Models\User::factory()->make(['user_id' => '1000000000']);
    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->id,
    ]);

    $rater = \App\Models\Rater::factory()->create([
        'user_id' => $user->user_id,
    ]);

    $nodeType = \App\Models\NodeType::factory()->create();
    $node     = \App\Models\Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
    ]);

    $questions = Question::factory()->count(2)->create([
        'node_id' => $node->id,
        'required' => true,
    ]);
    $scale       = \App\Models\Scale::factory()->create();
    $scaleOption = \App\Models\ScaleOption::factory()->create(['scale_id' => $scale->id]);

    foreach ($questions as $q) {
        Response::factory()->create([
            'assessment_id'   => $assessment->id,
            'rater_id'        => $rater->id,
            'question_id'     => $q->id,
            'scale_option_id' => $scaleOption->id,
        ]);
    }

    $helper = new AssessmentHelperFake;

    $response = $helper->redirectIfSubmittedOrFinished($assessment, $framework->id);

    expect($response->getTargetUrl())->toBe(
        route('summary', ['frameworkId' => $framework->id, 'assessmentId' => $assessment->id])
    );
});

test('redirectIfSubmittedOrFinished redirects when assessment is already submitted', function () {
    $user = \App\Models\User::factory()->make(['user_id' => '1000000000']);
    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->id,
        'submitted_at' => now(),
    ]);

    $helper = new AssessmentHelperFake;

    $response = $helper->redirectIfSubmittedOrFinished($assessment, $framework->id);

    expect($response->getTargetUrl())->toBe(
        route('summary', ['frameworkId' => $framework->id, 'assessmentId' => $assessment->id])
    );
});
