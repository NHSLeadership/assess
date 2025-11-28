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
                TextInput::make('user_id')
                    ->label('User ID')
                    ->nullable(),
                TextInput::make('name')
                    ->nullable(),
                TextInput::make('email')
                    ->nullable()
                    ->email(),
            ]);
    }
}
