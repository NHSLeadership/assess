<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class Banner extends Settings
{

    public string $title;
    public string $body;

    public static function group(): string
    {
        return 'banner';
    }
}