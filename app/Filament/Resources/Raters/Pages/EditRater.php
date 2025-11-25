<?php

namespace App\Filament\Resources\Raters\Pages;

use App\Filament\Resources\Raters\RaterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRater extends EditRecord
{
    protected static string $resource = RaterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
