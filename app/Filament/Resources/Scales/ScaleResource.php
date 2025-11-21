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
    protected static string|null|\UnitEnum $navigationGroup = 'Authoring';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ScaleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ScalesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OptionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListScales::route('/'),
            'create' => CreateScale::route('/create'),
            'edit' => EditScale::route('/{record}/edit'),
        ];
    }
}
