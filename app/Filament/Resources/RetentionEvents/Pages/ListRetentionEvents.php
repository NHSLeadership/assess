<?php

namespace App\Filament\Resources\RetentionEvents\Pages;

use App\Filament\Resources\RetentionEvents\RetentionEventResource;
use Filament\Resources\Pages\ListRecords;

class ListRetentionEvents extends ListRecords
{
    protected static string $resource = RetentionEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
