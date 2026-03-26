<?php

namespace App\Filament\Resources\Frameworks\Schemas;

use App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks\BarChartBlock;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Laravel\Pennant\Feature;

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
                    ->toolbarButtons([
                        ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'small', 'highlight', 'link'],
                        ['h2', 'h3', 'horizontalRule', 'alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                        ['bulletList', 'orderedList'],
                        array_filter([
                            'table', 'details', 'attachFiles',
                            Feature::active('rich-editor-custom-blocks') ? 'customBlocks' : null,
                            'mergeTags'
                        ]),
                        ['undo', 'redo', 'clearFormatting'],
                    ])
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('media')
                    ->fileAttachmentsVisibility('public')
                    ->mergeTags([
                        'userID',
                        'userName',
                        'userEmail',
                        'today',
                        'framework',
                    ])
                    ->required()
                    ->columnSpanFull(),
                Section::make('Form settings')
                    ->schema([
                        RichEditor::make('instructions')
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'small', 'highlight', 'link'],
                                ['h2', 'h3', 'horizontalRule', 'alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                                ['bulletList', 'orderedList'],
                                array_filter([
                                    'table', 'details', 'attachFiles',
                                    Feature::active('rich-editor-custom-blocks') ? 'customBlocks' : null,
                                    'mergeTags'
                                ]),
                                ['undo', 'redo', 'clearFormatting'],
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('media')
                            ->fileAttachmentsVisibility('public')
                            ->mergeTags([
                                'userID',
                                'userName',
                                'userEmail',
                                'today',
                                'framework',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->collapsed()
                    ->columnSpanFull(),
                Section::make('Report settings')
                    ->schema([
                        RichEditor::make('report_intro')
                            ->label('Report Introduction')
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'small', 'highlight', 'link'],
                                ['h2', 'h3', 'horizontalRule', 'alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                                ['bulletList', 'orderedList'],
                                array_filter([
                                    'table', 'details', 'attachFiles',
                                    Feature::active('rich-editor-custom-blocks') ? 'customBlocks' : null,
                                    'mergeTags'
                                ]),
                                ['undo', 'redo', 'clearFormatting'],
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('media')
                            ->fileAttachmentsVisibility('public')
                            ->mergeTags([
                                'userID',
                                'userName',
                                'userEmail',
                                'today',
                                'framework',
                            ])
                            ->customBlocks([
                                BarChartBlock::class,
                            ])
                            ->columnSpanFull(),
                        CodeEditor::make('report_html')
                            ->label('Report HTML')
                            ->language(Language::Html)
                            ->columnSpanFull(),
                        RichEditor::make('report_ending')
                            ->toolbarButtons([
                                ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'small', 'highlight', 'link'],
                                ['h2', 'h3', 'horizontalRule', 'alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                                ['bulletList', 'orderedList'],
                                array_filter([
                                    'table', 'details', 'attachFiles',
                                    Feature::active('rich-editor-custom-blocks') ? 'customBlocks' : null,
                                    'mergeTags'
                                ]),
                                ['undo', 'redo', 'clearFormatting'],
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('media')
                            ->fileAttachmentsVisibility('public')
                            ->mergeTags([
                                'userID',
                                'userName',
                                'userEmail',
                                'today',
                                'framework',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->collapsed()
                    ->columnSpanFull(),
            ]);
    }
}
