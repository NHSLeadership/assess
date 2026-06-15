<?php

use App\Enums\Audience;
use App\Enums\RaterType;
use App\Models\QuestionVariant;

test('question variant has expected fillable attributes', function () {
    $variant = new QuestionVariant;

    expect($variant->getFillable())->toEqual([
        'question_id',
        'text',
        'audience',
        'priority',
    ]);
});

test('question variant casts audience to enum', function () {
    $variant = new QuestionVariant;

    expect($variant->getCasts())->toHaveKey('audience')
        ->and($variant->getCasts()['audience'])->toEqual(Audience::class);
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
