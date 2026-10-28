<?php

namespace App\Models;

use App\Enums\Audience;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class ScaleOptionVariant extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'scale_option_id',
        'text',
        'audience',
        'priority',
    ];

    protected $casts = [
        'audience' => Audience::class,
    ];

    public function scaleOption(): BelongsTo
    {
        return $this->belongsTo(ScaleOption::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(ScaleOptionVariantMatch::class);
    }
}

