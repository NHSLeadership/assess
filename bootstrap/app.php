<?php

use App\Providers\AuthServiceProvider;
use Auth0\Laravel\Exceptions\Controllers\CallbackControllerException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (CallbackControllerException $e, $request) {

            $raw = $e->getMessage();
            $auth0Error = strtok($raw, ':');

            $status = match ($auth0Error) {
                'access_denied' => 403,
                'login_required',
                'consent_required',
                'interaction_required' => 401,
                'server_error' => 500,
                default => 400,
            };

            // Convert to HttpException so Laravel handles rendering later
            throw new HttpException(
                $status,
                $raw,
                $e
            );
        });
    })
    ->withProviders([
        AuthServiceProvider::class,
    ])->create();
