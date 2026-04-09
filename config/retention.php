<?php

return [
    'retention_years' => (int) env('RETENTION_YEARS', 1),
    'warning_months'  => (int) env('EXPIRY_WARNING_MONTHS', 1),
];
