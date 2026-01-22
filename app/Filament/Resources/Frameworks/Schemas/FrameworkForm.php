<?php

namespace App\Filament\Resources\Frameworks\Schemas;

use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class FrameworkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $set('slug', Str::slug($state));
                    })
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                RichEditor::make('description')
                    ->required()
                    ->columnSpanFull(),
                RichEditor::make('instructions')
                    ->columnSpanFull(),
                RichEditor::make('report_intro')
                    ->label('Report Introduction')
                    ->columnSpanFull(),
                CodeEditor::make('report_html')
                    ->label('Report HTML')
                    ->language(Language::Html)
                    ->columnSpanFull(),
            ]);
    }
}
