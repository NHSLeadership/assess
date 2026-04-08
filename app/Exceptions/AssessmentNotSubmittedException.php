<?php

namespace App\Exceptions;

use Exception;

class AssessmentNotSubmittedException extends Exception
{
    public function __construct(public ?int $assessmentId = null, ?string $message = null)
    {
        parent::__construct($message);
    }

    public function report(): void
    {
        \Log::notice('Attempt to access an unsubmitted assessment', [
            'assessment_id' => $this->assessmentId,
            'user_id' => auth()->id(),
            'url' => request()->fullUrl(),
        ]);
    }

    public function render()
    {
        return response()->view('errors.403', [
            'message' => $this->getMessage(),
        ], 403);
    }
}
