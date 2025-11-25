<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Resources\QuestionVariants\Schemas;

use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Resources\QuestionVariants\QuestionVariantResource;
use App\Models\FrameworkVariantAttribute;
use App\Models\FrameworkVariantOption;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class QuestionVariantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Repeater::make('matches')
                ->label('Conditions (attribute → option)')
                ->relationship('matches')
                ->schema([
                    Select::make('framework_variant_attribute_id')
                        ->label('Attribute')
                        ->options(function () {
                            $frameworkId = QuestionVariantResource::resolveFrameworkId();
                            return FrameworkVariantAttribute::query()
                                ->when($frameworkId, fn ($q) => $q->where('framework_id', $frameworkId))
                                ->orderBy('order')
                                ->pluck('label', 'id');
                        })
                        ->required()
                        ->searchable()
                        ->native(false)
                        ->live(),
                    Select::make('framework_variant_option_id')
                        ->label('Option')
                        ->options(function (callable $get) {
                            $attrId = $get('framework_variant_attribute_id');
                            if (! $attrId) {
                                return [];
                            }
                            return FrameworkVariantOption::query()
                                ->where('framework_variant_attribute_id', $attrId)
                                ->orderBy('order')
                                ->pluck('label', 'id');
                        })
                        ->required()
                        ->searchable()
                        ->native(false),
                ])
//                ->minItems(1)
                ->defaultItems(1)
                ->collapsed(false)
                ->addActionLabel('Add condition')
                ->columnSpanFull(),
            Select::make('rater_type')
                ->label('Rater type')
                ->options([
                    'self'  => 'Self',
                    'rater' => 'Rater',
                ])
                ->nullable()
                ->native(false)
                ->helperText('Leave blank to apply to both self and rater.'),
            Textarea::make('text')
                ->label('Variant text')
                ->required()
                ->rows(4)
                ->columnSpanFull(),
            TextInput::make('priority')
                ->label('Priority')
                ->numeric()
                ->default(0)
                ->helperText('Tie-breaker when multiple variants match with the same specificity.'),
        ]);
    }
}
