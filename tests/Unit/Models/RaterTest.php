<?php

use App\Models\Rater;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('rater has expected fillable attributes', function () {
    $rater = new Rater;

    expect($rater->getFillable())->toEqual([
        'subject_id',
        'name',
        'email',
    ]);
});

test('rater defines relationship methods', function () {
    $methods = get_class_methods(Rater::class);

    expect($methods)->toContain('assessmentRaters')
        ->and($methods)->toContain('assessments')
        ->and($methods)->toContain('responses');
});

test('rater relationships are configured correctly', function () {
    $rater = new Rater();

    expect($rater->assessments())->toBeInstanceOf(BelongsToMany::class);

    expect($rater->assessmentRaters())->toBeInstanceOf(HasMany::class);

    expect($rater->responses())->toBeInstanceOf(HasMany::class);
});