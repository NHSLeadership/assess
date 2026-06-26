<?php

use App\Livewire\Variants;
use App\Models\Assessment;
use App\Models\Framework;
use App\Models\Rater;
use App\Models\Response;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('redirects to frameworks when frameworkId is invalid', function () {
    Livewire::test(Variants::class, [
        'frameworkId' => 999, // does not exist
    ])
        ->assertRedirect(route('frameworks'));
});

it('redirects to frameworks when assessmentId is invalid', function () {
    $framework = Framework::factory()->create();

    Livewire::test(Variants::class, [
        'frameworkId' => $framework->id,
        'assessmentId' => 999, // does not exist
    ])
        ->assertRedirect(route('frameworks'));
});

it('redirects when the assessment is already submitted', function () {
    $user = makeAuthUser();

    $framework = Framework::factory()->create();

    $assessment = createAssessmentForUser($user, $framework, [
        'submitted_at' => now(),
    ]);

    variantsLivewireTest($user, [
        'frameworkId' => $framework->id,
        'assessmentId' => $assessment->id,
    ])
        ->assertRedirect(route('summary', [
            'assessmentId' => $assessment->id,
            'frameworkId' => $framework->id,
        ]));
});

it('redirects to the resume node when the assessment has answered questions', function () {
    $user = makeAuthUser();
    $rater = variantsRaterForUser($user);

    $setup = createFrameworkWithNodeAndQuestionsForVariants(2);
    $framework = $setup['framework'];
    $question = $setup['questions']->first();
    $scaleOption = $setup['scaleOption'];
    $node = $setup['node'];

    $assessment = createAssessmentForUser($user, $framework);

    Response::factory()->for($assessment)->create([
        'updated_at' => Carbon::now()->subDays(5),
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'question_id' => $question->id,
        'scale_option_id' => $scaleOption->id,
    ]);

    variantsLivewireTest($user, [
        'frameworkId' => $framework->id,
        'assessmentId' => $assessment->id,
    ])
        ->assertRedirect(route('questions', [
            'assessmentId' => $assessment->id,
            'nodeId' => $node->id,
        ]));
});

it('loads existing variant selections into data on mount', function () {
    $user = makeAuthUser();

    $setup = createFrameworkWithAttributeAndOption();
    $framework = $setup['framework'];
    $attribute = $setup['attribute'];
    $option = $setup['option'];

    // Create an assessment for the authenticated user
    $assessment = createAssessmentForUser($user, $framework);

    // Attach a variant selection
    $assessment->variantSelections()->create([
        'framework_variant_option_id' => $option->id,
        'attribute_key' => 'stage',
        'framework_variant_attribute_id' => $attribute->id,
    ]);

    variantsLivewireTest($user, [
        'frameworkId' => $framework->id,
        'assessmentId' => $assessment->id,
    ])
        ->assertSet('data.stage', $option->id);
});

it('shows validation errors when required variant fields are missing', function () {
    $user = makeAuthUser();

    $framework = Framework::factory()->create();

    // Create one required attribute
    $framework->variantAttributes()->create([
        'key' => 'stage',
        'label' => 'Stage',
    ]);

    variantsLivewireTest($user, [
        'frameworkId' => $framework->id,
    ])
        ->set('data', []) // missing required field
        ->call('store')
        ->assertHasErrors(['data.stage']); // validation error expected
});

it('creates a new assessment and redirects to questions when data is valid', function () {
    $user = makeAuthUser();

    $setup = createFrameworkWithAttributeAndOption();
    $framework = $setup['framework'];
    $attribute = $setup['attribute'];
    $option = $setup['option'];

    $test = variantsLivewireTest($user, [
        'frameworkId' => $framework->id,
    ])
        ->set('data', [
            'stage' => $option->id,
        ])
        ->call('store');

    // Assert assessment was created
    $assessment = Assessment::first();
    expect($assessment)->not()->toBeNull()
        ->and($assessment->framework_id)->toBe($framework->id)
        ->and($assessment->user_id)->toEqual($user->user_id)
        ->and($assessment->variantSelections()->count())->toBe(1)
        ->and($assessment->variantSelections()->first()->framework_variant_option_id)
        ->toBe($option->id);

    // Assert redirect to questions
    $test->assertRedirect(route('questions', [
        'assessmentId' => $assessment->id,
        'nodeId' => null,
    ]));
});

it('redirects to instructions when goPrevious is called', function () {
    $user = makeAuthUser();
    $framework = Framework::factory()->create();

    $assessment = createAssessmentForUser($user, $framework);

    variantsLivewireTest($user, [
        'frameworkId' => $framework->id,
        'assessmentId' => $assessment->id,
    ])
        ->call('goPrevious')
        ->assertRedirect(route('instructions', [
            'frameworkId' => $framework->id,
            'assessmentId' => $assessment->id,
        ]));
});

it('creates a self rater when creating an assessment', function () {
    $user = makeAuthUser();

    $framework = Framework::factory()->create();

    variantsLivewireTest($user, [
        'frameworkId' => $framework->id,
    ])
        ->call('initialiseAssessment');

    expect(
        Rater::where('subject_id', $user->user_id)->count()
    )->toBe(1);

    $rater = Rater::where('subject_id', $user->user_id)
        ->orderBy('id')
        ->first();

    expect($rater->name)
        ->toBe($user->name)
        ->and($rater->email)
        ->toBe($user->email);
});

it('updates the existing self rater when creating another assessment', function () {
    $user = makeAuthUser([
        'name' => 'Andrew Blane',
        'email' => 'andrew.blane@example.com',
    ]);

    $framework = Framework::factory()->create();

    $rater = Rater::create([
        'subject_id' => $user->user_id,
        'name' => 'Old Name',
        'email' => 'old@example.com',
    ]);

    variantsLivewireTest($user, [
        'frameworkId' => $framework->id,
    ])
        ->call('initialiseAssessment');

    expect(
        Rater::where('subject_id', $user->user_id)->count()
    )->toBe(1);

    $rater->refresh();

    expect($rater->name)
        ->toBe('Andrew Blane')
        ->and($rater->email)
        ->toBe('andrew.blane@example.com');
});

it('uses the oldest rater as the self rater', function () {
    $user = makeAuthUser([
        'name' => 'Andrew Blane',
        'email' => 'andrew.blane@example.com',
    ]);

    $framework = Framework::factory()->create();

    $selfRater = Rater::create([
        'subject_id' => $user->user_id,
        'name' => 'Old Self',
        'email' => 'old-self@example.com',
    ]);

    $externalRater = Rater::create([
        'subject_id' => $user->user_id,
        'name' => 'Tom',
        'email' => 'tom@example.com',
    ]);

    variantsLivewireTest($user, [
        'frameworkId' => $framework->id,
    ])
        ->call('initialiseAssessment');

    $selfRater->refresh();
    $externalRater->refresh();

    expect($selfRater->name)->toBe('Andrew Blane')
        ->and($selfRater->email)->toBe('andrew.blane@example.com');

    expect($externalRater->name)->toBe('Tom')
        ->and($externalRater->email)->toBe('tom@example.com');
});
