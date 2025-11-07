<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stage extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function frameworks(): HasMany
    {
        return $this->hasMany(Framework::class);
    }
}
