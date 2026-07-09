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
