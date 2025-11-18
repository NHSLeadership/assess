<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDataOption extends Model
{
    protected $fillable = [
        'assessment_id',
        'form_field_id',
        'form_field_option_id',
        'user_id',
        'updated_at',
        'scale_option_id',
        'question_id'
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(ScaleOption::class);
    }
}
