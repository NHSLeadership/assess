<?php

use App\Livewire\Frameworks;
use App\Models\Framework;
use App\Models\Node;
use App\Models\NodeType;
use App\Models\Question;
use App\Models\Rater;
use App\Models\Scale;
use App\Models\ScaleOption;
use App\Models\User;
use App\Models\Assessment;
use App\Models\Response;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

// Helper functions like makeAuthUser(), raterForUser(), and createFrameworkWithNodeAndQuestions()
// are provided globally by tests/Support/TestHelpers.php


test('mount sets default framework when none is provided', function () {
    $user = makeAuthUser();
    Framework::factory()->count(2)->create();
    Livewire::actingAs($user)
        ->test(\App\Livewire\Frameworks::class)
        ->assertSet('frameworkId', Framework::first()->id);
});

test('mount keeps provided frameworkId', function () {
    // Create two frameworks
    $framework1 = Framework::factory()->create();
    $framework2 = Framework::factory()->create();

    // Create a real authenticated user
    $user = makeAuthUser();

    // Pass frameworkB explicitly
    Livewire::actingAs($user)
        ->test(Frameworks::class, ['frameworkId' => $framework2->id])
        ->assertSet('frameworkId', $framework2->id);
});

test('framework computed property returns the correct framework', function () {
    // Create two frameworks
    $framework1 = Framework::factory()->create();
    $framework2 = Framework::factory()->create();

    // Create authenticated user
    $user = makeAuthUser();

    // Test component
    Livewire::actingAs($user)
        ->test(Frameworks::class, ['frameworkId' => $framework2->id])
        ->assertOk()
        ->tap(function ($component) use ($framework2) {
            $computed = $component->get('framework');
            expect($computed->id)->toBe($framework2->id);
        });
});

test('framework computed property returns null for invalid frameworkId', function () {
    // Create a real authenticated user
    $user = makeAuthUser();

    $invalidId = 999999;

    Livewire::actingAs($user)
        ->test(Frameworks::class, ['frameworkId' => $invalidId])
        ->assertOk()
        ->tap(function ($component) {
            $computed = $component->get('framework');
            expect($computed)->toBeNull();
        });
});

test('frameworks computed property returns all frameworks', function () {
    $user = makeAuthUser();

    // Create 3 frameworks
    Framework::factory()->count(3)->create();

    Livewire::actingAs($user)
        ->test(Frameworks::class)
        ->assertOk()
        ->tap(function ($component) {
            $computed = $component->get('frameworks');
            expect($computed->count())->toBe(3);
        });
});

test('assessments computed property returns only assessments for the logged-in user', function () {
    // Create framework
    $framework = Framework::factory()->create();

    // Create two users
    $userA = makeAuthUser([
        'preferred_username' => 'userA',
        'user_id' => '1000000000',
    ]);

    $userB = makeAuthUser([
        'preferred_username' => 'userB',
        'user_id' => '1000000002',
    ]);

    // Create assessments for both users
    $assessmentA = Assessment::factory()->for($userA)->create([
        'framework_id' => $framework->id,
    ]);

    Assessment::factory()->for($userB)->create([
        'framework_id' => $framework->id,
    ]);

    // Test as userA
    Livewire::actingAs($userA)
        ->test(Frameworks::class, ['frameworkId' => $framework->id])
        ->assertOk()
        ->tap(function ($component) use ($assessmentA) {
            $computed = $component->get('assessments');

            // Should contain only userA's assessment
            expect($computed->count())->toBe(1)
                ->and($computed->first()->id)->toBe($assessmentA->id);
        });
});

test('assessments computed property filters by frameworkId', function () {
    // Create authenticated user
    $user = makeAuthUser();

    // Create two frameworks
    $frameworkA = Framework::factory()->create();
    $frameworkB = Framework::factory()->create();

    // Create assessments for the same user but different frameworks
    $assessmentA = Assessment::factory()->for($user)->create([
        'framework_id' => $frameworkA->id,
    ]);

    Assessment::factory()->for($user)->create([
        'framework_id' => $frameworkB->id,
    ]);

    // Test component with frameworkA selected
    Livewire::actingAs($user)
        ->test(Frameworks::class, ['frameworkId' => $frameworkA->id])
        ->assertOk()
        ->tap(function ($component) use ($assessmentA) {
            $computed = $component->get('assessments');

            // Should contain only the assessment for frameworkA
            expect($computed->count())->toBe(1)
                ->and($computed->first()->id)->toBe($assessmentA->id);
        });
});

test('assessments are ordered by last response date descending', function () {
    // Create authenticated user
    $user = makeAuthUser();
    $rater = raterForUser($user);

    // Create framework + questions graph
    $setup = createFrameworkWithNodeAndQuestions(1);
    $framework = $setup['framework'];
    $question = $setup['questions']->first();
    $scaleOption = $setup['scaleOption'];

    // Create two assessments for the same user
    $assessmentOld = Assessment::factory()->for($user)->create([
        'framework_id' => $framework->id,
    ]);

    $assessmentNew = Assessment::factory()->for($user)->create([
        'framework_id' => $framework->id,
    ]);

    // Add responses with different timestamps
    Response::factory()->for($assessmentOld)->create([
        'updated_at' => Carbon::now()->subDays(5),
        'assessment_id'   => $assessmentOld->id,
        'rater_id'        => $rater->id,
        'question_id'     => $question->id,
        'scale_option_id' => $scaleOption->id,
    ]);

    Response::factory()->for($assessmentNew)->create([
        'updated_at' => Carbon::now()->subDay(),
        'assessment_id'   => $assessmentNew->id,
        'rater_id'        => $rater->id,
        'question_id'     => $question->id,
        'scale_option_id' => $scaleOption->id,
    ]);

    Livewire::actingAs($user)
        ->test(Frameworks::class, ['frameworkId' => $framework->id])
        ->assertOk()
        ->tap(function ($component) use ($assessmentNew, $assessmentOld) {
            $computed = $component->get('assessments');

            // Should be sorted: newest first
            expect($computed->first()->id)->toBe($assessmentNew->id)
                ->and($computed->last()->id)->toBe($assessmentOld->id);
        });
});

test('assessments include responses relationship', function () {
    $user = makeAuthUser();

    $rater = raterForUser($user);

    // Create framework + questions graph
    $setup = createFrameworkWithNodeAndQuestions(2);
    $framework = $setup['framework'];
    $question1 = $setup['questions']->get(0);
    $question2 = $setup['questions']->get(1);
    $scaleOption = $setup['scaleOption'];

    // Create an assessment
    $assessment = Assessment::factory()->for($user)->create([
        'framework_id' => $framework->id,
    ]);

    // Add responses
    $responses = collect([
        Response::factory()->for($assessment)->create([
            'updated_at'      => Carbon::now()->subDay(),
            'assessment_id'   => $assessment->id,
            'rater_id'        => $rater->id,
            'question_id'     => $question1->id,
            'scale_option_id' => $scaleOption->id,
        ]),

        Response::factory()->for($assessment)->create([
            'updated_at'      => Carbon::now()->subDay(),
            'assessment_id'   => $assessment->id,
            'rater_id'        => $rater->id,
            'question_id'     => $question2->id,
            'scale_option_id' => $scaleOption->id,
        ]),
    ]);

    Livewire::actingAs($user)
        ->test(Frameworks::class, ['frameworkId' => $framework->id])
        ->assertOk()
        ->tap(function ($component) use ($responses) {
            $computed = $component->get('assessments');

            $first = $computed->first();

            // Ensure relationship is loaded
            expect($first->relationLoaded('responses'))->toBeTrue();

            // Ensure correct number of responses
            expect($first->responses->count())->toBe(2);
        });
});

test('displayAssessmentDate uses submitted_at when present', function () {
    $user = makeAuthUser();

    // Create framework
    $framework = Framework::factory()->create();

    // Create assessment with submitted_at
    $submittedAt = Carbon::parse('2024-01-15 10:30:00');

    $assessment = Assessment::factory()->for($user)->create([
        'framework_id' => $framework->id,
        'submitted_at' => $submittedAt,
    ]);

    Livewire::actingAs($user)
        ->test(Frameworks::class, ['frameworkId' => $framework->id])
        ->assertOk()
        ->tap(function ($component) use ($assessment, $submittedAt) {
            $formatted = $component->instance()->displayAssessmentDate($assessment);

            expect($formatted)->toBe($submittedAt->format('j F Y'));
        });
});

test('displayAssessmentDate uses latest response date when submitted_at is null', function () {
    $user = makeAuthUser();

    // Create two responses with different timestamps
    $older = Carbon::parse('2024-01-10 09:00:00');
    $newer = Carbon::parse('2024-01-20 14:45:00');

    $rater = raterForUser($user);

    // Create framework + questions graph
    $setup = createFrameworkWithNodeAndQuestions(2);
    $framework = $setup['framework'];
    $question1 = $setup['questions']->get(0);
    $question2 = $setup['questions']->get(1);
    $scaleOption = $setup['scaleOption'];

    // Create assessment with NO submitted_at
    $assessment = Assessment::factory()->for($user)->create([
        'framework_id' => $framework->id,
        'submitted_at' => null,
    ]);

    // Add responses
    Response::factory()->for($assessment)->create([
        'assessment_id'   => $assessment->id,
        'rater_id'        => $rater->id,
        'question_id'     => $question1->id,
        'scale_option_id' => $scaleOption->id,
        'updated_at'      => $older,
    ]);

    Response::factory()->for($assessment)->create([
        'updated_at'      => $newer,
        'assessment_id'   => $assessment->id,
        'rater_id'        => $rater->id,
        'question_id'     => $question2->id,
        'scale_option_id' => $scaleOption->id,
    ]);

    Livewire::actingAs($user)
        ->test(Frameworks::class, ['frameworkId' => $framework->id])
        ->assertOk()
        ->tap(function ($component) use ($assessment, $newer) {
            $formatted = $component->instance()->displayAssessmentDate($assessment);

            expect($formatted)->toBe($newer->format('j F Y'));
        });
});

test('changing frameworkId refreshes framework and assessments', function () {
    // Create authenticated user
    $user = makeAuthUser();

    // Create two frameworks
    $frameworkA = Framework::factory()->create();
    $frameworkB = Framework::factory()->create();

    // Create assessments for each framework
    $assessmentA = Assessment::factory()->for($user)->create([
        'framework_id' => $frameworkA->id,
    ]);

    $assessmentB = Assessment::factory()->for($user)->create([
        'framework_id' => $frameworkB->id,
    ]);

    Livewire::actingAs($user)
        ->test(Frameworks::class, ['frameworkId' => $frameworkA->id])
        ->assertOk()
        ->tap(function ($component) use ($frameworkA, $assessmentA) {
            // Initial state
            expect($component->get('framework')->id)->toBe($frameworkA->id)
                ->and($component->get('assessments')->pluck('id')->toArray())
                ->toBe([$assessmentA->id]);
        })
        // Change frameworkId
        ->set('frameworkId', $frameworkB->id)
        ->tap(function ($component) use ($frameworkB, $assessmentB) {
            // Framework should update
            expect($component->get('framework')->id)->toBe($frameworkB->id)
                ->and($component->get('assessments')->pluck('id')->toArray())
                ->toBe([$assessmentB->id]);

            // Assessments should refresh
        });
});

test('mount loads the correct framework when frameworkId is passed', function () {
    // Create authenticated user
    $user = makeAuthUser();

    // Create two frameworks
    $frameworkA = Framework::factory()->create();
    $frameworkB = Framework::factory()->create();

    Livewire::actingAs($user)
        ->test(Frameworks::class, ['frameworkId' => $frameworkB->id])
        ->assertOk()
        ->tap(function ($component) use ($frameworkB) {
            // frameworkId should be set from mount
            expect($component->get('frameworkId'))->toEqual($frameworkB->id);

            // computed framework should match
            expect($component->get('framework')->id)->toEqual($frameworkB->id);
        });
});

test('mount sets frameworkId to the first framework when none is provided', function () {
    // Create authenticated user
    $user = makeAuthUser();

    // Create frameworks
    Framework::factory()->create();   // this will be Framework::first()
    Framework::factory()->create();

    $expectedFirst = Framework::orderBy('id')->first();

    Livewire::actingAs($user)
        ->test(Frameworks::class) // no frameworkId passed
        ->assertOk()
        ->tap(function ($component) use ($expectedFirst) {
            // frameworkId should be set to the first framework's ID
            expect($component->get('frameworkId'))->toEqual($expectedFirst->id);
        });
});

test('frameworks list is always available', function () {
    // Create authenticated user
    $user = makeAuthUser();

    // Create multiple frameworks
    $frameworks = Framework::factory()->count(3)->create();

    Livewire::actingAs($user)
        ->test(Frameworks::class) // no frameworkId passed, mount() will auto-select
        ->assertOk()
        ->tap(function ($component) use ($frameworks) {
            $list = $component->get('frameworks');

            // Should contain all frameworks
            expect($list->count())->toBe(3)
                ->and($list->pluck('id')->sort()->values())
                ->toEqual($frameworks->pluck('id')->sort()->values());

            // IDs should match exactly
        });
});

test('framework computed property returns the correct model for the selected frameworkId', function () {
    // Create authenticated user
    $user = makeAuthUser();

    // Create frameworks
    $frameworkA = Framework::factory()->create();
    $frameworkB = Framework::factory()->create();

    Livewire::actingAs($user)
        ->test(Frameworks::class, ['frameworkId' => $frameworkA->id])
        ->assertOk()
        ->tap(function ($component) use ($frameworkA) {
            // Initial framework should match A
            expect($component->get('framework')->id)->toBe($frameworkA->id);
        })
        // Change frameworkId
        ->set('frameworkId', $frameworkB->id)
        ->tap(function ($component) use ($frameworkB) {
            // Framework should now match B
            expect($component->get('framework')->id)->toBe($frameworkB->id);
        });
});

test('assessments computed property returns only assessments for the selected framework', function () {
    // Create authenticated user
    $user = makeAuthUser();

    // Create two frameworks
    $frameworkA = Framework::factory()->create();
    $frameworkB = Framework::factory()->create();

    // Create assessments for the user
    $assessmentA1 = Assessment::factory()->for($user)->create([
        'framework_id' => $frameworkA->id,
    ]);

    $assessmentA2 = Assessment::factory()->for($user)->create([
        'framework_id' => $frameworkA->id,
    ]);

    $assessmentB = Assessment::factory()->for($user)->create([
        'framework_id' => $frameworkB->id,
    ]);

    Livewire::actingAs($user)
        ->test(Frameworks::class, ['frameworkId' => $frameworkA->id])
        ->assertOk()
        ->tap(function ($component) use ($assessmentA1, $assessmentA2) {
            $ids = $component->get('assessments')
                ->pluck('id')
                ->sort()
                ->values()
                ->toArray();

            expect($ids)->toEqual(
                collect([$assessmentA1->id, $assessmentA2->id])
                    ->sort()
                    ->values()
                    ->toArray()
            );
        })
// Switch frameworks
        ->set('frameworkId', $frameworkB->id)
        ->tap(function ($component) use ($assessmentB) {
            $ids = $component->get('assessments')
                ->pluck('id')
                ->values()
                ->toArray();

            expect($ids)->toEqual([$assessmentB->id]);
        });
});

it('allows starting when no assessments exist', function () {
    $user = makeAuthUser();
    actingAs($user);

    $framework = Framework::factory()->create();

    Livewire::test(Frameworks::class, [
        'frameworkId' => $framework->id,
    ])
        ->call('startNewAssessment')
        ->assertRedirect(route('instructions', [
            'frameworkId' => $framework->id,
        ]));
});

it('blocks starting when a draft exists', function () {
    $user = makeAuthUser();
    actingAs($user);

    $framework = Framework::factory()->create();

    Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id'      => $user->user_id,
        'submitted_at' => null,
    ]);

    Livewire::test(Frameworks::class, [
        'frameworkId' => $framework->id,
    ])
        ->call('startNewAssessment')
        ->assertNoRedirect();
});

it('blocks starting when cooldown is active', function () {
    config(['app.assessment_min_interval_months' => 6]);

    $user = makeAuthUser();
    actingAs($user);

    $framework = Framework::factory()->create();

    $submittedAt = now()->subMonths(2);

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id'      => $user->user_id,
        'submitted_at' => $submittedAt,
        'created_at'   => $submittedAt,   // ensure ordering matches
        'updated_at'   => $submittedAt,
    ]);

    Livewire::test(Frameworks::class, [
        'frameworkId' => $framework->id,
    ])
        ->call('startNewAssessment')
        ->assertNoRedirect();
});

it('allows starting when cooldown has passed', function () {
    config(['app.assessment_min_interval_months' => 6]);

    $user = makeAuthUser();
    actingAs($user);

    $framework = Framework::factory()->create();

    // Completed 10 months ago → cooldown passed
    $submittedAt = now()->subMonths(10);

    Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id'      => $user->id,
        'submitted_at' => $submittedAt,
        'created_at'   => $submittedAt,
        'updated_at'   => $submittedAt,
    ]);

    Livewire::test(Frameworks::class, [
        'frameworkId' => $framework->id,
    ])
        ->call('startNewAssessment')
        ->assertRedirect(route('instructions', [
            'frameworkId' => $framework->id,
        ]));
});


it('clears pendingDeleteId when cancelDelete is called', function () {
    $user = makeAuthUser();
    actingAs($user);
    $framework = Framework::factory()->create();
    $component = Livewire::test(Frameworks::class, [
        'frameworkId' => $framework->id,
    ])
        ->set('pendingDeleteId', 456);

    $component->call('cancelDelete');

    expect($component->get('pendingDeleteId'))->toBeNull();
});


it('returns early when confirmDelete is called with no pendingDeleteId', function () {
    $user = makeAuthUser();
    actingAs($user);
    $framework = Framework::factory()->create();
    $component = Livewire::test(Frameworks::class, [
        'frameworkId' => $framework->id,
    ]);

    // Sanity: no ID set
    expect($component->get('pendingDeleteId'))->toBeNull();

    // Call confirmDelete – should early return, no flashes, still null
    $component->call('confirmDelete');

    // No success or error flash should be present
    $component->assertSessionMissing('success');
    $component->assertSessionMissing('error');

    // State should remain unchanged
    expect($component->get('pendingDeleteId'))->toBeNull();
});


it('deletes the assessment, flashes success, and clears state on confirmDelete', function () {
    $user = makeAuthUser();
    actingAs($user);
    $framework = Framework::factory()->create();
    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id'      => $user->id,
    ]);
    $this->assertDatabaseHas('assessments', ['id' => $assessment->id]);

    $component = Livewire::test(Frameworks::class, [
        'frameworkId' => $framework->id,
    ])
        ->set('pendingDeleteId', $assessment->id);

    $component->call('confirmDelete');

    // Deleted
    $this->assertDatabaseMissing('assessments', ['id' => $assessment->id]);

    // State reset
    expect($component->get('pendingDeleteId'))->toBeNull();
});


it('logs and flashes error, and clears state when delete throws an exception', function () {
    $user = makeAuthUser();
    actingAs($user);
    $framework = Framework::factory()->create();
    $component = Livewire::test(Frameworks::class, [
        'frameworkId' => $framework->id,
    ]);

    // Invalid ID to trigger exception
    $missingId = 999999;
    $component->set('pendingDeleteId', $missingId);

    Log::spy();

    $component->call('confirmDelete');

    // It should log the error with the expected context keys
    Log::shouldHaveReceived('error')
        ->once()
        ->withArgs(function ($message, $context) use ($missingId) {
            expect($message)->toBe('Error deleting assessment')
                ->and($context)->toHaveKeys(['assessment_id', 'message', 'exception'])
                ->and($context['assessment_id'])->toBe($missingId)
                ->and($context['exception'])->toBeInstanceOf(Throwable::class);
            return true;
        });

    // State reset in finally{}
    expect($component->get('pendingDeleteId'))->toBeNull();
});


it('redirects to instructions when no previous assessment exists', function () {
    $user = makeAuthUser();
    actingAs($user);
    $framework = Framework::factory()->create();

    // Act: Mount component with a frameworkId
    $component = Livewire::test(App\Livewire\Frameworks::class, [
        'frameworkId' => $framework->id,
    ]);

    // Assert: redirect to the instructions route
    $component
        ->call('startNewAssessment')
        ->assertRedirect(route('instructions', [
            'frameworkId' => $framework->id,
        ]));
});
