<?php

namespace App\Services;

use App\Enums\Audience;
use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\ScaleOption;
use App\Models\ScaleOptionVariant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScaleOptionTextResolver
{
    /**
     * Returns [scale_option_id => text].
     *
     * @return array<int, string>
     */
    public static function optionsFor(
        Assessment $assessment,
        ?AssessmentRater $assessmentRater,
        int $scaleId
    ): array {
        $audience = self::variantAudience($assessmentRater);

        $selectedOptionIds = $assessment->variantSelections()
            ->pluck('framework_variant_option_id')
            ->all();

        $scaleOptions = ScaleOption::query()
            ->where('scale_id', $scaleId)
            ->with([
                'variants' => fn ($query) => self::applyAudienceScope($query, $audience),
                'variants.matches',
            ])
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $map = [];

        foreach ($scaleOptions as $scaleOption) {
            $chosen = self::chooseVariant($scaleOption->variants, $selectedOptionIds);
            $map[$scaleOption->id] = $chosen?->text ?? $scaleOption->description ?? '';
        }

        return $map;
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
     * @param EloquentCollection<int, ScaleOptionVariant> $variants
     */
    private static function chooseVariant(
        EloquentCollection $variants,
        array $selectedOptionIds
    ): ?ScaleOptionVariant {
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

