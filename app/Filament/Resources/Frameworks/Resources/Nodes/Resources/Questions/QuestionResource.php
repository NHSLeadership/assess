<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions;

use App\Filament\Resources\Frameworks\Resources\Nodes\NodeResource;
use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Pages\CreateQuestion;
use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Pages\EditQuestion;
use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\RelationManagers\VariantsRelationManager;
use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Schemas\QuestionForm;
use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Tables\QuestionsTable;
use App\Models\Question;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = NodeResource::class;

    public static function form(Schema $schema): Schema
    {
        return QuestionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuestionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            VariantsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateQuestion::route('/create'),
            'edit' => EditQuestion::route('/{record}/edit'),
        ];
    }
}
