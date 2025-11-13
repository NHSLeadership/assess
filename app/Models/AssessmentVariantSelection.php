<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AssessmentVariantSelection extends Pivot
{
    protected $table = 'assessment_variant_selections';

    protected $fillable = [
        'assessment_id',
        'framework_variant_attribute_id',
        'framework_variant_option_id',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(FrameworkVariantAttribute::class, 'framework_variant_attribute_id');
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(FrameworkVariantOption::class, 'framework_variant_option_id');
    }
}
