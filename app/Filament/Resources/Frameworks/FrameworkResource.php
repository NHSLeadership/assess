<?php

namespace App\Filament\Resources\Frameworks;

use App\Filament\Resources\Frameworks\Pages\CreateFramework;
use App\Filament\Resources\Frameworks\Pages\EditFramework;
use App\Filament\Resources\Frameworks\Pages\ListFrameworks;
use App\Filament\Resources\Frameworks\RelationManagers\NodesRelationManager;
use App\Filament\Resources\Frameworks\RelationManagers\VariantAttributesRelationManager;
use App\Filament\Resources\Frameworks\Schemas\FrameworkForm;
use App\Filament\Resources\Frameworks\Tables\FrameworksTable;
use App\Models\Framework;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FrameworkResource extends Resource
{
    protected static ?string $model = Framework::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Authoring';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return FrameworkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FrameworksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            'nodes' => NodesRelationManager::class,
            'variants' => VariantAttributesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFrameworks::route('/'),
            'create' => CreateFramework::route('/create'),
            'edit' => EditFramework::route('/{record}/edit'),
        ];
    }
}
