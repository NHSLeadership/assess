<?php

use App\Providers\AuthServiceProvider;
use Auth0\Laravel\Exceptions\Controllers\CallbackControllerException;
use Illuminate\Auth\AuthenticationException;
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

            [$auth0Error, $description] = array_pad(
                explode(':', $e->getMessage(), 2),
                2,
                ''
            );

            $auth0Error = trim($auth0Error);
            $description = trim($description);

            [$status, $fallbackMessage] = match ($auth0Error) {
                'access_denied' => [
                    403,
                    'This action is unauthorised.',
                ],

                'login_required',
                'consent_required',
                'interaction_required' => [
                    401,
                    'You need to sign in to continue.',
                ],

                'server_error' => [
                    500,
                    'We are experiencing technical difficulties. Please try again later.',
                ],

                default => [
                    400,
                    'We could not complete your request.',
                ],
            };

            throw new HttpException(
                $status,
                $description !== ''
                    ? $description
                    : $fallbackMessage,
                $e
            );
        });

        $exceptions->renderable(function (\Auth0\SDK\Exception\StateException $e) {
            session()->flush();
            return redirect()->route('home');
        });


        $exceptions->render(function (
            AuthenticationException $e,
                                    $request
        ) {
            logger()->info('AuthenticationException', [
                'livewire' => $request->hasHeader('X-Livewire'),
                'url' => $request->path(),
            ]);

            if ($request->header('X-Livewire')) {
                abort(419);
            }

            return null;
        });


    })
    ->withProviders([
        AuthServiceProvider::class,
    ])->create();
