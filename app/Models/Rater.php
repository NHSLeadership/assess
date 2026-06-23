<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rater extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'name',
        'email',
    ];

    protected $casts = [
        'name' => 'encrypted',
        'email' => 'encrypted',
    ];

    protected static function booted(): void
    {
        static::saving(function (Rater $rater): void {
            $rater->email_hash = filled($rater->email)
                ? self::emailHash($rater->email)
                : null;
        });
    }

    public static function emailHash(string $email): string
    {
        return hash_hmac(
            'sha256',
            strtolower(trim($email)),
            config('app.key')
        );
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'subject_id',
            'user_id'
        );
    }

    public function assessments(): BelongsToMany
    {
        return $this->belongsToMany(Assessment::class, 'assessment_rater')
            ->using(AssessmentRater::class)
            ->withPivot(['type', 'rater_group_id'])
            ->withTimestamps();
    }

    public function assessmentRaters(): HasMany
    {
        return $this->hasMany(AssessmentRater::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }
}
