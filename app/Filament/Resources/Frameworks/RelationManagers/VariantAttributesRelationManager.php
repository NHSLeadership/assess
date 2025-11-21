<?php

namespace App\Filament\Resources\Frameworks\RelationManagers;

use App\Filament\Resources\Frameworks\Resources\FrameworkVariantAttributes\FrameworkVariantAttributeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class VariantAttributesRelationManager extends RelationManager
{
    protected static string $relationship = 'variantAttributes';

    protected static ?string $relatedResource = FrameworkVariantAttributeResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
