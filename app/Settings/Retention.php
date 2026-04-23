<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class Retention extends Settings
{

    public int $retention_years;
    public int $expiry_warning_days;
    public int $min_days_after_warning;

    public static function group(): string
    {
        return 'retention';
    }
}