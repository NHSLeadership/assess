<?php

use App\Models\QuestionVariantMatch;

test('question variant match has expected fillable attributes', function () {
    $match = new QuestionVariantMatch();

    expect($match->getFillable())->toEqual([
        'question_variant_id',
        'framework_variant_attribute_id',
        'framework_variant_option_id',
    ]);
});

test('question variant match defines relationship methods', function () {
    $methods = get_class_methods(QuestionVariantMatch::class);

    expect($methods)->toContain('variant')
        ->and($methods)->toContain('attribute')
        ->and($methods)->toContain('option');
});
