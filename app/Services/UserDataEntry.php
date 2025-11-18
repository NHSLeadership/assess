<?php

namespace App\Services;

use App\Models\Question;
use App\Models\User;
use App\Models\UserDataOption;
use App\Models\UserDataText;
use Illuminate\Support\Facades\Log;

class UserDataEntry
{
    public static function updateOrCreate(mixed $values, Question $question, int $assessmentId, User $user): void
    {
        if (!is_array($values)) {
            $values = [$values];
        }

        if (isset($question->component)) {
            foreach ($values as $value) {
                try {
                    match ($question->component) {
                        'date',
                        'email',
                        'file',
                        'number',
                        'text',
                        'textarea' => UserDataText::updateOrCreate([
                            'assessment_id' => $assessmentId,
                            'question_id'   => $question->id,
                            'user_id'       => $user->id,
                        ],[
                            'value'         => $value,
                            'updated_at'    => now(),
                        ]),
                        'checkbox',
                        'radio',
                        'select' => UserDataOption::updateOrCreate([
                            'assessment_id'        => $assessmentId,
                            'question_id'          => $question->id,
                            'user_id'              => $user->id,
                        ],[
                            'scale_option_id'      => $value,
                            'updated_at'           => now(),
                        ]),

                        default => throw new \InvalidArgumentException(
                            'You must pass in a valid data type'
                        ),
                    };
                } catch (\Throwable $e) {
                    Log::error('Error saving form field {field_id}. {details}', [
                        'question_id' => $question->id,
                        'details' => $e->getMessage()
                    ]);
                }
            }
        }
    }
}
