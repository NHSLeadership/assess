<?php

namespace App\Models;

use App\Services\Auth0UserService;
use App\Settings\Retention;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property-read Framework|null $framework
 */

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'framework_id',
        'submitted_at',
        'target_completion_date',
        'notes',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'target_completion_date' => 'date',
    ];


    public function effectiveLastUpdatedAt(): ?Carbon
    {
        $dates = collect([
            $this->updated_at,
            $this->submitted_at,
            $this->responses?->max('updated_at'),
        ])->filter();

        return $dates->max();
    }

    public function expiresAt(): Carbon
    {
        $settings = app(Retention::class);

        $lastUpdatedAt = $this->effectiveLastUpdatedAt() ?? $this->created_at;
        if (! $lastUpdatedAt instanceof Carbon) {
            throw new \LogicException('Cannot determine expiry date for an assessment without any timestamps.');
        }

        return $lastUpdatedAt
            ->copy()
            ->addYears($settings->retention_years);
    }

    public function isWithinExpiryWarningWindow(): bool
    {
        $settings = app(Retention::class);

        return now()->greaterThanOrEqualTo(
            $this->expiresAt()->subDays($settings->expiry_warning_days)
        );
    }

    public function isExpired(): bool
    {
        return now()->greaterThanOrEqualTo($this->expiresAt());
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function framework(): BelongsTo
    {
        return $this->belongsTo(Framework::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function raters(): BelongsToMany
    {
        return $this->belongsToMany(Rater::class, 'assessment_rater')
            ->using(AssessmentRater::class)
            ->withPivot(['role', 'is_self'])
            ->withTimestamps();
    }

    public function variantSelections(): HasMany
    {
        return $this->hasMany(AssessmentVariantSelection::class);
    }

    public function notificationRecipient(): ?array
    {
        $userName = (string) $this->user_id;

        $authUser = app(Auth0UserService::class)
            ->getUserByUsername($userName);

        if (! $authUser || empty($authUser['email'])) {
            return null;
        }

        return [
            'email' => $authUser['email'],
            'first_name' => $authUser['given_name']
                ?? $authUser['name']
                    ?? null,
        ];
    }
}
