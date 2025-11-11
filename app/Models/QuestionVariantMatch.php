<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionVariantMatch extends Model
{
    protected $fillable = ['question_variant_id', 'framework_variant_attribute_id', 'framework_variant_option_id'];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(QuestionVariant::class, 'question_variant_id');
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
