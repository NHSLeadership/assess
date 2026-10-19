<?php

namespace App\Filament\Resources\Scales\RelationManagers;

use App\Enums\Audience;
use App\Models\FrameworkVariantAttribute;
use App\Models\FrameworkVariantOption;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'options';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->required(),
                TextInput::make('value')
                    ->required()
                    ->numeric(),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('description')
                    ->columnSpanFull(),
                Repeater::make('variants')
                    ->relationship('variants')
                    ->label('Scale option variants')
                    ->schema([
                        Repeater::make('matches')
                            ->label('Conditions (attribute → option)')
                            ->relationship('matches')
                            ->schema([
                                Select::make('framework_variant_attribute_id')
                                    ->label('Attribute')
                                    ->options(
                                        FrameworkVariantAttribute::query()
                                            ->orderBy('framework_id')
                                            ->orderBy('order')
                                            ->get()
                                            ->mapWithKeys(fn ($attribute) => [
                                                $attribute->id => "Framework {$attribute->framework_id}: {$attribute->label}",
                                            ])
                                            ->toArray()
                                    )
                                    ->required()
                                    ->searchable()
                                    ->native(false)
                                    ->live(),
                                Select::make('framework_variant_option_id')
                                    ->label('Option')
                                    ->options(function (callable $get): array {
                                        $attributeId = $get('framework_variant_attribute_id');

                                        if (! $attributeId) {
                                            return [];
                                        }

                                        return FrameworkVariantOption::query()
                                            ->where('framework_variant_attribute_id', $attributeId)
                                            ->orderBy('order')
                                            ->pluck('label', 'id')
                                            ->toArray();
                                    })
                                    ->required()
                                    ->searchable()
                                    ->native(false),
                            ])
                            ->defaultItems(0)
                            ->collapsed(false)
                            ->addActionLabel('Add condition')
                            ->columnSpanFull(),
                        Select::make('audience')
                            ->options(
                                collect(Audience::cases())
                                    ->mapWithKeys(fn ($case) => [
                                        $case->value => ucfirst($case->value),
                                    ])
                                    ->toArray()
                            )
                            ->nullable()
                            ->native(false)
                            ->helperText('Leave blank to apply to both self and raters.'),
                        Textarea::make('text')
                            ->label('Variant text')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('priority')
                            ->label('Priority')
                            ->numeric()
                            ->default(0)
                            ->helperText('Higher priority wins when multiple variants match.'),
                    ])
                    ->defaultItems(0)
                    ->collapsed()
                    ->addActionLabel('Add scale option variant')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Scale option')
            ->reorderable('order')
            ->columns([
                TextColumn::make('label')
                    ->searchable(),
                TextColumn::make('value')
                    ->numeric(),
                TextColumn::make('description')->limit(100),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
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
