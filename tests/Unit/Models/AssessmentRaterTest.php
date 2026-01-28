<?php

use App\Models\AssessmentRater;

test('assessment rater casts is_self to boolean', function () {
    $pivot = new AssessmentRater(['is_self' => 1]);
    expect($pivot->is_self)->toBeTrue();

    $pivot = new AssessmentRater(['is_self' => 0]);
    expect($pivot->is_self)->toBeFalse();
});

test('assessment rater has fillable attributes', function () {
    $pivot = new AssessmentRater([
        'assessment_id' => 10,
        'rater_id'      => 20,
        'role'          => 'manager',
        'is_self'       => true,
    ]);

    expect($pivot->assessment_id)->toEqual(10)
        ->and($pivot->rater_id)->toEqual(20)
        ->and($pivot->role)->toEqual('manager')
        ->and($pivot->is_self)->toBeTrue();
});
