<?php

namespace App\Filament\Resources\Scales\Pages;

use App\Filament\Resources\Scales\ScaleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditScale extends EditRecord
{
    protected static string $resource = ScaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
