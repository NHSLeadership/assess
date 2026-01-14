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
        'assessment-not-permitted-now' => 'You cannot have more than one assessment in the same framework within a :months month time period. You can start your next one after :newDate.',
        'assessment-not-permitted-now-title' => 'Framework assessment limit reached',
        'assessment-in-progress' => 'You cannot start a new assessment for this framework until you finish the one already in progress.',
        'assessment-in-progress-title' => 'Assessment in progress',
        'assessment-not-belong-to-framework' => 'Assessment does not belong to this framework.',
        'assessment-not-submitted' => 'This assessment has not been submitted yet.',
        'framework-not-found' => 'Framework not found.',
    ],
    'success' => [
        'title' => 'Assessment submitted',
        'assessment-submitted' => 'Assessment submitted successfully.',
    ],
];
