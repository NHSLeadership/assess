<?php

use App\Models\Question;
use App\Enums\ResponseType;

test('question has expected fillable attributes', function () {
    $question = new Question();

    expect($question->getFillable())->toEqual([
        'node_id',
        'title',
        'text',
        'hint',
        'placeholder',
        'response_type',
        'scale_id',
        'required',
        'order',
        'active',
    ]);
});

test('question casts required and active to boolean', function () {
    $question = new Question();

    expect($question->getCasts())->toHaveKey('required')
        ->and($question->getCasts()['required'])->toEqual('boolean')
        ->and($question->getCasts())->toHaveKey('active')
        ->and($question->getCasts()['active'])->toEqual('boolean');
});

test('question defines relationship methods', function () {
    $methods = get_class_methods(Question::class);

    expect($methods)->toContain('node')
        ->and($methods)->toContain('scale')
        ->and($methods)->toContain('variants')
        ->and($methods)->toContain('responses');
});

test('question component accessor returns expected component name', function () {
    // Fake a response_type attribute
    $question = new Question([
        'response_type' => ResponseType::TYPE_TEXTAREA->value,
    ]);

    // TEXT enum should map to its component()
    expect($question->component)->toEqual(ResponseType::TYPE_TEXTAREA->component());
});

test('question name accessor returns question_id prefixed name', function () {
    $question = new Question();
    $question->setRawAttributes(['id' => 42]);

    expect($question->name)->toEqual('question_42');
});
