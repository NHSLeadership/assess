<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Framework extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function nodes(): HasMany
    {
        return $this->hasMany(Node::class);
    }

    public function variantAttributes(): HasMany
    {
        return $this->hasMany(FrameworkVariantAttribute::class)->orderBy('order');
    }
}
