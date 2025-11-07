<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormField extends Model
{
    use HasFactory;

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function formFieldOptions(): HasMany
    {
        return $this->hasMany(FormFieldOption::class);
    }

    public function userDataOptions(): HasMany
    {
        return $this->hasMany(UserDataOption::class);
    }
}
