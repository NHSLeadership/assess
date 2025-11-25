<?php
namespace App\Traits;

trait FormFieldValidationRulesTrait
{
    public static array $typeRules = [
        'date' => ['date'],
        'email' => ['email', 'rfc,dns', 'max:255'],
        'number' => ['numeric'],
        'radio' => ['numeric'],
        'checkbox' => ['sometimes', 'array'],
        'select' => ['numeric'],
        'text' => ['sometimes', 'max:255'],
        'textarea' => ['sometimes', 'max:255'],
    ];

    public function getRulesForType($question = null): array
    {
        $rules = self::$typeRules[$question['component'] ?? null] ?? [];

        if (!empty($question['minLength'])) {
            $rules[] = 'min:' . $question['minLength'];
        }
        if (!empty($question['maxLength'])) {
            $rules[] = 'max:' . $question['maxLength'];
        }
        if (!empty($question['required'])) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        return $rules;
    }
}
