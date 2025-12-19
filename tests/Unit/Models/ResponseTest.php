<?php

use App\Models\Response;

test('response has expected fillable attributes', function () {
    $response = new Response();

    expect($response->getFillable())->toEqual([
        'assessment_id',
        'rater_id',
        'question_id',
        'scale_option_id',
        'textarea',
    ]);
});

test('response defines relationship methods', function () {
    $methods = get_class_methods(Response::class);

    expect($methods)->toContain('assessment')
        ->and($methods)->toContain('rater')
        ->and($methods)->toContain('question')
        ->and($methods)->toContain('scaleOption');
});
