<?php

namespace App\Filament\Resources\Frameworks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class FrameworkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->live(debounce: 400)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $set('slug', Str::slug($state));
                    })
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
