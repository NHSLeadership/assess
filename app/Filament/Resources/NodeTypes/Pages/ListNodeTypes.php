<?php

namespace App\Filament\Resources\NodeTypes\Pages;

use App\Filament\Resources\NodeTypes\NodeTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNodeTypes extends ListRecords
{
    protected static string $resource = NodeTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
