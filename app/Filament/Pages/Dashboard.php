<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // Hide dashboard heading
    #[\Override]
    public function getHeading(): ?string
    {
        return null;
    }
}
