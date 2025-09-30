<?php
namespace App\Traits;

trait FormFieldValidationRulesTrait
{
    public static array $typeRules = [
        'date' => ['date'],
        'email' => ['email', 'rfc,dns', 'max:255'],
        'number' => ['numeric'],
        'radio' => ['sometimes'],
        'checkbox' => ['sometimes', 'array'],
        'select' => ['sometimes'],
        'text' => ['sometimes', 'max:255'],
        'textarea' => ['sometimes', 'max:255'],
    ];

    public function getRulesForType($field = null): array
    {
        $rules = self::$typeRules[$field['element'] ?? null] ?? [];

        if (!empty($field['minLength'])) {
            $rules[] = 'min:' . $field['minLength'];
        }
        if (!empty($field['maxLength'])) {
            $rules[] = 'max:' . $field['maxLength'];
        }
        if (!empty($field['required'])) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        return $rules;
    }
}
