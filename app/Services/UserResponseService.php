<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Response;
use Illuminate\Support\Facades\Log;

class UserResponseService
{
    public static function updateOrCreate(mixed $values, Question $question, int $assessmentId, int $raterId): void
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
                        'textarea' => Response::updateOrCreate([
                            'assessment_id' => $assessmentId,
                            'question_id'   => $question->id,
                            'rater_id'      => $raterId,
                        ],[
                            'textarea'     => $value,
                            'updated_at'    => now(),
                        ]),
                        'checkbox',
                        'radio',
                        'select' => Response::updateOrCreate([
                            'assessment_id'   => $assessmentId,
                            'question_id'     => $question->id,
                            'rater_id'        => $raterId,
                        ],[
                            'scale_option_id' => $value,
                            'updated_at'      => now(),
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
