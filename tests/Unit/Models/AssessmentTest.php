<?php

use App\Models\Assessment;
use App\Models\User;
use App\Models\Framework;
use App\Models\Response;
use App\Models\Rater;
use App\Models\AssessmentVariantSelection;

test('assessment has submitted_at cast defined', function () {
    $assessment = new \App\Models\Assessment();

    expect($assessment->getCasts())->toHaveKey('submitted_at', 'datetime');
});

test('notes can be mass assigned', function () {
    $assessment = new \App\Models\Assessment();
    $assessment->fill(['notes' => 'Reviewer notes']);
    expect($assessment->notes)->toEqual('Reviewer notes');
});

test('assessment has correct fillable attributes defined', function () {
    $assessment = new \App\Models\Assessment();
    expect($assessment->getFillable())->toEqual([
        'user_id',
        'framework_id',
        'submitted_at',
        'target_completion_date',
        'notes',
    ]);
});
