<?php

use App\Models\Assessment;

test('assessment has submitted_at cast defined', function () {
    $assessment = new Assessment;

    expect($assessment->getCasts())->toHaveKey('submitted_at', 'datetime');
});

test('notes can be mass assigned', function () {
    $assessment = new Assessment;
    $assessment->fill(['notes' => 'Reviewer notes']);
    expect($assessment->notes)->toEqual('Reviewer notes');
});

test('assessment has correct fillable attributes defined', function () {
    $assessment = new Assessment;
    expect($assessment->getFillable())->toEqual([
        'user_id',
        'framework_id',
        'submitted_at',
        'target_completion_date',
        'notes',
    ]);
});
