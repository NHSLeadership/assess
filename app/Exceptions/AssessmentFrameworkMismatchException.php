<?php

namespace App\Exceptions;

use Exception;

class AssessmentFrameworkMismatchException extends Exception
{
    public function __construct(
        public ?int $assessmentId = null,
        public ?int $frameworkId = null,
        ?string $message = null
    ) {
        parent::__construct($message);
    }

    public function report(): void
    {
        \Log::warning('Assessment does not belong to the specified framework', [
            'assessment_id' => $this->assessmentId,
            'framework_id' => $this->frameworkId,
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
