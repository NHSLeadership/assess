<?php

namespace App\Filament\Resources\Frameworks\Resources\FrameworkVariantAttributes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class FrameworkVariantAttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set,) {
                        $set('label', Str::title($state));
                    })
                    ->required(),
                TextInput::make('label')
                    ->required(),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
