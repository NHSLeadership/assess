<?php

use App\Enums\RaterType;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Rater;
use App\Models\RaterGroup;

test('assessment rater casts type to enum', function () {
    $pivot = new AssessmentRater(['type' => 'manager']);

    expect($pivot->type)->toEqual(RaterType::Manager);
});

test('assessment rater identifies self rater correctly', function () {
    $pivot = new AssessmentRater(['type' => 'self']);

    expect($pivot->isSelf())->toBeTrue();
});

test('assessment rater identifies external rater correctly', function () {
    $pivot = new AssessmentRater(['type' => 'peer']);

    expect($pivot->isSelf())->toBeFalse();
});


test('assessment rater has fillable attributes', function () {
    $user = makeAuthUser();
    $assessment = Assessment::factory()->create([
        'user_id' => $user->user_id
    ]);
    $rater = Rater::factory()->create();

    $pivot = AssessmentRater::create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'type' => 'manager',
    ]);

    expect($pivot->assessment_id)->toEqual($assessment->id)
        ->and($pivot->rater_id)->toEqual($rater->id)
        ->and($pivot->type)->toEqual(RaterType::Manager);
});


test('assessment rater has custom group', function () {
    $user = makeAuthUser();
    $assessment = Assessment::factory()->create([
        'user_id' => $user->user_id,
    ]);
    $rater = Rater::factory()->create();

    $group = RaterGroup::create([
        'user_id' => $user->user_id,
        'name' => 'test',
    ]);

    $pivot = AssessmentRater::create([
        'assessment_id' => $assessment->id,
        'rater_id' => $rater->id,
        'type' => 'other',
        'rater_group_id' => $group->id,
    ]);

    expect($pivot->group)->not->toBeNull()
        ->and($pivot->group->name)->toEqual('test')
        ->and($pivot->type)->toEqual(RaterType::Other);
});

