<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scale extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'min_value',
        'max_value',
        'neutral_value',
    ];

    protected $casts = [
        'min_value'     => 'integer',
        'max_value'     => 'integer',
        'neutral_value' => 'integer',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(ScaleOption::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Node::class);
    }
}
