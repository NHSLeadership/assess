<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rater extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assessments(): BelongsToMany
    {
        return $this->belongsToMany(Assessment::class, 'assessment_rater')
            ->using(AssessmentRater::class)
            ->withPivot(['role', 'is_self'])
            ->withTimestamps();
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }
}
