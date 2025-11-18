<?php

namespace App\Models;

use App\Enums\RaterType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionVariant extends Model
{
    protected $fillable = [
        'question_id',
        'text',
        'rater_type',
        'priority'
    ];

    protected $casts = [
        'rater_type' => RaterType::class,
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(QuestionVariantMatch::class);
    }

    public function conditionPairs(): array
    {
        $matches = $this->relationLoaded('matches')
            ? $this->matches
            : $this->matches()->with(['attribute', 'option'])->get();

        return $matches
            ->sortBy(fn ($m) => $m->attribute->order ?? 0)
            ->map(fn ($m) => "{$m->attribute->key}={$m->option->value}")
            ->values()
            ->all();
    }

    public function getConditionsSummaryAttribute(): string
    {
        $pairs = $this->conditionPairs();
        return empty($pairs) ? '—' : implode('; ', $pairs);
    }
}
