<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Signposts\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SignpostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('min_value')
                    ->required()
                    ->numeric(),
                TextInput::make('max_value')
                    ->required()
                    ->numeric(),
                Select::make('framework_variant_option_id')
                    ->relationship('frameworkVariantOption', 'label'),
                RichEditor::make('guidance')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
