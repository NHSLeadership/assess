<?php

namespace App\Filament\Resources\Assessments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AssessmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount('raters'))
            ->columns([
                TextColumn::make('user_id')->label('Subject'),
                TextColumn::make('framework.name'),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn ($state) => $state === '360' ? 'warning' : 'success'),
                TextColumn::make('feedback_status')
                    ->badge()
                    ->color(fn ($state) => $state === 'Completed' ? 'success' : 'warning')
                    ->label('Feedback'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('submitted_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_360')
                    ->label('Type')
                    ->placeholder('All')
                    ->trueLabel('360')
                    ->falseLabel('Self')
                    ->queries(
                        true: fn ($query) => $query->has('raters'),
                        false: fn ($query) => $query->doesntHave('raters'),
                    ),
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
