<?php

namespace App\Filament\Resources\Assessments\Resources\Responses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ResponsesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rater')
                    ->formatStateUsing(function ($state, $record) {
                        $rater = $record->rater ?? null;
                        $name  = $rater->name ?? null;
                        $uid   = $rater->user_id ?? null;

                        if ($uid && $name) {
                            return "{$uid} ({$name})";
                        }

                        if ($uid) {
                            return (string) $uid;
                        }

                        if ($name) {
                            return (string) $name;
                        }

                        return '';
                    }),
                TextColumn::make('question.title')
                    ->limit(80),
                TextColumn::make('scaleOption.label')
                    ->label('Answer'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
