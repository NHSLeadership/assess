<?php

use App\Models\Assessment;
use App\Models\Framework;
use App\Models\User;
use App\Models\Rater;
use App\Models\AssessmentRater;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = makeAuthUser(['user_id' => '1000000000']);
    $this->framework = Framework::factory()->create();
    $this->assessment = Assessment::factory()->create([
        'framework_id' => $this->framework->id,
        'user_id'      => $this->user->id,
    ]);
    $this->rater = Rater::factory()->create(['user_id' => $this->user->user_id]);
});

test('assessment rater pivot persists correctly', function () {
    $pivot = AssessmentRater::create([
        'assessment_id' => $this->assessment->id,
        'rater_id'      => $this->rater->id,
        'role'          => 'manager',
        'is_self'       => false,
    ]);

    expect($pivot->exists)->toBeTrue()
        ->and($pivot->role)->toEqual('manager')
        ->and($pivot->is_self)->toBeFalse();
});

test('assessment rater belongs to assessment and rater', function () {
    $pivot = AssessmentRater::create([
        'assessment_id' => $this->assessment->id,
        'rater_id'      => $this->rater->id,
        'role'          => 'manager',
        'is_self'       => true,
    ]);

    expect($pivot->assessment->id)->toEqual($this->assessment->id)
        ->and($pivot->rater->id)->toEqual($this->rater->id);
});
test('assessment rater can be retrieved from assessment', function () {
    $pivot = AssessmentRater::create([
        'assessment_id' => $this->assessment->id,
        'rater_id'      => $this->rater->id,
        'role'          => 'peer',
        'is_self'       => false,
    ]);

    $retrievedPivot = AssessmentRater::where('assessment_id', $this->assessment->id)
        ->where('rater_id', $this->rater->id)
        ->first();

    expect($retrievedPivot)->not->toBeNull()
        ->and($retrievedPivot->role)->toEqual('peer')
        ->and($retrievedPivot->is_self)->toBeFalse();
});

test('assessment rater can be updated', function () {
    $this->assessment->raters()->attach($this->rater->id, [
        'role'    => 'direct_report',
        'is_self' => false,
    ]);

    // Update via relationship
    $this->assessment->raters()->updateExistingPivot($this->rater->id, [
        'role'    => 'manager',
        'is_self' => true,
    ]);

    $pivot = $this->assessment->raters()->first()->pivot;

    expect($pivot->role)->toEqual('manager')
        ->and($pivot->is_self)->toBeTrue();
});
