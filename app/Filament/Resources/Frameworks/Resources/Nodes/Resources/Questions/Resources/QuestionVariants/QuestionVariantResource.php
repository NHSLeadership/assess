<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Resources\QuestionVariants;

use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\QuestionResource;
use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Resources\QuestionVariants\Pages\CreateQuestionVariant;
use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Resources\QuestionVariants\Pages\EditQuestionVariant;
use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Resources\QuestionVariants\Schemas\QuestionVariantForm;
use App\Filament\Resources\Frameworks\Resources\Nodes\Resources\Questions\Resources\QuestionVariants\Tables\QuestionVariantsTable;
use App\Models\Question;
use App\Models\QuestionVariant;
use BackedEnum;
use Filament\Resources\ParentResourceRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QuestionVariantResource extends Resource
{
    protected static ?string $model = QuestionVariant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = QuestionResource::class;

    // Define the parent resource relationship
    public static function getParentResourceRegistration(): ?ParentResourceRegistration
    {
        return QuestionResource::asParent()
            ->relationship('variants')
            ->inverseRelationship('question');
    }

    public static function form(Schema $schema): Schema
    {
        return QuestionVariantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuestionVariantsTable::configure($table);
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
            'create' => CreateQuestionVariant::route('/create'),
            'edit' => EditQuestionVariant::route('/{record}/edit'),
        ];
    }

    /**
     * Resolve the parent Question id when this resource is opened via a RelationManager.
     */
    public static function resolveOwnerQuestionId(): ?int
    {
        // Filament 4 passes the parent id as ?ownerRecord=...
        $ownerId = request()->query('ownerRecord');
        if ($ownerId) {
            return (int) $ownerId;
        }

        // Edit context fallback: derive via the current variant
        $variantId = request()->route('record');
        if ($variantId) {
            $variant = QuestionVariant::query()->with('question:id,node_id')->find($variantId);
            return $variant?->question?->id;
        }

        return null;
    }

    /**
     * Resolve framework_id (needed to scope attribute/option selects).
     */
    public static function resolveFrameworkId(): ?int
    {
        if ($variantId = request()->route('record')) {
            $variant = QuestionVariant::query()
                ->with('question.node:id,framework_id')
                ->find($variantId);
            return $variant?->question?->node?->framework_id;
        }

        if ($questionId = self::resolveOwnerQuestionId()) {
            $question = Question::query()
                ->with('node:id,framework_id')
                ->find($questionId);
            return $question?->node?->framework_id;
        }

        return null;
    }
}
