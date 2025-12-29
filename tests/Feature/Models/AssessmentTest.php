<?php

use App\Models\Assessment;
use App\Models\User;
use App\Models\Framework;
use App\Models\Response;
use App\Models\Rater;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Common setup for all tests
    $this->user = makeAuthUser(['user_id' => '1000000000']);

    $this->framework = Framework::factory()->create();
    $this->assessment = Assessment::factory()->create([
        'framework_id' => $this->framework->id,
        'user_id'      => $this->user->id,
    ]);
});

test('assessment belongs to a framework', function () {
    expect($this->assessment->framework->id)->toEqual($this->framework->id);
});

test('assessment has many responses', function () {
    $rater = Rater::factory()->create(['user_id' => $this->user->user_id]);

    // Create node + questions via helper
    $setup = createNodeWithQuestions(3, 'scale', ['framework' => $this->framework]);
    $questions = $setup['questions'];

    // Create scale + option
    $scaleSetup = createScaleWithOption();
    $option = $scaleSetup['scaleOption'];

    foreach ($questions as $question) {
        createResponseForAssessment($this->assessment, $rater, $question, $option);
    }

    expect($this->assessment->responses)->toHaveCount(3);
});

test('assessment raters relationship works', function () {
    $rater = Rater::factory()->create(['user_id' => $this->user->user_id]);

    $this->assessment->raters()->attach($rater->id, [
        'role'    => 'manager',
        'is_self' => false,
    ]);

    $pivot = $this->assessment->raters()->first()->pivot;

    expect($pivot->role)->toEqual('manager')
        ->and($pivot->is_self)->toBeFalse();
});

test('assessment casts submitted_at to Carbon when persisted', function () {
    $assessment = Assessment::factory()->create([
        'framework_id' => $this->framework->id,
        'user_id'      => $this->user->id,
        'submitted_at' => '2025-12-18 14:00:00',
    ]);

    expect($assessment->submitted_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});
