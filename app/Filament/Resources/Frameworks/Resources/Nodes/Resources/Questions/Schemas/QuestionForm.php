<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Schemas;

use App\Models\Scale;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                RichEditor::make('text')
                    ->columnSpanFull()
                    ->required(),
                TextInput::make('hint')
                    ->nullable(),
                TextInput::make('placeholder')
                    ->nullable(),
                Select::make('response_type')
                    ->options([
                        'scale' => 'Scale',
                        'textarea' => 'Text',
                    ])
                    ->required()
                    ->reactive()
                    ->default('scale')
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state !== 'scale') {
                            $set('scale_id', null);
                        }
                    }),
                Select::make('scale_id')
                    ->label('Scale')
                    ->options(fn () => Scale::orderBy('name', 'asc')->pluck('name', 'id')->toArray())
                    ->preload()
                    ->nullable()
                    ->reactive()
                    ->required(fn ($get) => $get('response_type') === 'scale')
                    ->disabled(fn ($get) => $get('response_type') !== 'scale')
                    ->dehydrateStateUsing(fn ($state, $get) => $get('response_type') === 'scale' ? $state : null)
                    ->dehydrated(fn ($state, $get) => true),
                Toggle::make('required')
                    ->default(true)
                    ->required(),
                Toggle::make('active')
                    ->default(true)
                    ->required(),
            ]);
    }
}
