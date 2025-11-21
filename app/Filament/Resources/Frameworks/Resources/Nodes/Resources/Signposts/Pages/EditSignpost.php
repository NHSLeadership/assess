<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Signposts\Pages;

use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Signposts\SignpostResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSignpost extends EditRecord
{
    protected static string $resource = SignpostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
