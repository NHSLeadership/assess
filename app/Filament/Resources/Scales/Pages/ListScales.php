<?php

namespace App\Filament\Resources\Scales\Pages;

use App\Filament\Resources\Scales\ScaleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListScales extends ListRecords
{
    protected static string $resource = ScaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
