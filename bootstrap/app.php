<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Illuminate\Foundation\Configuration\Exceptions $exceptions) {

        // Handle HTTP exceptions (401, 403, 404, 419, 429, 503, etc.)
        $exceptions->render(function (Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e, $request) {

            $status = $e->getStatusCode();
            $message = $e->getMessage();

            // 1. Try exact error page: errors/{status}.blade.php
            if (view()->exists("errors.$status")) {
                return response()->view("errors.$status", [
                    'message' => $message,
                ], $status);
            }

            // 2. 4xx fallback
            if ($status >= 400 && $status < 500 && view()->exists("errors.4xx")) {
                return response()->view("errors.4xx", [
                    'message' => $message,
                ], $status);
            }

            // 3. 5xx fallback
            if ($status >= 500 && $status < 600 && view()->exists("errors.5xx")) {
                return response()->view("errors.5xx", [
                    'message' => $message,
                ], $status);
            }

            // 4. Let Laravel handle it if no matching view exists
            return null;
        });

        // Handle all non-HTTP exceptions
        $exceptions->render(function (Throwable $e, $request) {

            $message = app()->environment('production')
                ? 'Something went wrong'
                : $e->getMessage();

            return response()->view('errors.500', [
                'message' => $message,
                'exception' => $e,
            ], 500);
        });
    })

    ->withProviders([
            App\Providers\AuthServiceProvider::class,
        ])->create();
