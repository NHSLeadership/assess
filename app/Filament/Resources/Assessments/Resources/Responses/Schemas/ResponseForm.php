<?php

namespace App\Filament\Resources\Assessments\Resources\Responses\Schemas;

use App\Models\Question;
use App\Models\Rater;
use App\Models\ScaleOption;
use App\Models\User;
use App\Services\QuestionTextResolver;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ResponseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('rater_id')
                    ->relationship('rater', 'name')
                    ->createOptionForm([
                        Select::make('user_id')
                            ->hint('Select an existing user if rater is internal.')
                            ->relationship('user', 'name')
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $user = User::find($state);
                                    if ($user) {
                                        $set('name', $user->name);
                                        $set('email', $user->email);
                                    }
                                }
                            })
                            ->nullable(),
                        TextInput::make('name')->required(),
                        TextInput::make('email')->required(),
                    ])
                    ->required()
                    ->live(),
                Select::make('question_id')
                    ->label('Question')
                    ->disabled(fn (Get $get) => blank($get('rater_id')))
                    ->options(function (Get $get, $livewire) {
                        $assessment = $livewire->getParentRecord();
                        if (! $assessment) return [];
                        $rater = Rater::find($get('rater_id'));
                        return QuestionTextResolver::optionsFor($assessment, $rater);
                    })
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('scale_option_id', null)),
                Select::make('scale_option_id')
                    ->label('Answer')
                    ->disabled(fn (Get $get) => blank($get('question_id')))
                    ->options(function (Get $get): array {
                        $questionId = $get('question_id');
                        if (! $questionId) return [];
                        $scaleId = Question::query()
                            ->whereKey($questionId)
                            ->value('scale_id');
                        if (! $scaleId) return [];
                        return ScaleOption::query()
                            ->where('scale_id', $scaleId)
                            ->orderBy('order')
                            ->pluck('label', 'id')
                            ->toArray();
                    }),
                Textarea::make('free_text')
                    ->disabled(fn (Get $get) => blank($get('question_id')))
                    ->columnSpanFull(),
            ]);
    }
}
