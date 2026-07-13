<?php

use App\Livewire\EditRater;
use App\Livewire\SelectRater;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\Rater;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;


uses(RefreshDatabase::class);

test('store attaches selected rater to assessment', function () {
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
        ->test(SelectRater::class, [
            'assessmentId' => $assessment->id,
        ])
        ->set('selectedRaterId', $rater->id)
        ->set('type', 'other')
        ->call('store')
        ->assertHasNoErrors();

    expect(
        AssessmentRater::query()
            ->where('assessment_id', $assessment->id)
            ->where('rater_id', $rater->id)
            ->exists()
    )->toBeTrue();
});

test('store requires selected rater', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
    ]);

    Livewire::actingAs($user)
        ->test(SelectRater::class, [
            'assessmentId' => $assessment->id,
        ])
        ->set('type', 'other')
        ->call('store')
        ->assertHasErrors([
            'selectedRaterId' => 'required',
        ]);
});
