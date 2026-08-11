<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        // PENGATURAN REDIRECT OTOMATIS GUEST CUSTOMER VS ADMIN
        $middleware->redirectTo(
            guests: function (Request $request) {
                // Jika route yang diakses berawal dari customer, orders, atau checkout
                if ($request->is('customer*') || $request->is('checkout*') || $request->is('*orders*')) {
                    return route('customer.login');
                }

                // Default redirect untuk halaman admin yang butuh login
                return route('login');
            }
        );

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();