<?php

namespace App\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * @param $request
     * @param  array  $guards
     * @return void|null
     * @throws AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {

        if (Auth::check()) {
            return;
        }

        // TODO: Guards logic once permission is implemented

        throw new AuthenticationException(
            'Unauthenticated.',
            $guards,
            route('home')
        );
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * @param  Request  $request
     * @return string|void|null
     * @throws AuthenticationException
     */
    protected function redirectTo(Request $request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }

        if ($this->auth->guard()->guest()) {
            throw new AuthenticationException('Requires authentication', 401);
        }
    }
}
