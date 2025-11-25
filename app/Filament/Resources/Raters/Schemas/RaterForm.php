<?php

namespace App\Filament\Resources\Raters\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RaterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->hint('Select an existing user if rater is internal.')
                    ->relationship('user', 'name')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set,) {
                        $set('user_id', $state);
                    }),
                TextInput::make('user_id')
                    ->disabled()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->required()
                    ->label('Email address')
                    ->email(),
            ]);
    }
}
