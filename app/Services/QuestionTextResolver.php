<?php

namespace App\Services;

use App\Enums\RaterType;
use App\Models\Assessment;
use App\Models\Question;
use App\Models\Rater;

class QuestionTextResolver
{
    /**
     * Returns [question_id => text] for an assessment+rater.
     * Basic rules: match rater_type (self/rater) and variant options (AND across selected attributes).
     * Falls back to Question->text if no variants qualify.
     */
    public static function optionsFor(Assessment $assessment, ?Rater $rater = null): array
    {
        // 1) Derive rater type
        $subjectUserId = $assessment->getAttribute('user_id');
        $raterUserId = $rater?->getAttribute('user_id');
        $raterType = ($raterUserId && $subjectUserId && $raterUserId === $subjectUserId) ? RaterType::Self : RaterType::Rater;

        // 2) Selected framework variant option ids for this assessment
        $selectedOptionIds = $assessment->variantSelections()
            ->pluck('framework_variant_option_id')
            ->all();

        // 3) Load questions for the assessment's framework
        $questions = Question::query()
            ->whereHas('node', fn($question) => $question->where('framework_id', $assessment->framework_id))
            ->with(['variants' => function ($question) use ($raterType) {
                $question->whereNull('rater_type')->orWhere('rater_type', $raterType)
                    ->orderByDesc('priority')->orderBy('id');
            }, 'variants.matches'])
            ->orderBy('order')->orderBy('id')
            ->get();

        // 4) Pick variant per question (AND across all selected attributes).
        $map = [];
        foreach ($questions as $question) {
            $chosen = $question->variants->first(function ($variant) use ($selectedOptionIds) {
                // If no matches on the variant, treat as generic => allowed
                if ($variant->matches->isEmpty()) return true;

                // AND: every selected attribute must be present.
                // Here we enforce presence by requiring that each selected option id appears in the variant's matches.
                $variantOptionIds = $variant->matches->pluck('framework_variant_option_id');
                // Basic AND across assessment selections (tighten later if you add multi-attribute)
                return collect($selectedOptionIds)->every(fn($id) => $variantOptionIds->contains($id));
            });

            $map[$question->id] = $chosen?->text ?? $question->text;
        }
        return $map;
    }
}
