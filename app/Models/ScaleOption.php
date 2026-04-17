<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class ScaleOption extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

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
