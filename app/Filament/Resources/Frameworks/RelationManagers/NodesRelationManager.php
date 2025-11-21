<?php

namespace App\Filament\Resources\Frameworks\RelationManagers;

use App\Filament\Resources\Frameworks\Resources\Nodes\NodeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class NodesRelationManager extends RelationManager
{
    protected static string $relationship = 'nodes';

    protected static ?string $relatedResource = NodeResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
