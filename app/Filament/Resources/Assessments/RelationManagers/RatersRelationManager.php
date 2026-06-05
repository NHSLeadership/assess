<?php

namespace App\Filament\Resources\Assessments\RelationManagers;

use App\Enums\RaterType;
use App\Filament\Resources\Raters\Schemas\RaterForm;
use App\Models\Rater;
use App\Models\RaterGroup;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;

class RatersRelationManager extends RelationManager
{
    protected static string $relationship = 'raters';

    protected function getFormComponents(): array
    {
        return [
            Select::make('recordId')
                ->label('Rater')
                ->options(function () {
                    $assessment = $this->getOwnerRecord();
                    $attachedRaterIds = $assessment->raters()->pluck('raters.id');
                    return Rater::query()
                        ->where('subject_id', $assessment->user_id)
                        ->whereNotIn('id', $attachedRaterIds)
                        ->whereNotNull('name')
                        ->orderBy('name')
                        ->pluck('name', 'id');
                })
                ->required()
                ->visible(fn ($context) => $context === 'attach')
                ->createOptionForm(RaterForm::components())
                ->createOptionUsing(function ($data) {
                    $subjectId = $this->getOwnerRecord()->user_id;

                    $existing = Rater::where('subject_id', $subjectId)
                        ->where('email', $data['email'])
                        ->first();

                    if ($existing) {
                        Notification::make()
                            ->title('Duplicate rater email')
                            ->body('A rater with that email address already exists.')
                            ->info()
                            ->send();
                        return $existing->id;
                    }

                    $rater = Rater::firstOrCreate(
                        [
                            'subject_id' => $subjectId,
                            'email' => $data['email'],
                        ],
                        [
                            'name' => $data['name'],
                        ]
                    );
                    return $rater->id;
                }),
            Select::make('type')
                ->options(collect(RaterType::cases())
                    ->mapWithKeys(fn ($case) => [$case->value => ucfirst($case->value)])
                    ->toArray()
                )
                ->live()
                ->required(),
            Select::make('rater_group_id')
                ->label('Group')
                ->options(fn () => RaterGroup::query()
                    ->where('subject_id', $this->getOwnerRecord()->user_id)
                    ->pluck('name', 'id')
                )
                ->requiredIf('type', 'other')
                ->nullable()
                ->createOptionForm([
                    TextInput::make('name')->required(),
                ])
                ->createOptionUsing(fn ($data) => RaterGroup::create([
                    'name' => $data['name'],
                    'subject_id' => $this->getOwnerRecord()->user_id,
                ])->id),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components($this->getFormComponents());
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
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
                AttachAction::make()
                    ->label('Attach rater')
                    ->schema(fn () => $this->getFormComponents())
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
