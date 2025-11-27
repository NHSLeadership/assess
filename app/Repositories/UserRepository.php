<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Auth0\Laravel\UserRepositoryAbstract;
use Auth0\Laravel\UserRepositoryContract;
use Illuminate\Support\Facades\Log;
use Auth0\Laravel\Users\{StatefulUser, StatelessUser};
use Illuminate\Contracts\Auth\Authenticatable;

final class UserRepository extends UserRepositoryAbstract implements UserRepositoryContract
{
    public function fromAccessToken(array $user): ?Authenticatable
    {
        return new StatelessUser($user);
    }

    public function fromSession(array $user): ?Authenticatable
    {
        $authProfile = $this->extractUserAuthProfile($user);
        return new User($authProfile);
    }

    public function user()
    {
        return $this->user();
    }

    /**
     * That function simplifies the user data into accessible object (can be encrypted)
     *
     * @param $user
     *
     * @return mixed
     */
    protected function extractUserAuthProfile($user)
    {
        $prefix = config('auth0.customDataPrefix', 'https://nhsla.net');
        $user['user_id'] = intval($user[$prefix . '/username'] ?? $user['user_id']);

        $user['first_name'] = $user['given_name'] ?? $user['first_name'] ?? '';
        $user['last_name'] = $user['family_name'] ?? $user['last_name'] ?? '';
        $user['country'] = $user[$prefix . '/country'] ?? $user['country'] ?? '';
        $user['timezone'] = $user[$prefix . '/timezone'] ?? $user['timezone'] ?? '';
        $user['updated_at'] = $user[$prefix . '/updated_at'] ?? $user['updated_at'] ?? '';
        $user['user_metadata'] = $user[$prefix . '/user_metadata'] ?? $user['user_metadata'] ?? '';

        if (config('app.debug')) {
            Log::debug(serialize([$user]));
        }

        return $user;
    }
}
