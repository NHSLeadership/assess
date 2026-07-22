<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaterGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function raters()
    {
        return $this->hasMany(Rater::class, 'group_id');
    }
}