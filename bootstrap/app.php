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

        $exceptions->renderable(function (CallbackControllerException $e) {

            $raw = $e->getMessage();
            $auth0Error = strtok($raw, ':');
            [$status, $message] = match ($auth0Error) {
                'access_denied' => [403, 'This action is unauthorised.'],
                'login_required',
                'consent_required',
                'interaction_required' => [401, 'You need to sign in to continue.'],
                'server_error' => [500, 'We are experiencing technical difficulties. Please try again later.'],
                default => [400, 'We could not complete your request.'],
            };

            throw new HttpException($status, $message, $e);
        });

        $exceptions->renderable(function (\Auth0\SDK\Exception\StateException $e) {
            session()->flush();
            return redirect()->route('home');
        });

    })
    ->withProviders([
        AuthServiceProvider::class,
    ])->create();
