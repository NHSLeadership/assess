<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Alerts - Errors, messages and warnings
    |--------------------------------------------------------------------------
    */

    'errors' => [
        'title' => 'Error',
        'assessment-initialise' => 'Could not initialise new assessment. Please try again later.',
        'assessment-not-found' => 'Assessment not found.',
        'assessment-already-submitted' => 'This assessment has already been submitted.',
        'assessment-not-permitted-now' => 'Assessments can only be taken every :months months. You can start your next one after :newDate.',
        'assessment-in-progress' => 'You cannot start a new assessment for this framework until you finish the one already in progress.',
    ],
    'success' => [
        'title' => 'Assessment submitted',
        'assessment-submitted' => 'Assessment submitted successfully.',
    ],
];
