<?php

namespace App\Exceptions;

use Exception;

class AssessmentFrameworkMismatchException extends Exception
{
    public ?int $assessmentId;
    public ?int $frameworkId;

    public function __construct(
        ?int $assessmentId = null,
        ?int $frameworkId = null,
        string $message = null
    ) {
        $this->assessmentId = $assessmentId;
        $this->frameworkId = $frameworkId;

        parent::__construct($message);
    }

    public function report()
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
