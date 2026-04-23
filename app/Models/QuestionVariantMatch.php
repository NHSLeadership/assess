<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class QuestionVariantMatch extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'question_variant_id',
        'framework_variant_attribute_id',
        'framework_variant_option_id',
    ];

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
