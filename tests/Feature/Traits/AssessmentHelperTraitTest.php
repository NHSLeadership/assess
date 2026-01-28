<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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
    $user = makeAuthUser(['user_id' => '1000000000']);
    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()->create(['framework_id' => $framework->id, 'user_id' => $user->id]);

    $helper = new AssessmentHelperFake;

    $result = $helper->redirectIfInvalidAssessment($framework->id, $assessment->id);

    expect($result)->toBeNull();
});


test('redirectIfSubmittedOrFinished redirects when all required questions are answered', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);
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
    $user = makeAuthUser(['user_id' => '1000000000']);
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

test('allows starting when no assessments exist', function () {
    config(['app.assessment_min_interval_months' => 6]);

    $user = makeAuthUser(['email' => 'test@example.com', 'user_id' => 1000000000]);

    $this->actingAs($user);

    $helper = new AssessmentHelperFake($user);

    expect($helper->userCanStartAssessment(1))->toBeTrue();
});

test('blocks when a draft exists and user is not continuing it', function () {
    config(['app.assessment_min_interval_months' => 6]);

    $user = makeAuthUser(['email' => 'test@example.com', 'user_id' => 1000000000]);
    $framework = Framework::factory()->create();
    $this->actingAs($user);

    // Create a draft assessment
    $draft = Assessment::factory()->create([
        'user_id' => $user->id,
        'framework_id' => $framework->id,
        'submitted_at' => null,
    ]);

    $helper = new AssessmentHelperFake($user);

    $response = $helper->redirectIfAssessmentNotPermitted($framework->id, null);

    expect($response)->toBeNull();
});

test('allows continuing the same draft', function () {
    config(['app.assessment_min_interval_months' => 6]);

    $user = makeAuthUser(['email' => 'test@example.com', 'user_id' => 1000000000]);
    $framework = Framework::factory()->create();

    $this->actingAs($user);

    // Create a draft assessment
    $draft = Assessment::factory()->create([
        'user_id' => $user->user_id, // IMPORTANT: matches trait logic
        'framework_id' => $framework->id,
        'submitted_at' => null,
    ]);

    $helper = new AssessmentHelperFake($user);

    // User continues the same draft
    $response = $helper->redirectIfAssessmentNotPermitted(1, $draft->id);

    expect($response)->toBeNull();
});

test('blocks when cooldown has not passed', function () {
    config(['app.assessment_min_interval_months' => 6]);

    $user = makeAuthUser(['email' => 'test@example.com', 'user_id' => 1000000000]);

    $framework = Framework::factory()->create();
    $this->actingAs($user);

    // Create a submitted assessment 2 months ago (cooldown not passed)
    $submitted = Assessment::factory()->create([
        'user_id' => $user->user_id,
        'framework_id' => $framework->id,
        'submitted_at' => Carbon::now()->subMonths(2),
    ]);

    $helper = new AssessmentHelperFake($user);

    $response = $helper->redirectIfAssessmentNotPermitted($framework->id, null);
    expect($response->getTargetUrl())->toBe(route('frameworks'));
});

test('allows starting a new assessment when cooldown has passed', function () {
    config(['app.assessment_min_interval_months' => 6]);

    $user = makeAuthUser(['email' => 'test@example.com', 'user_id' => 1000000000]);

    $framework = Framework::factory()->create();
    $this->actingAs($user);

    // Submitted 8 months ago → cooldown passed
    $submitted = Assessment::factory()->create([
        'user_id' => $user->user_id,
        'framework_id' => $framework->id,
        'submitted_at' => Carbon::now()->subMonths(8),
    ]);

    $helper = new AssessmentHelperFake($user);

    $response = $helper->redirectIfAssessmentNotPermitted($framework->id, null);

    expect($response)->toBeNull();
});
