<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NodeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function nodes(): HasMany
    {
        return $this->hasMany(Node::class);
    }
}
