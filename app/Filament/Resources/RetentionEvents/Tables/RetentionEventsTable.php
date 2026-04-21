<?php

namespace App\Filament\Resources\RetentionEvents\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RetentionEventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('owner')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject_type')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('action')
                    ->badge()
                    ->searchable(),
                TextColumn::make('reason')
                    ->badge()
                    ->searchable(),
                TextColumn::make('actor_type')
                    ->badge()
                    ->searchable(),
                TextColumn::make('actor_id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('id', direction: 'desc')
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
