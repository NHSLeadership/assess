<?php

use Tests\Support\FormFieldValidationRulesFake;

test('getRulesForType returns base rules for known component', function () {
    $fake = new FormFieldValidationRulesFake;

    $rules = $fake->getRulesForType(['component' => 'email']);

    expect($rules)->toContain('email', 'rfc,dns', 'max:255');
});

test('getRulesForType adds minLength rule', function () {
    $fake = new FormFieldValidationRulesFake;

    $rules = $fake->getRulesForType([
        'component' => 'text',
        'minLength' => 5,
    ]);

    expect($rules)->toContain('min:5');
});

test('getRulesForType adds maxLength rule', function () {
    $fake = new FormFieldValidationRulesFake;

    $rules = $fake->getRulesForType([
        'component' => 'text',
        'maxLength' => 120,
    ]);

    expect($rules)->toContain('max:120');
});

test('getRulesForType adds required rule when required is true', function () {
    $fake = new FormFieldValidationRulesFake;

    $rules = $fake->getRulesForType([
        'component' => 'text',
        'required' => true,
    ]);

    expect($rules)->toContain('required');
});

test('getRulesForType adds sometimes and nullable when required is false', function () {
    $fake = new FormFieldValidationRulesFake;

    $rules = $fake->getRulesForType([
        'component' => 'text',
        'required' => false,
    ]);

    expect($rules)->toContain('sometimes', 'nullable');
});

test('getRulesForType returns empty array for unknown component', function () {
    $fake = new FormFieldValidationRulesFake;

    $rules = $fake->getRulesForType(['component' => 'unknown']);

    expect($rules)->toBe(['sometimes','nullable']);
});

test('getMaxLengthForType returns correct max length', function () {
    $fake = new FormFieldValidationRulesFake;

    $max = $fake->getMaxLengthForType('email');

    expect($max)->toBe(255);
});

test('getMaxLengthForType returns null when no max rule exists', function () {
    $fake = new FormFieldValidationRulesFake;

    $max = $fake->getMaxLengthForType('number');

    expect($max)->toBeNull();
});

