<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('text')
                    ->columnSpanFull()
                    ->required(),
                TextInput::make('hint')
                    ->nullable(),
                TextInput::make('placeholder')
                    ->nullable(),
                Select::make('response_type')
                    ->options([
//                            'single_choice' => 'Single choice',
//                            'multi_choice' => 'Multiple choice',
                            'scale' => 'Scale',
//                            'boolean' => 'Boolean',
                            'free_text' => 'Free text',
                    ])
                    ->required()
                    ->default('scale'),
                Select::make('scale_id')
                    ->relationship(
                        name: 'scale',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->orderBy('name', 'asc')
                    )
                    ->preload(),
                Toggle::make('required')
                    ->default(true)
                    ->required(),
                Toggle::make('active')
                    ->default(true)
                    ->required(),
            ]);
    }
}
