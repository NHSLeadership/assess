<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Scale extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'description',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(ScaleOption::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
