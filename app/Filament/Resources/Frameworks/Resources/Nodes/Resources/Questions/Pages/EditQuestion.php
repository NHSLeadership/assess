<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Pages;

use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\QuestionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditQuestion extends EditRecord
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
