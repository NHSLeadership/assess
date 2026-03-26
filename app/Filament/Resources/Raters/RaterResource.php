<?php

namespace App\Filament\Resources\Raters;

use App\Filament\Resources\Raters\Pages\CreateRater;
use App\Filament\Resources\Raters\Pages\EditRater;
use App\Filament\Resources\Raters\Pages\ListRaters;
use App\Filament\Resources\Raters\Schemas\RaterForm;
use App\Filament\Resources\Raters\Tables\RatersTable;
use App\Models\Rater;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RaterResource extends Resource
{
    protected static ?string $model = Rater::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return RaterForm::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return RatersTable::configure($table);
    }

    #[\Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListRaters::route('/'),
            'create' => CreateRater::route('/create'),
            'edit' => EditRater::route('/{record}/edit'),
        ];
    }
}
