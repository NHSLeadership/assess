<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScaleOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'scale_id',
        'label',
        'value',
        'order',
        'description',
    ];

    protected $casts = [
        'value' => 'integer',
        'order' => 'integer',
    ];

    public function scale(): BelongsTo
    {
        return $this->belongsTo(Scale::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }
}
