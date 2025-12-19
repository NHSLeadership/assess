<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FrameworkVariantAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'framework_id',
        'key',
        'label',
        'order'
    ];

    public function framework(): BelongsTo
    {
        return $this->belongsTo(Framework::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(FrameworkVariantOption::class)->orderBy('order');
    }
    
}
