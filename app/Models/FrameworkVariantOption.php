<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrameworkVariantOption extends Model
{
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
}
