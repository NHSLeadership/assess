<?php

use App\Models\Rater;

test('rater has expected fillable attributes', function () {
    $rater = new Rater();

    expect($rater->getFillable())->toEqual([
        'user_id',
        'name',
        'email',
    ]);
});

test('rater defines relationship methods', function () {
    $methods = get_class_methods(Rater::class);

    expect($methods)->toContain('user')
        ->and($methods)->toContain('assessments')
        ->and($methods)->toContain('responses');
});
