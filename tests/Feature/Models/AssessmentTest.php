<?php

use App\Enums\RaterType;
use App\Models\Assessment;
use App\Models\Framework;
use App\Models\Rater;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Common setup for all tests
    $this->user = makeAuthUser(['user_id' => '1000000000']);

    $this->framework = Framework::factory()->create();
    $this->assessment = Assessment::factory()->create([
        'framework_id' => $this->framework->id,
        'user_id' => $this->user->id,
    ]);
});

test('assessment belongs to a framework', function () {
    expect($this->assessment->framework->id)->toEqual($this->framework->id);
});

test('assessment has many responses', function () {
    $rater = Rater::factory()->create([
        'subject_id' => $this->user->user_id]
    );

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
    $rater = Rater::factory()->create([
        'subject_id' => $this->user->user_id
    ]);

    $this->assessment->raters()->attach($rater->id, [
        'type' => 'manager',
    ]);

    $pivot = $this->assessment->raters()->first()->pivot;

    expect($pivot->type)->toEqual(RaterType::Manager);
});

test('assessment casts submitted_at to Carbon when persisted', function () {
    $assessment = Assessment::factory()->create([
        'framework_id' => $this->framework->id,
        'user_id' => $this->user->id,
        'submitted_at' => '2025-12-18 14:00:00',
    ]);

    expect($assessment->submitted_at)->toBeInstanceOf(Carbon::class);
});

test('report is not available when assessment is not submitted', function () {

    $assessment = Assessment::factory()->create([
        'submitted_at' => null,
    ]);

    expect($assessment->reportAvailable())->toBeFalse();
});

test('report is available for submitted self assessment', function () {

    $assessment = Assessment::factory()->create([
        'submitted_at' => now(),
    ]);

    expect($assessment->reportAvailable())->toBeTrue();
});

test('report is not available when 360 raters are incomplete', function () {

    $assessment = Assessment::factory()->create([
        'submitted_at' => now(),
    ]);

    $rater = Rater::factory()->create();

    $assessment->raters()->attach($rater->id, [
        'type' => RaterType::Peer,
        'submitted_at' => null,
    ]);

    expect($assessment->reportAvailable())->toBeFalse();
});

test('report is available when all 360 raters have submitted', function () {

    $assessment = Assessment::factory()->create([
        'submitted_at' => now(),
    ]);

    $rater = Rater::factory()->create();

    $assessment->raters()->attach($rater->id, [
        'type' => RaterType::Peer,
        'submitted_at' => now(),
    ]);

    expect($assessment->reportAvailable())->toBeTrue();
});

test('assessment without raters has self type', function () {
    expect($this->assessment->type)->toBe('Self');
});

test('assessment with raters has 360 type', function () {
    $rater = Rater::factory()->create();

    $this->assessment->raters()->attach($rater->id, [
        'type' => RaterType::Peer,
    ]);

    expect($this->assessment->type)->toBe('360');
});

test('assessment type uses preloaded raters count', function () {
    $rater = Rater::factory()->create();

    $this->assessment->raters()->attach($rater->id, [
        'type' => RaterType::Peer,
    ]);

    $assessment = Assessment::query()
        ->withCount('raters')
        ->findOrFail($this->assessment->id);

    expect($assessment->raters_count)->toBe(1)
        ->and($assessment->type)->toBe('360');
});

test('assessment type is self when preloaded raters count is zero', function () {
    $assessment = Assessment::query()
        ->withCount('raters')
        ->findOrFail($this->assessment->id);

    expect($assessment->raters_count)->toBe(0)
        ->and($assessment->type)->toBe('Self');
});

test('assessment type uses eager loaded raters relationship', function () {
    $rater = Rater::factory()->create();

    $this->assessment->raters()->attach($rater->id, [
        'type' => RaterType::Peer,
    ]);

    $assessment = Assessment::query()
        ->with('raters')
        ->findOrFail($this->assessment->id);

    expect($assessment->relationLoaded('raters'))->toBeTrue()
        ->and($assessment->type)->toBe('360');
});

test('feedback status is null for self assessment', function () {
    expect($this->assessment->feedback_status)->toBeNull();
});

test('feedback status shows progress for incomplete 360 assessment', function () {
    $rater = Rater::factory()->create();

    $this->assessment->raters()->attach($rater->id, [
        'type' => RaterType::Peer,
        'submitted_at' => null,
    ]);

    expect($this->assessment->feedback_status)->toBe('0 of 1');
});

test('feedback status is completed when all raters have submitted', function () {
    $rater = Rater::factory()->create();

    $this->assessment->raters()->attach($rater->id, [
        'type' => RaterType::Peer,
        'submitted_at' => now(),
    ]);

    expect($this->assessment->feedback_status)->toBe('Completed');
});
