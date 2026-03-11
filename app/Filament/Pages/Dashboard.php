<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // Hide dashboard heading
    public function getHeading(): string | null
    {
        return null;
    }
}