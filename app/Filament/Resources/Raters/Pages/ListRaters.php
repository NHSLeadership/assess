<?php

namespace App\Filament\Resources\Raters\Pages;

use App\Filament\Resources\Raters\RaterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRaters extends ListRecords
{
    protected static string $resource = RaterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
