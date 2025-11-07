<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Framework extends Model
{
    use HasFactory;

    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }
}
