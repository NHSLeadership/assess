<?php

namespace App\Exceptions;

use Exception;

class AssessmentNotFoundException extends Exception
{
    public function render()
    {
        return response()->view('errors.404', [
            'message' => $this->getMessage(),
        ], 404);
    }
}

