<?php

namespace App\Filament\Resources\Raters\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RaterForm
{
    public static function components(): array
    {
        return [
            TextInput::make('user_id')
                ->label('User ID')
                ->nullable(),
            TextInput::make('name')
                ->nullable(),
            TextInput::make('email')
                ->nullable()
                ->email(),
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components(static::components());
    }
}
