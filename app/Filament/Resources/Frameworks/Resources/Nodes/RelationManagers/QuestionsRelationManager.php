<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\RelationManagers;

use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\QuestionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $relatedResource = QuestionResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
