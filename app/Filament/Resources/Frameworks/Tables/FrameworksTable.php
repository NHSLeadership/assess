<?php

namespace App\Filament\Resources\Frameworks\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FrameworksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Delete framework')
                    ->modalDescription(fn ($record) =>
                        ($record->assessments()->exists()
                            ? trans('messages.framework_in_use_delete_modal', ['count' => $record->assessments()->count()])
                            : trans('messages.framework_delete_confirmation'))
                    ),
                RestoreAction::make(),
            ]);
    }
}
