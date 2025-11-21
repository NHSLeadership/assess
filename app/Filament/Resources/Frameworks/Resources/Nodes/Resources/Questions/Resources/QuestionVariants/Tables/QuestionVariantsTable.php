<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Resources\QuestionVariants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QuestionVariantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('priority')
            ->defaultSort('priority')
            ->modifyQueryUsing(fn (Builder $query) =>
            $query->with(['matches.attribute', 'matches.option'])
            )
            ->columns([
                TextColumn::make('conditions_summary')
                    ->label('Conditions')
                    ->badge(),
                TextColumn::make('rater_type'),
                TextColumn::make('text')
                    ->label('Variant text')
                    ->limit(90)
                    ->wrap(),
                TextColumn::make('updated_at')
                    ->since()
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
