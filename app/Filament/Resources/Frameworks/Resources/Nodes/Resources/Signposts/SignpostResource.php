<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Signposts;

use App\Filament\Resources\Frameworks\Resources\Nodes\NodeResource;
use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Signposts\Pages\CreateSignpost;
use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Signposts\Pages\EditSignpost;
use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Signposts\Schemas\SignpostForm;
use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Signposts\Tables\SignpostsTable;
use App\Models\Signpost;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SignpostResource extends Resource
{
    protected static ?string $model = Signpost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = NodeResource::class;

    public static function form(Schema $schema): Schema
    {
        return SignpostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SignpostsTable::configure($table);
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
            'create' => CreateSignpost::route('/create'),
            'edit' => EditSignpost::route('/{record}/edit'),
        ];
    }
}
