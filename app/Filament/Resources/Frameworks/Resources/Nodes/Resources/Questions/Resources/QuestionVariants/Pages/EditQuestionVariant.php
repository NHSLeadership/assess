<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Resources\QuestionVariants\Pages;

use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Resources\QuestionVariants\QuestionVariantResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditQuestionVariant extends EditRecord
{
    protected static string $resource = QuestionVariantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
