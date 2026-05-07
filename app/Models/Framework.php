<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Framework extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'instructions',
        'report_intro',
        'report_html',
        'report_ending',
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

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    public function hasAssessments(): bool
    {
        return $this->assessments()->exists();
    }
}
