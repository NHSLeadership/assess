<?php

use App\Models\QuestionVariant;
use App\Enums\RaterType;

test('question variant has expected fillable attributes', function () {
    $variant = new QuestionVariant();

    expect($variant->getFillable())->toEqual([
        'question_id',
        'text',
        'rater_type',
        'priority',
    ]);
});

test('question variant casts rater_type to enum', function () {
    $variant = new QuestionVariant();

    expect($variant->getCasts())->toHaveKey('rater_type')
        ->and($variant->getCasts()['rater_type'])->toEqual(RaterType::class);
});

test('question variant defines relationship methods', function () {
    $methods = get_class_methods(QuestionVariant::class);

    expect($methods)->toContain('question')
        ->and($methods)->toContain('matches');
});

test('question variant defines conditionPairs and conditionsSummary accessor', function () {
    $methods = get_class_methods(QuestionVariant::class);

    expect($methods)->toContain('conditionPairs')
        ->and($methods)->toContain('getConditionsSummaryAttribute');
});
