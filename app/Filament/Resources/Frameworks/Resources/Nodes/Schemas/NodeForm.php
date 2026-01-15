<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\Schemas;

use App\Enums\NodeColour;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
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
                ToggleButtons::make('colour')
                    ->inline()
                    ->options(NodeColour::options())
                    ->colors(NodeColour::colours())
                    ->required(),
                TextInput::make('name')
                    ->required(),
                RichEditor::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
