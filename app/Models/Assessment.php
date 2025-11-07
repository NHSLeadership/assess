<?php

namespace App\Models;

use App\Traits\Reviewable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    /** @use HasFactory<\Database\Factories\AssessmentFactory> */
    use HasFactory;
    use Reviewable;

    protected $fillable = [
        'framework_id',
        'user_id',
    ];

    public function framework(): BelongsTo
    {
        return $this->belongsTo(Framework::class);
    }

    public function userDataOptions(): HasMany
    {
        return $this->hasMany(UserDataOption::class);
    }

    public function userDataTexts(): HasMany
    {
        return $this->hasMany(UserDataText::class);
    }
}
