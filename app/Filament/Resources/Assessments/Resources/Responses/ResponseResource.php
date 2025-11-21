<?php

namespace App\Filament\Resources\Assessments\Resources\Responses;

use App\Filament\Resources\Assessments\AssessmentResource;
use App\Filament\Resources\Assessments\Resources\Responses\Pages\CreateResponse;
use App\Filament\Resources\Assessments\Resources\Responses\Pages\EditResponse;
use App\Filament\Resources\Assessments\Resources\Responses\Schemas\ResponseForm;
use App\Filament\Resources\Assessments\Resources\Responses\Tables\ResponsesTable;
use App\Models\Response;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ResponseResource extends Resource
{
    protected static ?string $model = Response::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = AssessmentResource::class;

    public static function form(Schema $schema): Schema
    {
        return ResponseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResponsesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateResponse::route('/create'),
            'edit' => EditResponse::route('/{record}/edit'),
        ];
    }
}
