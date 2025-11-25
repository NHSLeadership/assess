<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\Pages;

use App\Filament\Resources\Frameworks\Resources\Nodes\NodeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNode extends EditRecord
{
    protected static string $resource = NodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
