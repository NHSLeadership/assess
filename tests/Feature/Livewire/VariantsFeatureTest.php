<?php

use App\Livewire\Variants;
use App\Models\Assessment;
use App\Models\Framework;
use App\Models\Node;
use App\Models\NodeType;
use App\Models\Question;
use App\Models\Rater;
use App\Models\Response;
use App\Models\Scale;
use App\Models\ScaleOption;
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
        'frameworkId'  => $framework->id,
        'assessmentId' => $assessment->id,
    ])
        ->assertRedirect(route('summary', [
            'assessmentId' => $assessment->id,
            'frameworkId'  => $framework->id,
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
        'assessment_id'   => $assessment->id,
        'rater_id'        => $rater->id,
        'question_id'     => $question->id,
        'scale_option_id' => $scaleOption->id,
    ]);

    variantsLivewireTest($user, [
        'frameworkId'  => $framework->id,
        'assessmentId' => $assessment->id,
    ])
        ->assertRedirect(route('questions', [
            'assessmentId' => $assessment->id,
            'nodeId'       => $node->id,
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
        'attribute_key'               => 'stage',
        'framework_variant_attribute_id'    => $attribute->id,
    ]);

    variantsLivewireTest($user, [
        'frameworkId'  => $framework->id,
        'assessmentId' => $assessment->id,
    ])
        ->assertSet('data.stage', $option->id);
});

it('shows validation errors when required variant fields are missing', function () {
    $user = makeAuthUser();

    $framework = Framework::factory()->create();

    // Create one required attribute
    $framework->variantAttributes()->create([
        'key'   => 'stage',
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
        'nodeId'       => null,
    ]));
});

it('redirects to instructions when goPrevious is called', function () {
    $user = makeAuthUser();
    $framework = Framework::factory()->create();

    $assessment = createAssessmentForUser($user, $framework);

    variantsLivewireTest($user, [
        'frameworkId'  => $framework->id,
        'assessmentId' => $assessment->id,
    ])
        ->call('goPrevious')
        ->assertRedirect(route('instructions', [
            'frameworkId'  => $framework->id,
            'assessmentId' => $assessment->id,
        ]));
});
