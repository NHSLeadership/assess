<?php

namespace App\Services;

use App\Enums\Audience;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\Question;
use App\Models\QuestionVariant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionTextResolver
{
    /**
     * Returns [question_id => text] for an assessment+rater.
     * Basic rules: match rater_type (self/rater) and variant options (AND across selected attributes).
     * Falls back to Question->text if no variants qualify.
     */
    public static function optionsFor(
        Assessment $assessment,
        ?AssessmentRater $assessmentRater
    ): array
    {
        // 1) Determine audience (self vs external)
        $audience = self::variantAudience($assessmentRater);

        // 2) Selected framework variant option ids for this assessment
        $selectedOptionIds = $assessment->variantSelections()
            ->pluck('framework_variant_option_id')
            ->all();

        // 3) Load questions for the assessment's framework
        $questions = Question::query()
            ->whereHas('node', fn ($question) => $question->where('framework_id', $assessment->framework_id))
            ->withCount('variants')
            ->with([
                'variants' => fn ($query) => self::applyAudienceScope($query, $audience),
                'variants.matches',
            ])
            ->get();

        $map = [];

        foreach ($questions as $question) {
            $chosen = $question->variants->first(function ($variant) use ($selectedOptionIds) {

                if ($variant->matches->isEmpty()) {
                    return true;
                }

                $variantOptionIds = $variant->matches->pluck('framework_variant_option_id');

                return collect($selectedOptionIds)
                    ->every(fn ($id) => $variantOptionIds->contains($id));
            });

            // Exclusion questions with variants when no variants match user
            if ($question->variants_count > 0 && ! $chosen) {
                continue;
            }

            $map[$question->id] = $chosen?->text ?? $question->text;
        }

        return $map;
    }

    public static function textFor(
        Assessment $assessment,
        ?AssessmentRater $assessmentRater,
        int $questionId
    ): string {
        // 1) Determine audience (self vs rater)
        $audience = self::variantAudience($assessmentRater);

        // 2) Selected framework variant option ids for this assessment
        $selectedOptionIds = $assessment->variantSelections()
            ->pluck('framework_variant_option_id')
            ->all();

        // 3) Load just the one question
        $question = Question::query()
            ->where('id', $questionId)
            ->whereHas('node', fn ($q) => $q->where('framework_id', $assessment->framework_id))
            ->withCount('variants')
            ->with([
                'variants' => fn ($query) => self::applyAudienceScope($query, $audience),
                'variants.matches',
            ])
            ->first();

        if (! $question) {
            return ''; // Question not found in this framework
        }

        // 4) Pick variant for this question
        $chosen = self::chooseVariant($question->variants, $selectedOptionIds);

        // If question has variants but none match → suppress
        if ($question->variants_count > 0 && ! $chosen) {
            return '';
        }

        return $chosen?->text ?? $question->text;
    }

    private static function applyAudienceScope(Builder|HasMany $query, Audience $audience): void
    {
        $query->where(function ($query) use ($audience): void {
            $query->whereNull('audience')
                ->orWhere('audience', $audience->value);
        })
            ->orderByDesc('priority')
            ->orderBy('id');
    }

    /**
     * @param EloquentCollection<int, QuestionVariant> $variants
     */
    private static function chooseVariant(
        EloquentCollection $variants,
        array $selectedOptionIds
    ): ?QuestionVariant
    {
        return $variants->first(function ($variant) use ($selectedOptionIds) {
            if ($variant->matches->isEmpty()) {
                return true;
            }

            $variantOptionIds = $variant->matches->pluck('framework_variant_option_id');

            return collect($selectedOptionIds)
                ->every(fn ($id) => $variantOptionIds->contains($id));
        });
    }

    private static function variantAudience(?AssessmentRater $assessmentRater): Audience
    {
        return $assessmentRater === null
            ? Audience::Self
            : Audience::Rater;
    }
}
