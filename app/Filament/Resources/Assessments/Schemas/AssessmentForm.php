<?php

namespace App\Filament\Resources\Assessments\Schemas;

use App\Models\FrameworkVariantAttribute;
use App\Models\FrameworkVariantOption;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class AssessmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('subject', 'name')
                    ->required(),


                Select::make('framework_id')
                    ->relationship('framework', 'name')
                    ->required()
                    ->live()
                    // When framework changes, (re)seed the repeater with one item per attribute:
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
                            'framework_variant_option_id'    => null,
                        ])->toArray());
                    }),

                Repeater::make('variantSelections')
                    ->label(null) // or keep 'Framework variants' if you want a section title
                    ->relationship('variantSelections')
                    ->visible(fn (Get $get) => filled($get('framework_id')))
                    ->default(function (Get $get) {
                        $frameworkId = $get('framework_id');
                        if (! $frameworkId) return [];

                        return \App\Models\FrameworkVariantAttribute::query()
                            ->where('framework_id', $frameworkId)
                            ->orderBy('order')
                            ->get()
                            ->map(fn ($attr) => [
                                'framework_variant_attribute_id' => $attr->id,
                                'framework_variant_option_id'    => null,
                            ])->toArray();
                    })

                    // Lock rows
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)

                    // ✅ Remove expander look + header text
                    ->collapsible(false)
                    ->collapsed(false)
                    ->itemLabel(fn () => '') // <- empty label prevents showing the header text

                    ->schema([
                        Hidden::make('framework_variant_attribute_id'),

                        // ✅ Put the attribute name on the Radio label
                        Radio::make('framework_variant_option_id')
                            ->label(function (Get $get) {
                                $attrId = $get('framework_variant_attribute_id');
                                return optional(
                                    \App\Models\FrameworkVariantAttribute::find($attrId)
                                )->label ?? 'Attribute';
                            })
                            ->required()
                            ->inline() // horizontal options
                            ->options(function (Get $get) {
                                $attrId = $get('framework_variant_attribute_id');
                                return \App\Models\FrameworkVariantOption::query()
                                    ->where('framework_variant_attribute_id', $attrId)
                                    ->orderBy('order')
                                    ->pluck('label', 'id')
                                    ->toArray();
                            }),
                    ])
                    ->columns(1),

                DateTimePicker::make('target_completion_date')->default(now()->addMonth()),
                DateTimePicker::make('submitted_at'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
