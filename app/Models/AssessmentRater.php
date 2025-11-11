<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AssessmentRater extends Pivot
{
    protected $table = 'assessment_rater';

    public $timestamps = true;

    protected $fillable = [
        'assessment_id',
        'rater_id',
        'role',
        'is_self',
    ];

    protected $casts = [
        'is_self' => 'boolean',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function rater(): BelongsTo
    {
        return $this->belongsTo(Rater::class);
    }
}
