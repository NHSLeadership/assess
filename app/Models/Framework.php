<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Framework extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function nodes(): HasMany
    {
        return $this->hasMany(Node::class);
    }

    public function questions(): HasManyThrough
    {
        return $this->hasManyThrough(Question::class, Node::class);
    }

    public function variantAttributes(): HasMany
    {
        return $this->hasMany(FrameworkVariantAttribute::class)->orderBy('order');
    }

    public function stages(): HasMany
    {
        return $this->hasMany(FrameworkVariantAttribute::class)->where('key', 'stage');
    }
}
