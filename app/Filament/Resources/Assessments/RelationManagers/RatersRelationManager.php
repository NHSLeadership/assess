<?php

namespace App\Filament\Resources\Assessments\RelationManagers;

use App\Enums\RaterType;
use App\Models\Rater;
use App\Models\RaterGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RatersRelationManager extends RelationManager
{
    protected static string $relationship = 'raters';

    public function form(Schema $schema): Schema    {
        return $schema->components([
            Select::make('rater_id')
                ->label('Name')
                ->options(function () {
                    return Rater::query()
                        ->pluck('name', 'id');
                })
                ->preload()
                ->searchable()
                ->required(),

            Select::make('type')
                ->options(RaterType::class)
                ->preload()
                ->required(),

            Select::make('rater_group_id')
                ->label('Group')
                ->options(function () {
                    return RaterGroup::query()
                        ->where('user_id', $this->getOwnerRecord()->user_id)
                        ->pluck('name', 'id');
                })
                ->preload()
                ->required(fn ($get) => $get('type') === RaterType::Other->value)
                ->helperText('Required when type is Other')
                ->nullable(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->label('User ID')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('pivot.type')->label('Type')->badge()
                    ->formatStateUsing(fn ($state) => ucfirst($state->value)),
                TextColumn::make('pivot.group.name'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
