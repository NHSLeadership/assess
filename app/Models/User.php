<?php

declare(strict_types=1);

namespace App\Models;

use Auth0\SDK\Auth0;
use Auth0\SDK\Utility\HttpResponse;
use Auth0\SDK\Utility\Request\PaginatedRequest;
use Auth0\SDK\Utility\Request\RequestOptions;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use JsonException;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    FilamentUser
{
    use Authenticatable;
    use Authorizable;
    use HasFactory;
    use Notifiable;

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $guarded = [
    ];

    protected $fillable = [
        'user_id',
        'sub',
        'nickname',
        'preferred_username',
        'name',
        'email',
        'email_verified',
        'first_name',
        'last_name',
        'country',
        'timezone',
        'updated_at',

        'iss',
        'aud',
        'iat',
        'exp',
        'sub',
        'sid',
        'scope',
        'nonce',

        'user_metadata',
        'permissions',
        'roles',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'user_id', 'user_id')
            ->with('responses');
    }

    public function raters(): HasMany
    {
        return $this->hasMany(Rater::class);
    }


    /**
     * Returns Auth0 Permissions for the user
     * @throws JsonException
     */
    public function getAuth0Permissions(): array
    {
        if (!$this->sub) {
            return [];
        }

        $ttl = config('app.auth0_admin_permission_cache_ttl', 300) < 5
            ? 5
            : config('app.auth0_admin_permission_cache_ttl', 300);;

        return cache()->remember("auth0_permissions_{$this->sub}", $ttl, function () {
            $sdk = app('auth0');

            $all = [];
            $page = 0;

            do {
                $options = (new RequestOptions)->setPagination(
                    new PaginatedRequest($page, 100)
                );

                $response = $sdk->management()->users()->getPermissions($this->sub, $options);

                if (! HttpResponse::wasSuccessful($response)) {
                    break;
                }

                $chunk = HttpResponse::decodeContent($response);

                $all = array_merge($all, $chunk);

                $page++;
            } while (count($chunk) === 100);

            return $all;
        });
    }

}
