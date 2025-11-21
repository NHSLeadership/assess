<?php

namespace App\Filament\Resources\Assessments\RelationManagers;

use App\Filament\Resources\Assessments\Resources\Responses\ResponseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ResponsesRelationManager extends RelationManager
{
    protected static string $relationship = 'responses';

    protected static ?string $relatedResource = ResponseResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
