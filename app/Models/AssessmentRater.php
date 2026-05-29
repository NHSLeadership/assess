<?php

namespace App\Models;

use App\Enums\RaterType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AssessmentRater extends Pivot
{
    use HasFactory;

    protected $table = 'assessment_rater';

    public $timestamps = true;

    protected $fillable = [
        'assessment_id',
        'rater_id',
        'type',
        'rater_group_id',
    ];

    protected $casts = [
        'type' => RaterType::class,
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function rater(): BelongsTo
    {
        return $this->belongsTo(Rater::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(RaterGroup::class, 'rater_group_id');
    }

    public function isSelf(): bool
    {
        return $this->type === RaterType::Self;
    }
}
