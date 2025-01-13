<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias middleware
        $middleware->alias([
            // 'admin' => \App\Http\Middleware\AdminMiddleware::class,
            // 'customer' => \App\Http\Middleware\CustomerMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'sweetalert' => \RealRashid\SweetAlert\ToSweetAlert::class, // Tambahin alias middleware SweetAlert
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
