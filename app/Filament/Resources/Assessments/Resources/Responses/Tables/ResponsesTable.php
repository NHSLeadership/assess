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
                        $rater = $record->rater;

                        if (! $rater) {
                            return '';
                        }

                        $hasPivot = $record->assessment
                            ->raters()
                            ->where('raters.id', $record->rater_id)
                            ->exists();

                        if (! $hasPivot) {
                            return 'Self';
                        }

                        return $rater->name ?? 'Unknown rater';
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
