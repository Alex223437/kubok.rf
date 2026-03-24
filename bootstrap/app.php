<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->authenticateSessions();
        $middleware->redirectGuestsTo(fn() => abort(404));
        //$middleware->redirectGuestsTo('/');
        $middleware->redirectUsersTo('/admin');

        //$middleware->validateCsrfTokens(except: ['/foo/*']);
        //$middleware->prepend(SomeMiddleware::class);
        //$middleware->append(AnotherMiddleware::class);
        //$middleware->remove(UnwantedMiddleware::class);
        //$middleware->replace(OldMiddleware::class, NewMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
