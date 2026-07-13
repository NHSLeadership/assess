<?php

use App\Livewire\AssessmentCompleted;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\Rater;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('redirects when no assessment Id is provided', function () {
    Livewire::test(AssessmentCompleted::class)
        ->assertRedirect(route('frameworks'));
});

it('throws ModelNotFoundException when assessment is missing', function () {
    $user = makeAuthUser();
    $this->actingAs($user);

    $this->expectException(ModelNotFoundException::class);

    Livewire::test(AssessmentCompleted::class, [
        'assessmentId' => 123,
    ]);
});


it('redirects to normal assessment report when raterId is not provided', function () {

    $user = makeAuthUser();
    Livewire::actingAs($user);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
        'submitted_at' => now(),
    ]);

    Livewire::test(AssessmentCompleted::class, [
        'assessmentId' => $assessment->id,
    ])
        ->call('viewReport')
        ->assertRedirect(route('assessment-report', [
            'frameworkId' => $framework->id,
            'assessmentId' => $assessment->id,
        ]));
});

it('returns 404 when assessment rater has not been submitted', function () {

    $user = makeAuthUser();
    $this->actingAs($user);

    $rater = Rater::factory()->create([
        'subject_id' => $user->user_id,
    ]);

    $assessment = Assessment::factory()->create([
        'user_id' => $user->user_id,
    ]);

    AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'submitted_at' => null,
    ]);

    $url = URL::signedRoute('assessment-rater-completed', [
        'assessmentId' => $assessment->id,
        'raterId' => $rater->id,
    ]);

    $this->get($url)
        ->assertNotFound();
});

it('redirects to frameworks when assessment id is missing', function () {
    Livewire::test(AssessmentCompleted::class, [
        'assessmentId' => null,
    ])
        ->assertRedirect(route('frameworks'));
});

it('redirects to frameworks when assessment id is not numeric', function () {
    Livewire::test(AssessmentCompleted::class, [
        'assessmentId' => 'abc',
    ])
        ->assertRedirect(route('frameworks'));
});

it('redirects to frameworks when assessment is not submitted', function () {
    $user = makeAuthUser();

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
        'submitted_at' => null,
    ]);

    $this->actingAs($user);

    $this->get(route('assessment-completed', [
        'assessmentId' => $assessment->id,
    ]))
        ->assertRedirect(route('frameworks'));
});
