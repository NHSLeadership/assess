<?php

namespace App\Filament\Resources\Assessments\Resources\Responses\Schemas;

use App\Models\Question;
use App\Models\Rater;
use App\Models\ScaleOption;
use App\Services\QuestionTextResolver;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ResponseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('rater_id')
                    ->label('Rater')
                    // Display all that are available from user_id, name and email
                    ->options(function () {
                        return Rater::query()
                            ->orderBy('user_id')
                            ->get()
                            ->mapWithKeys(function (Rater $rater) {
                                $parts = array_filter([
                                    $rater->user_id,
                                    $rater->name,
                                    $rater->email,
                                ]);
                                $label = implode(' | ', $parts);
                                return [$rater->id => $label];
                            })
                            ->toArray();
                    })
                    ->default(fn () => Rater::where('user_id', auth()->id())->value('id'))
                    ->createOptionForm([
                        TextInput::make('user_id')
                            ->hint('Select an existing user if rater is internal.')
                            ->default(auth()->id())
                            ->nullable(),
                        TextInput::make('name')->nullable(),
                        TextInput::make('email')->nullable(),
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
                    ->visible(fn (Get $get) => $get('question_id') && Question::query()->whereKey($get('question_id'))->value('response_type') === 'scale')
                    ->required(fn (Get $get) => $get('question_id') && Question::query()->whereKey($get('question_id'))->value('response_type') === 'scale')
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
                Textarea::make('textarea')
                    ->label('Answer')
                    ->visible(fn (Get $get) => $get('question_id') && Question::query()->whereKey($get('question_id'))->value('response_type') === 'textarea')
                    ->required(fn (Get $get) => $get('question_id') && Question::query()->whereKey($get('question_id'))->value('response_type') === 'textarea')
                    ->columnSpanFull(),
            ]);
    }
}
