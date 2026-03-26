<?php

namespace App\Filament\Resources\Scales;

use App\Filament\Resources\Scales\Pages\CreateScale;
use App\Filament\Resources\Scales\Pages\EditScale;
use App\Filament\Resources\Scales\Pages\ListScales;
use App\Filament\Resources\Scales\Schemas\ScaleForm;
use App\Filament\Resources\Scales\Tables\ScalesTable;
use App\Models\Scale;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ScaleResource extends Resource
{
    protected static ?string $model = Scale::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return ScaleForm::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return ScalesTable::configure($table);
    }

    #[\Override]
    public static function getRelations(): array
    {
        return [
            RelationManagers\OptionsRelationManager::class,
        ];
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListScales::route('/'),
            'create' => CreateScale::route('/create'),
            'edit' => EditScale::route('/{record}/edit'),
        ];
    }
}
