<?php

namespace App\Filament\Resources\Frameworks\Resources\FrameworkVariantAttributes;

use App\Filament\Resources\Frameworks\FrameworkResource;
use App\Filament\Resources\Frameworks\Resources\FrameworkVariantAttributes\Pages\CreateFrameworkVariantAttribute;
use App\Filament\Resources\Frameworks\Resources\FrameworkVariantAttributes\Pages\EditFrameworkVariantAttribute;
use App\Filament\Resources\Frameworks\Resources\FrameworkVariantAttributes\RelationManagers\OptionsRelationManager;
use App\Filament\Resources\Frameworks\Resources\FrameworkVariantAttributes\Schemas\FrameworkVariantAttributeForm;
use App\Filament\Resources\Frameworks\Resources\FrameworkVariantAttributes\Tables\FrameworkVariantAttributesTable;
use App\Models\FrameworkVariantAttribute;
use BackedEnum;
use Filament\Resources\ParentResourceRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FrameworkVariantAttributeResource extends Resource
{
    protected static ?string $model = FrameworkVariantAttribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = FrameworkResource::class;

    // Define the parent resource relationship
    public static function getParentResourceRegistration(): ?ParentResourceRegistration
    {
        return FrameworkResource::asParent()
            ->relationship('variantAttributes')
            ->inverseRelationship('framework');
    }

    public static function form(Schema $schema): Schema
    {
        return FrameworkVariantAttributeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FrameworkVariantAttributesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            OptionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateFrameworkVariantAttribute::route('/create'),
            'edit' => EditFrameworkVariantAttribute::route('/{record}/edit'),
        ];
    }
}
