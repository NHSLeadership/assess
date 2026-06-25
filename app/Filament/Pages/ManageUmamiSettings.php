<?php

namespace App\Filament\Pages;

use JeffersonGoncalves\Filament\Umami\Pages\ManageUmamiSettings as BasePage;

class ManageUmamiSettings extends BasePage
{
    public static function canAccess(): bool
    {
        return auth()->user()?->can('settings:update') ?? false;
    }
}
