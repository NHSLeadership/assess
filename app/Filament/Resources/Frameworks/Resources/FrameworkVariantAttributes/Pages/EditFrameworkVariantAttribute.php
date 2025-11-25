<?php

namespace App\Filament\Resources\Frameworks\Resources\FrameworkVariantAttributes\Pages;

use App\Filament\Resources\Frameworks\Resources\FrameworkVariantAttributes\FrameworkVariantAttributeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFrameworkVariantAttribute extends EditRecord
{
    protected static string $resource = FrameworkVariantAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
