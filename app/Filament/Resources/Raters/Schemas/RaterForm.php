<?php

namespace App\Filament\Resources\Raters\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RaterForm
{
    public static function components(): array
    {
        return [
            TextInput::make('name'),
            TextInput::make('email')->email(),
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components(static::components());
    }
}
