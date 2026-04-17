<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class Signpost extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'node_id',
        'framework_variant_option_id',
        'min_value',
        'max_value',
        'guidance',
    ];

    protected $casts = [
        'min_value' => 'integer',
        'max_value' => 'integer',
    ];

    public function node(): BelongsTo
    {
        return $this->belongsTo(Node::class);
    }

    public function frameworkVariantOption(): BelongsTo
    {
        return $this->belongsTo(FrameworkVariantOption::class);
    }
}
