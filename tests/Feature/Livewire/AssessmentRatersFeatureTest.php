<?php

use App\Livewire\AssessmentRaters;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\Rater;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

use App\Services\RaterInvitationService;

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
