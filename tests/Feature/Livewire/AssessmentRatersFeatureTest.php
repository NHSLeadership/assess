<?php

use App\Enums\RaterType;
use App\Livewire\AssessmentRaters;
use App\Livewire\Assessments;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\Node;
use App\Models\Question;
use App\Models\Rater;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\RaterInvitationService;


uses(RefreshDatabase::class);

test('confirmDetach deletes assessment rater owned by current user', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
    ]);

    $rater = Rater::factory()->create([
        'subject_id' => $user->user_id,
    ]);

    $assessmentRater = AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
    ]);

    Livewire::actingAs($user)
        ->test(AssessmentRaters::class, [
            'assessmentId' => $assessment->id,
        ])
        ->call('askDetach', $assessmentRater->id)
        ->assertSet('pendingDetachId', $assessmentRater->id)
        ->call('confirmDetach')
        ->assertSet('pendingDetachId', null);

    expect(
        AssessmentRater::query()
            ->whereKey($assessmentRater->id)
            ->exists()
    )->toBeFalse();
});

test('confirmDetach does not delete assessment rater owned by another user', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
    ]);

    $otherAssessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => '2000000000',
    ]);

    $otherRater = Rater::factory()->create([
        'subject_id' => '2000000000',
    ]);

    $otherAssessmentRater = AssessmentRater::factory()->create([
        'assessment_id' => $otherAssessment->id,
        'rater_id' => $otherRater->id,
    ]);

    expect($assessment->user_id)->toBe($user->user_id)
        ->and($otherAssessment->user_id)->not->toBe($user->user_id);

    Livewire::actingAs($user)
        ->test(AssessmentRaters::class, [
            'assessmentId' => $assessment->id,
        ])
        ->call('askDetach', $otherAssessmentRater->id)
        ->assertSet('pendingDetachId', $otherAssessmentRater->id)
        ->call('confirmDetach')
        ->assertSet('pendingDetachId', null);

    expect(
        AssessmentRater::query()
            ->whereKey($otherAssessmentRater->id)
            ->exists()
    )->toBeTrue();
});



test('mount fails when assessment is owned by another user', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $otherAssessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => '2000000000',
    ]);

    Livewire::actingAs($user)
        ->test(AssessmentRaters::class, [
            'assessmentId' => $otherAssessment->id,
        ]);
})->throws(ModelNotFoundException::class);

test('askDetach sets pendingDetachId', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
    ]);

    Livewire::actingAs($user)
        ->test(AssessmentRaters::class, [
            'assessmentId' => $assessment->id,
        ])
        ->call('askDetach', 123)
        ->assertSet('pendingDetachId', 123);
});

test('inviteRater sends invitation for selected rater', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
    ]);

    $rater = Rater::factory()->create([
        'subject_id' => $user->user_id,
    ]);

    $service = Mockery::mock(RaterInvitationService::class);

    $service
        ->shouldReceive('send')
        ->once()
        ->withArgs(function ($passedAssessment, $passedRater) use ($assessment, $rater) {
            return $passedAssessment->id === $assessment->id
                && $passedRater->id === $rater->id;
        });

    app()->instance(RaterInvitationService::class, $service);

    Livewire::actingAs($user)
        ->test(AssessmentRaters::class, [
            'assessmentId' => $assessment->id,
        ])
        ->call('inviteRater', $rater->id);
});

it('returns 404 when user does not own the assessment', function () {
    $owner = makeAuthUser(['user_id' => 1]);
    $otherUser = makeAuthUser(['user_id' => 2]);

    $assessment = Assessment::factory()->create([
        'user_id' => $owner->user_id,
    ]);

    Livewire::actingAs($otherUser);

    $this->get(route('assessment-raters', [
        'assessmentId' => $assessment->id,
    ]))
        ->assertNotFound();
});

it('mounts successfully when user owns the assessment', function () {
    $user = makeAuthUser();

    $assessment = Assessment::factory()->create([
        'user_id' => $user->user_id,
    ]);

    Livewire::actingAs($user);

    Livewire::test(AssessmentRaters::class, [
        'assessmentId' => $assessment->id,
    ])
        ->assertSet('assessmentId', $assessment->id);
});

it('clears the pending detach id', function () {
    $user = makeAuthUser();

    $assessment = Assessment::factory()->create([
        'user_id' => $user->user_id,
    ]);

    Livewire::actingAs($user);

    Livewire::test(AssessmentRaters::class, [
        'assessmentId' => $assessment->id,
    ])
        ->set('pendingDetachId', 123)
        ->call('cancelDetach')
        ->assertSet('pendingDetachId', null);
});

it('returns early when there is no pending detach id', function () {
    $user = makeAuthUser();

    $assessment = Assessment::factory()->create([
        'user_id' => $user->user_id,
    ]);

    Livewire::actingAs($user);

    Livewire::test(AssessmentRaters::class, [
        'assessmentId' => $assessment->id,
    ])
        ->set('pendingDetachId', null)
        ->call('confirmDetach')
        ->assertSet('pendingDetachId', null);

    expect(session()->has('success'))->toBeFalse()
        ->and(session()->has('error'))->toBeFalse();
});

it('redirects to create rater page', function () {
    $user = makeAuthUser();

    $assessment = Assessment::factory()->create([
        'user_id' => $user->user_id,
    ]);

    Livewire::actingAs($user);

    Livewire::test(AssessmentRaters::class, [
        'assessmentId' => $assessment->id,
    ])
        ->call('addNewRater')
        ->assertRedirect(route('create-rater', [
            'assessmentId' => $assessment->id,
        ]));
});

it('redirects to edit rater page', function () {
    $user = makeAuthUser();

    $assessment = Assessment::factory()->create([
        'user_id' => $user->user_id,
    ]);

    Livewire::actingAs($user);

    Livewire::test(AssessmentRaters::class, [
        'assessmentId' => $assessment->id,
    ])
        ->call('editAssessmentRater', 123)
        ->assertRedirect(route('edit-rater', [
            'assessmentRaterId' => 123,
        ]));
});


test('rater opening assessment for the first time is shown the first node', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $node = Node::factory()->create([
        'framework_id' => $framework->id,
        'order' => 1,
    ]);

    $question = Question::factory()->create([
        'node_id' => $node->id,
        'active' => true,
        'required' => true,
    ]);

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
        'submitted_at' => null,
    ]);

    $rater = Rater::factory()->create([
        'subject_id' => $user->user_id,
    ]);

    AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'type' => RaterType::Manager,
        'started_at' => null,
        'submitted_at' => null,
    ]);

    $component = Livewire::test(Assessments::class, [
        'assessmentId' => $assessment->id,
        'raterId' => $rater->id,
    ]);

    expect($component->instance()->resolvedQuestionTexts)
        ->toHaveKey($question->id);

    $component->assertSet('currentNode.id', $node->id);
});

test('rater assessment route returns forbidden when signature is invalid', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
    ]);

    $rater = Rater::factory()->create([
        'subject_id' => $user->user_id,
    ]);

    AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'submitted_at' => null,
    ]);

    $url = route('assessment-rater', [
        'assessmentId' => $assessment->id,
        'raterId' => $rater->id,
    ]);

    $this->get($url)
        ->assertForbidden();
});

test('assessment-rater route returns 404 for unknown assessment rater', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
    ]);

    $rater = Rater::factory()->create([
        'subject_id' => $user->user_id,
    ]);

    $url = URL::signedRoute('assessment-rater', [
        'assessmentId' => $assessment->id,
        'raterId' => $rater->id,
    ]);

    $this->get($url)
        ->assertNotFound();
});

test('rater assessment route loads successfully when signature is valid', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
    ]);

    $rater = Rater::factory()->create([
        'subject_id' => $user->user_id,
    ]);

    AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
    ]);

    $url = URL::signedRoute('assessment-rater', [
        'assessmentId' => $assessment->id,
        'raterId' => $rater->id,
    ]);

    $this->get($url)
        ->assertOk();
});

