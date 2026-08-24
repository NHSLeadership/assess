<?php

use App\Enums\RaterType;
use App\Livewire\EditRater;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Framework;
use App\Models\Rater;

use App\Models\RaterGroup;
use App\Services\RaterInvitationService;
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

    $raterGroup = RaterGroup::factory()->create(['subject_id' => $user->user_id]);
    AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'type' => 'other',
        'rater_group_id' => $raterGroup->id,
    ]);

    Livewire::actingAs($user)
        ->test(EditRater::class, [
            'assessmentId' => $assessment->id,
        ])
        ->set('name', 'Existing Rater')
        ->set('email', 'existing@example.com')
        ->set('type', 'other')
        ->set('groupId', $raterGroup->id)
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

test('store allows other rater without group', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
    ]);

    Livewire::actingAs($user)
        ->test(EditRater::class, [
            'assessmentId' => $assessment->id,
        ])
        ->set('name', 'Other Rater')
        ->set('email', 'other@example.com')
        ->set('type', RaterType::Other->value)
        ->call('store')
        ->assertHasNoErrors();
});

test('store allows manager without group', function () {
    $user = makeAuthUser(['user_id' => '1000000000']);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
    ]);

    Livewire::actingAs($user)
        ->test(EditRater::class, [
            'assessmentId' => $assessment->id,
        ])
        ->set('name', 'Manager Rater')
        ->set('email', 'manager@example.com')
        ->set('type', RaterType::Manager->value)
        ->call('store')
        ->assertHasNoErrors();
});

test('creating a rater sends an invitation', function () {
    $user = makeAuthUser([
        'user_id' => '1000000000',
        'permissions' => [
            [
                'permission_name' => 'assess:360',
            ],
        ],
    ]);

    $assessment = Assessment::factory()->create([
        'user_id' => $user->user_id,
    ]);

    $service = Mockery::mock(RaterInvitationService::class);

    $service
        ->shouldReceive('send')
        ->once()
        ->withArgs(function ($passedAssessment, $passedRater) use ($assessment) {
            return $passedAssessment->id === $assessment->id
                && $passedRater->email === 'manager@example.com';
        });

    app()->instance(RaterInvitationService::class, $service);

    Livewire::actingAs($user)
        ->test(EditRater::class, [
            'assessmentId' => $assessment->id,
        ])
        ->set('name', 'Test Manager')
        ->set('email', 'manager@example.com')
        ->set('type', RaterType::Manager->value)
        ->call('store');
});

test('editing a rater does not send an invitation', function () {
    $user = makeAuthUser([
        'user_id' => '1000000000',
        'permissions' => [
            [
                'permission_name' => 'assess:360',
            ],
        ],
    ]);

    $assessment = Assessment::factory()->create([
        'user_id' => $user->user_id,
    ]);

    $rater = Rater::factory()->create([
        'subject_id' => $user->user_id,
    ]);

    $assessmentRater = AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'type' => RaterType::Manager,
    ]);

    $service = Mockery::mock(RaterInvitationService::class);

    $service->shouldNotReceive('send');

    app()->instance(RaterInvitationService::class, $service);

    Livewire::actingAs($user)
        ->test(EditRater::class, [
            'assessmentRaterId' => $assessmentRater->id,
        ])
        ->set('type', RaterType::Peer->value)
        ->call('store');

    expect(
        $assessmentRater->fresh()->type
    )->toBe(RaterType::Peer);
});
