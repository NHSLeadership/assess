<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\RelationManagers;

use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Signposts\SignpostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class SignpostsRelationManager extends RelationManager
{
    protected static string $relationship = 'signposts';

    protected static ?string $relatedResource = SignpostResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
