<?php

namespace App\Filament\Resources\Assessments\RelationManagers;

use App\Enums\RaterType;
use App\Filament\Resources\Raters\Schemas\RaterForm;
use App\Models\Rater;
use App\Models\RaterGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                ->label('User ID')
                ->options(function () {
                    return Rater::query()
                        ->pluck('user_id', 'id');
                })
                ->preload()
                ->searchable()
                ->required()
                ->createOptionForm(RaterForm::components())
                ->createOptionUsing(function (array $data) {
                    return Rater::create($data)->id;
                }),

            Select::make('type')
                ->options(RaterType::class)
                ->live()
                ->required(),

            Select::make('rater_group_id')
                ->label('Group')
                ->options(function () {
                    return RaterGroup::query()
                        ->where('user_id', $this->getOwnerRecord()->user_id)
                        ->pluck('name', 'id');
                })
                ->preload()
                ->requiredIf('type', 'other')
                ->helperText('Required when type is Other')
                ->nullable()
                ->createOptionForm([
                    TextInput::make('name')
                        ->required()
                        ->label('Group name'),
                ])
                ->createOptionUsing(function (array $data) {
                    return RaterGroup::create([
                        'name' => $data['name'],
                        'user_id' => $this->getOwnerRecord()->user_id,
                    ])->id;
                }),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
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
