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
        'text' => ['max:255'],
        'textarea' => ['max:65535' ],
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
            $rules[] = 'sometimes';
            $rules[] = 'nullable';
        }

        return $rules;
    }

    /**
     * Get the maximum length for a given form field type.
     *
     * @param string $type The form field type.
     * @return int|null The maximum length if defined, otherwise null.
     */
    public function getMaxLengthForType(string $type): ?int
    {
        $rules = self::$typeRules[$type] ?? [];

        foreach ($rules as $rule) {
            if (str_starts_with($rule, 'max:')) {
                return (int) substr(trim($rule), 4);
            }
        }
        return null;
    }
}
