<?php

return [
    'retention_years' => (int) env('RETENTION_YEARS', 1),
    'warning_days' => (int) env('EXPIRY_WARNING_DAYS',30),
    'minimum_days_after_warning' => (int) env('MIN_DAYS_AFTER_WARNING', 7),
];
