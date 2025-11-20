<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'framework_id',
        'submitted_at',
        'target_completion_date',
        'notes',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'target_completion_date' => 'date',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function framework(): BelongsTo
    {
        return $this->belongsTo(Framework::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function raters(): BelongsToMany
    {
        return $this->belongsToMany(Rater::class, 'assessment_rater')
            ->using(AssessmentRater::class)
            ->withPivot(['role','is_self'])
            ->withTimestamps();
    }
    public function variantSelections(): HasMany
    {
        return $this->hasMany(AssessmentVariantSelection::class);
    }
}
