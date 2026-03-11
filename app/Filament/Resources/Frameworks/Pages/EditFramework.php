<?php

namespace App\Filament\Resources\Frameworks\Pages;

use App\Filament\Resources\Frameworks\FrameworkResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditFramework extends EditRecord
{
    protected static string $resource = FrameworkResource::class;
    protected static ?string $navigationLabel = 'Framework';
    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedLifebuoy;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
