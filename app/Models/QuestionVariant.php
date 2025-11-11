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
}
