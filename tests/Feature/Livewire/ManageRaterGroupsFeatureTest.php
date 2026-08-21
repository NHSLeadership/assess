<?php

use App\Livewire\ManageRaterGroups;
use App\Models\Assessment;
use App\Models\RaterGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Models\AssessmentRater;
use App\Models\Rater;
use App\Models\Framework;

uses(RefreshDatabase::class);

test('creates a new rater group', function () {
    $user = makeAuthUser([
        'user_id' => '1000000000',
    ]);

    $assessment = Assessment::factory()->create([
        'user_id' => $user->user_id,
    ]);

    Livewire::actingAs($user)
        ->test(ManageRaterGroups::class, [
            'assessmentId' => $assessment->id,
        ])
        ->set('name', 'Clinical Team')
        ->call('saveGroup');

    $this->assertDatabaseHas('rater_groups', [
        'subject_id' => $user->user_id,
        'name' => 'Clinical Team',
    ]);
});

test('renames an existing group', function () {
    $user = makeAuthUser([
        'user_id' => '1000000000',
    ]);

    $assessment = Assessment::factory()->create([
        'user_id' => $user->user_id,
    ]);

    $group = RaterGroup::factory()->create([
        'subject_id' => $user->user_id,
        'name' => 'Old Name',
    ]);

    Livewire::actingAs($user)
        ->test(ManageRaterGroups::class, [
            'assessmentId' => $assessment->id,
        ])
        ->call('editGroup', $group->id)
        ->set('name', 'New Name')
        ->call('saveGroup');

    expect(
        $group->fresh()->name
    )->toBe('New Name');
});

test('deletes a group', function () {
    $user = makeAuthUser([
        'user_id' => '1000000000',
    ]);

    $assessment = Assessment::factory()->create([
        'user_id' => $user->user_id,
    ]);

    $group = RaterGroup::factory()->create([
        'subject_id' => $user->user_id,
    ]);

    Livewire::actingAs($user)
        ->test(ManageRaterGroups::class, [
            'assessmentId' => $assessment->id,
        ])
        ->call('deleteGroup', $group->id);

    expect(
        RaterGroup::query()
            ->whereKey($group->id)
            ->exists()
    )->toBeFalse();
});

test('deleting a group clears group assignments', function () {
    $user = makeAuthUser([
        'user_id' => '1000000000',
    ]);

    $framework = Framework::factory()->create();

    $assessment = Assessment::factory()->create([
        'framework_id' => $framework->id,
        'user_id' => $user->user_id,
    ]);

    $group = RaterGroup::factory()->create([
        'subject_id' => $user->user_id,
    ]);

    $rater = Rater::factory()->create([
        'subject_id' => $user->user_id,
    ]);

    $assessmentRater = AssessmentRater::factory()->create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'rater_group_id' => $group->id,
    ]);

    Livewire::actingAs($user)
        ->test(ManageRaterGroups::class, [
            'assessmentId' => $assessment->id,
        ])
        ->call('deleteGroup', $group->id);

    expect(
        $assessmentRater->fresh()->rater_group_id
    )->toBeNull();
});

test('prevents duplicate group names for the same user', function () {
    $user = makeAuthUser([
        'user_id' => '1000000000',
    ]);

    $assessment = Assessment::factory()->create([
        'user_id' => $user->user_id,
    ]);

    RaterGroup::factory()->create([
        'subject_id' => $user->user_id,
        'name' => 'Peers',
    ]);

    Livewire::actingAs($user)
        ->test(ManageRaterGroups::class, [
            'assessmentId' => $assessment->id,
        ])
        ->set('name', 'Peers')
        ->call('saveGroup')
        ->assertHasErrors(['name']);

    expect(
        RaterGroup::query()
            ->where('subject_id', $user->user_id)
            ->where('name', 'Peers')
            ->count()
    )->toBe(1);
});

test('allows different users to use the same group name', function () {
    $user = makeAuthUser([
        'user_id' => '1000000000',
    ]);

    $assessment = Assessment::factory()->create([
        'user_id' => $user->user_id,
    ]);

    RaterGroup::factory()->create([
        'subject_id' => '2000000000',
        'name' => 'Peers',
    ]);

    Livewire::actingAs($user)
        ->test(ManageRaterGroups::class, [
            'assessmentId' => $assessment->id,
        ])
        ->set('name', 'Peers')
        ->call('saveGroup')
        ->assertHasNoErrors(['name']);

    $this->assertDatabaseHas('rater_groups', [
        'subject_id' => $user->user_id,
        'name' => 'Peers',
    ]);
});
