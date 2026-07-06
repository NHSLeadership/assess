<?php

use App\Livewire\EditRater;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\Rater;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;


uses(RefreshDatabase::class);

test('mount loads existing assessment rater details for editing', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
    ]);

    $rater = Rater::factory()->create([
        'subject_id' => $user->user_id,
        'name' => 'Jane Smith',
        'email' => 'jane.smith@example.com',
    ]);

    $assessmentRater = AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'type' => 'other',
        'rater_group_id' => null,
    ]);

    Livewire::actingAs($user)
        ->test(EditRater::class, [
            'assessmentRaterId' => $assessmentRater->id,
        ])
        ->assertSet('assessmentRaterId', $assessmentRater->id)
        ->assertSet('assessmentId', $assessment->id)
        ->assertSet('raterId', $rater->id)
        ->assertSet('name', 'Jane Smith')
        ->assertSet('email', 'jane.smith@example.com')
        ->assertSet('type', 'other')
        ->assertSet('groupId', null);
});

test('store prevents duplicate rater being added to assessment', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
    ]);

    $rater = Rater::factory()->create([
        'subject_id' => $user->user_id,
        'name' => 'Existing Rater',
        'email' => 'existing@example.com',
    ]);

    AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'type' => 'other',
    ]);

    Livewire::actingAs($user)
        ->test(EditRater::class, [
            'assessmentId' => $assessment->id,
        ])
        ->set('name', 'Existing Rater')
        ->set('email', 'existing@example.com')
        ->set('type', 'other')
        ->call('store')
        ->assertHasErrors(['email']);
});

test('useSelectedRater loads selected rater details', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
    ]);

    $rater = Rater::factory()->create([
        'subject_id' => $user->user_id,
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
    ]);

    Livewire::actingAs($user)
        ->test(EditRater::class, [
            'assessmentId' => $assessment->id,
        ])
        ->set('selectedRaterId', $rater->id)
        ->call('useSelectedRater')
        ->assertSet('raterId', $rater->id)
        ->assertSet('name', 'Jane Smith')
        ->assertSet('email', 'jane@example.com')
        ->assertSet('showNewRater', false);
});
