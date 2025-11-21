<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('parent_id')
                    ->relationship('parent', 'name'),
                Select::make('node_type_id')
                    ->relationship('type', 'name')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required(),
                    ])
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
