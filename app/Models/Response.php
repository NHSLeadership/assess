<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'rater_id',
        'question_id',
        'scale_option_id',
        'textarea',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function rater(): BelongsTo
    {
        return $this->belongsTo(Rater::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function scaleOption(): BelongsTo
    {
        return $this->belongsTo(ScaleOption::class);
    }
}
