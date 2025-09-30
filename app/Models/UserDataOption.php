<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDataOption extends Model
{
    protected $fillable = [
        'form_id',
        'form_field_id',
        'form_field_option_id',
        'user_id',
        'updated_at'
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function formField(): BelongsTo
    {
        return $this->belongsTo(FormField::class);
    }

    public function formFieldOption(): BelongsTo
    {
        return $this->belongsTo(FormFieldOption::class);
    }
}
