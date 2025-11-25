<?php

namespace App\Filament\Resources\Assessments\Resources\Responses\Pages;

use App\Filament\Resources\Assessments\Resources\Responses\ResponseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResponse extends EditRecord
{
    protected static string $resource = ResponseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
