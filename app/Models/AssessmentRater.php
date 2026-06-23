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
        'invited_at',
        'started_at',
        'submitted_at',
    ];

    protected $casts = [
        'type' => RaterType::class,
        'invited_at' => 'datetime',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
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

    public function getStatus(): string
    {
        if ($this->submitted_at) {
            return 'Completed';
        }
        if ($this->started_at) {
            return 'Started';
        }
        if ($this->invited_at) {
            return 'Invited';
        }

        return 'Pending';
    }

    public function getStatusColour(): string
    {
        return match ($this->getStatus()) {
            'Pending' => 'gray',
            'Invited' => 'info',
            'Started' => 'warning',
            'Completed' => 'success',
            default => 'gray',
        };
    }
}
