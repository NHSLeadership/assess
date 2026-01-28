<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FrameworkVariantOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'framework_variant_attribute_id',
        'value',
        'label',
        'order'
    ];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(FrameworkVariantAttribute::class, 'framework_variant_attribute_id');
    }

    public function signposts(): HasMany
    {
        return $this->hasMany(Signpost::class);
    }

    public function framework(): BelongsTo
    {
        return $this->attribute->belongsTo(Framework::class);
    }
}
