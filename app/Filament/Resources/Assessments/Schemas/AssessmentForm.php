<?php

namespace App\Filament\Resources\Assessments\Schemas;

use App\Models\FrameworkVariantAttribute;
use App\Models\FrameworkVariantOption;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class AssessmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Assessment details')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('user_id')
                            ->columnStart(1)
                            ->label('Subject')
                            ->disabled()
                            ->dehydrated()
                            ->default(auth()->id())
                            ->required(),

                        TextEntry::make('type')
                            ->columnStart(2)
                            ->badge()
                            ->color(fn ($state) => $state === '360' ? 'warning' : 'success'),

                        Select::make('framework_id')
                            ->columnStart(1)
                            ->relationship('framework', 'name')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?int $state) {
                                if (! $state) {
                                    $set('variantSelections', []);

                                    return;
                                }

                                $attributes = FrameworkVariantAttribute::query()
                                    ->where('framework_id', $state)
                                    ->orderBy('order')
                                    ->get();

                                $set('variantSelections', $attributes->map(fn ($attr) => [
                                    'framework_variant_attribute_id' => $attr->id,
                                    'framework_variant_option_id' => null,
                                ])->toArray());
                            }),

                        DateTimePicker::make('target_completion_date')
                            ->default(now()->addMonth())
                            ->columnStart(2),

                        Repeater::make('variantSelections')
                            ->label(null)
                            ->relationship('variantSelections')
                            ->visible(fn (Get $get) => filled($get('framework_id')))
                            ->default(function (Get $get) {
                                $frameworkId = $get('framework_id');

                                if (! $frameworkId) {
                                    return [];
                                }

                                return FrameworkVariantAttribute::query()
                                    ->where('framework_id', $frameworkId)
                                    ->orderBy('order')
                                    ->get()
                                    ->map(fn ($attr) => [
                                        'framework_variant_attribute_id' => $attr->id,
                                        'framework_variant_option_id' => null,
                                    ])->toArray();
                            })

                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)

                            ->collapsible(false)
                            ->collapsed(false)
                            ->itemLabel(fn () => '')

                            ->schema([
                                Hidden::make('framework_variant_attribute_id'),

                                Radio::make('framework_variant_option_id')
                                    ->label(function (Get $get) {
                                        $attrId = $get('framework_variant_attribute_id');

                                        return optional(
                                            FrameworkVariantAttribute::find($attrId)
                                        )->label ?? 'Attribute';
                                    })
                                    ->required()
                                    ->inline()
                                    ->options(function (Get $get) {
                                        $attrId = $get('framework_variant_attribute_id');

                                        return FrameworkVariantOption::query()
                                            ->where('framework_variant_attribute_id', $attrId)
                                            ->orderBy('order')
                                            ->pluck('label', 'id')
                                            ->toArray();
                                    }),
                            ]),
                    ]),

                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
