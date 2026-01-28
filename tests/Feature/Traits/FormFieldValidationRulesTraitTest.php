<?php

use Tests\Support\FormFieldValidationRulesFake;

test('trait works inside Laravel feature test environment', function () {
    $fake = new FormFieldValidationRulesFake;

    $rules = $fake->getRulesForType([
        'component' => 'textarea',
        'maxLength' => 1000,
        'required' => true,
    ]);

    expect($rules)->toContain('max:65535')
        ->and($rules)->toContain('max:1000')
        ->and($rules)->toContain('required');
});
