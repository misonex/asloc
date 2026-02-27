<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SetAssociationContext;
//use App\Support\AssociationContext;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            SetAssociationContext::class,
        ]);

        /*
        if ($user && ! $user->hasRole('admin') && $user->association_id) {
            app(AssociationContext::class)
                ->set($user->association_id);
        }
        */
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
