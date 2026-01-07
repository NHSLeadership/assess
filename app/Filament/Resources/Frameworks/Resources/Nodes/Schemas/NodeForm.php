<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\Schemas;

use App\Enums\NodeVisibility;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

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
                Select::make('colour')
                    ->options([
                        'blue' => 'Blue',
                        'green' => 'Green',
                        'grey' => 'Grey',
                        'aqua-green' => 'Aqua green',
                        'orange' => 'Orange',
                        'purple' => 'Purple',
                        'pink' => 'Pink',
                        'red' => 'Red',
                        'white' => 'White',
                        'yellow' => 'Yellow',
                    ])
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('visibility')
                    ->hint('Select all who should see this node.')
                    ->options(NodeVisibility::options())
                    ->required()
                    ->default(NodeVisibility::Always->value)
                    ->default(NodeVisibility::Always->value),
                RichEditor::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
