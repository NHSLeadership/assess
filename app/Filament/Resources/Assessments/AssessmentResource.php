<?php

namespace App\Filament\Resources\Assessments;

use App\Filament\Resources\Assessments\Pages\CreateAssessment;
use App\Filament\Resources\Assessments\Pages\EditAssessment;
use App\Filament\Resources\Assessments\Pages\ListAssessments;
use App\Filament\Resources\Assessments\RelationManagers\ResponsesRelationManager;
use App\Filament\Resources\Assessments\Schemas\AssessmentForm;
use App\Filament\Resources\Assessments\Tables\AssessmentsTable;
use App\Models\Assessment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBarSquare;

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return AssessmentForm::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return AssessmentsTable::configure($table);
    }

    #[\Override]
    public static function getRelations(): array
    {
        return [
            ResponsesRelationManager::class,
        ];
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListAssessments::route('/'),
            'create' => CreateAssessment::route('/create'),
            'edit' => EditAssessment::route('/{record}/edit'),
        ];
    }
}
