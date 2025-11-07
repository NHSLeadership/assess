<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory;
    protected string $parentColumn = 'parent_id';

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(static::class, $this->parentColumn);
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    public function root()
    {
        return $this->parent
            ? $this->parent->root()
            : $this;
    }

    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class);
    }

    public function framework(): BelongsTo
    {
        return $this->belongsTo(Framework::class);
    }
}
