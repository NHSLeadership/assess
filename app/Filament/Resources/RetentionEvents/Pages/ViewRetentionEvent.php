<?php

namespace App\Filament\Resources\RetentionEvents\Pages;

use App\Filament\Resources\RetentionEvents\RetentionEventResource;
use Filament\Resources\Pages\ViewRecord;

class ViewRetentionEvent extends ViewRecord
{
    protected static string $resource = RetentionEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
