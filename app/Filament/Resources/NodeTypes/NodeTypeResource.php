<?php

namespace App\Filament\Resources\NodeTypes;

use App\Filament\Resources\NodeTypes\Pages\CreateNodeType;
use App\Filament\Resources\NodeTypes\Pages\EditNodeType;
use App\Filament\Resources\NodeTypes\Pages\ListNodeTypes;
use App\Filament\Resources\NodeTypes\Schemas\NodeTypeForm;
use App\Filament\Resources\NodeTypes\Tables\NodeTypesTable;
use App\Models\NodeType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NodeTypeResource extends Resource
{
    protected static ?string $model = NodeType::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Authoring';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return NodeTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NodeTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNodeTypes::route('/'),
            'create' => CreateNodeType::route('/create'),
            'edit' => EditNodeType::route('/{record}/edit'),
        ];
    }
}
