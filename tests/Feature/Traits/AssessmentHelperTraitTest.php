<?php

use App\Livewire\AssessmentCompleted;
use App\Livewire\SelectRater;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\Node;
use App\Models\NodeType;
use App\Models\Question;
use App\Models\Rater;
use App\Models\RaterGroup;
use App\Models\Response;
use App\Models\Scale;
use App\Models\ScaleOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\Support\AssessmentHelperFake;

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
        'user_id' => $user->user_id,
    ]);

    $rater = Rater::factory()->create([
        'subject_id' => $user->user_id,
    ]);

    $nodeType = NodeType::factory()->create();
    $node = Node::factory()->create([
        'framework_id' => $framework->id,
        'node_type_id' => $nodeType->id,
    ]);

    $questions = Question::factory()->count(2)->create([
        'node_id' => $node->id,
        'required' => true,
    ]);
    $scale = Scale::factory()->create();
    $scaleOption = ScaleOption::factory()->create(['scale_id' => $scale->id]);

    foreach ($questions as $q) {
        Response::factory()->create([
            'assessment_id' => $assessment->id,
            'rater_id' => $rater->id,
            'question_id' => $q->id,
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
    $helper->assessmentId = $assessment->id;

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

test('addGroup creates a new group and selects it', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    Livewire::actingAs($user)
        ->test(SelectRater::class, [
            'assessmentId' => Assessment::factory()->create([
                'user_id' => $user->user_id,
            ])->id,
        ])
        ->set('showNewGroup', true)
        ->set('newGroupName', 'Peers')
        ->call('addGroup')
        ->assertSet('showNewGroup', false)
        ->assertSet('newGroupName', null);

    $group = RaterGroup::query()
        ->where('subject_id', $user->user_id)
        ->where('name', 'Peers')
        ->first();

    expect($group)->not->toBeNull();
});

test('addGroup prevents duplicate group names for same user', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    RaterGroup::create([
        'subject_id' => $user->user_id,
        'name' => 'Peers',
    ]);

    Livewire::actingAs($user)
        ->test(SelectRater::class, [
            'assessmentId' => Assessment::factory()->create([
                'user_id' => $user->user_id,
            ])->id,
        ])
        ->set('newGroupName', 'Peers')
        ->call('addGroup')
        ->assertHasErrors(['newGroupName']);
});

it('shows the correct assessment submitted date', function () {

    $user = makeAuthUser();
    Livewire::actingAs($user);

    $rater = Rater::factory()->create([
        'subject_id' => $user->user_id,
    ]);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
        'submitted_at' => now(),
    ]);

    $submittedAt = now();

    $assessmentRater = AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'submitted_at' => $submittedAt,
    ]);

    $component = Livewire::test(AssessmentCompleted::class, [
        'assessmentId' => $assessment->id,
        'raterId' => $rater->id,
    ]);

    expect($component->instance()->assessmentCompletedDate())
        ->toEqual($assessmentRater->submitted_at);

});

test('redirectIfSubmittedOrFinished redirects rater to completed page when rater assessment is submitted', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->id,
        'submitted_at' => null, // subject has not submitted
    ]);

    $rater = Rater::factory()->create([
        'subject_id' => $user->id,
    ]);

    AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'submitted_at' => now(),
    ]);

    $helper = new AssessmentHelperFake;

    $helper->assessmentId = $assessment->id;
    $helper->raterId = $rater->id;

    $response = $helper->redirectIfSubmittedOrFinished(
        $assessment,
        $framework->id
    );

    $expectedUrl = URL::signedRoute('assessment-rater-completed', [
        'assessmentId' => $assessment->id,
        'raterId' => $rater->id,
    ]);

    expect($response)->not->toBeNull()
        ->and($response->getTargetUrl())->toBe($expectedUrl);
});

test('redirectIfSubmittedOrFinished redirects rater to summary page when all questions answered but not submitted', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $node = Node::factory()->create([
        'framework_id' => $framework->id,
        'order' => 1,
    ]);

    $questions = Question::factory()
        ->count(2)
        ->create([
            'node_id' => $node->id,
            'active' => true,
            'required' => true,
        ]);

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->id,
        'submitted_at' => null,
    ]);

    $rater = Rater::factory()->create([
        'subject_id' => $user->id,
    ]);

    AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'submitted_at' => null,
    ]);

    $scale = Scale::factory()->create();

    $scaleOption = ScaleOption::factory()->create([
        'scale_id' => $scale->id,
    ]);

    foreach ($questions as $question) {
        Response::factory()->create([
            'assessment_id' => $assessment->id,
            'question_id' => $question->id,
            'rater_id' => $rater->id,
            'scale_option_id' => $scaleOption->id,
        ]);
    }

    $helper = new AssessmentHelperFake;
    $helper->assessmentId = $assessment->id;
    $helper->raterId = $rater->id;

    $response = $helper->redirectIfSubmittedOrFinished(
        $assessment,
        $framework->id
    );

    $expectedUrl = URL::signedRoute('assessment-rater-summary', [
        'frameworkId' => $framework->id,
        'assessmentId' => $assessment->id,
        'raterId' => $rater->id,
    ]);

    expect($response)->not->toBeNull()
        ->and($response->getTargetUrl())->toBe($expectedUrl);
});

test('rater is redirected to completed page when assessment has already been submitted', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->id,
        'submitted_at' => null, // subject has not submitted
    ]);

    $rater = Rater::factory()->create([
        'subject_id' => $user->id,
    ]);

    AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'submitted_at' => now(),
    ]);

    $helper = new AssessmentHelperFake;

    $helper->assessmentId = $assessment->id;
    $helper->raterId = $rater->id;

    $response = $helper->redirectIfSubmittedOrFinished(
        $assessment,
        $framework->id
    );

    $expectedUrl = URL::signedRoute('assessment-rater-completed', [
        'assessmentId' => $assessment->id,
        'raterId' => $rater->id,
    ]);

    expect($response)->not->toBeNull();
    expect($response->getTargetUrl())->toBe($expectedUrl);
});

test('redirectIfSubmittedOrFinished does not treat rater as submitted when only assessment is submitted', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->id,
        'submitted_at' => now(), // subject submitted
    ]);

    $rater = Rater::factory()->create([
        'subject_id' => $user->id,
    ]);

    AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'submitted_at' => null, // rater not submitted
    ]);

    $helper = new AssessmentHelperFake;

    $helper->assessmentId = $assessment->id;
    $helper->raterId = $rater->id;

    $response = $helper->redirectIfSubmittedOrFinished(
        $assessment,
        $framework->id,
        '1' // avoid allAnswered path
    );

    expect($response)->toBeNull();
});
