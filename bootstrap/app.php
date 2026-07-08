<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Daftarkan alias middleware admin Anda
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // 2. Pengaturan redirect untuk guest
        $middleware->redirectGuestsTo('admin/login');

        // 3. Matikan proteksi token CSRF khusus untuk rute webhook Midtrans
        $middleware->validateCsrfTokens(except: [
            'midtrans/callback',
            '/midtrans/callback'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();