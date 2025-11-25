<?php

namespace App\Filament\Resources\NodeTypes\Pages;

use App\Filament\Resources\NodeTypes\NodeTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNodeType extends EditRecord
{
    protected static string $resource = NodeTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
