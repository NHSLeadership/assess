<?php

namespace App\AuditResolvers;

use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\UserResolver as UserResolverContract;

class UserResolver implements UserResolverContract
{
    public static function resolve()
    {
        return Auth::user();
    }
}
