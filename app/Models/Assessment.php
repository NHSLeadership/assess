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
        'subject_user_id',
        'framework_id',
        'started_at',
        'submitted_at',
        'target_completion_date',
        'notes',
        'locked',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'submitted_at' => 'datetime',
        'target_completion_date' => 'date',
        'locked' => 'boolean',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(User::class, 'subject_user_id');
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
}
