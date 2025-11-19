<?php

namespace App\Services;

use App\Models\FormField;
use App\Models\User;
use App\Models\UserDataOption;
use App\Models\UserDataText;
use Illuminate\Support\Facades\Log;

class UserDataEntry
{
    public static function updateOrCreate(mixed $values, FormField $formField, int $assessmentId, User $user): void
    {
        if (!is_array($values)) {
            $values = [$values];
        }

        if (isset($formField->element)) {
            foreach ($values as $value) {
                try {
                    match ($formField->element) {
                        'date',
                        'email',
                        'file',
                        'number',
                        'text',
                        'textarea' => UserDataText::updateOrCreate([
                            'assessment_id' => $assessmentId,
                            'form_field_id' => $formField->id,
                            'user_id'       => $user->id,
                        ],[
                            'value'         => $value,
                            'updated_at'    => now(),
                        ]),
                        'checkbox',
                        'radio',
                        'select' => UserDataOption::updateOrCreate([
                            'assessment_id' => $assessmentId,
                            'form_field_id' => $formField->id,
                            'user_id'       => $user->id,
                        ],[
                            'form_field_option_id' => $value,
                            'updated_at'           => now(),
                        ]),

                        default => throw new \InvalidArgumentException(
                            'You must pass in a valid data type'
                        ),
                    };
                } catch (\Throwable $e) {
                    Log::error('Error saving form field {field_id}. {details}', [
                        'field_id' => $formField->id,
                        'details' => $e->getMessage()
                    ]);
                }
            }
        }
    }
}
