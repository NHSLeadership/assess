<?php

namespace App\Filament\Resources\RetentionEvents\Schemas;

use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RetentionEventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('owner'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('subject_type'),
                TextEntry::make('subject_id')
                    ->numeric(),
                TextEntry::make('action')
                    ->badge(),
                TextEntry::make('reason')
                    ->badge(),
                TextEntry::make('actor_type')
                    ->badge(),
                TextEntry::make('actor_id'),
                KeyValueEntry::make('metadata')
                    ->columnSpanFull(),
            ]);
    }
}
