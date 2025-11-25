<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\RelationManagers;

use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Resources\QuestionVariants\QuestionVariantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $relatedResource = QuestionVariantResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
