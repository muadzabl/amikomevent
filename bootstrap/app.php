<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan alias middleware untuk semua role
        $middleware->alias([
            'admin'     => \App\Http\Middleware\AdminMiddleware::class,
            'organizer' => \App\Http\Middleware\OrganizerMiddleware::class,
            'role'      => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // Redirect guest ke halaman login customer (bukan admin)
        $middleware->redirectGuestsTo('/login');

        // Matikan CSRF khusus untuk webhook Midtrans
        $middleware->validateCsrfTokens(except: [
            'midtrans/callback',
            '/midtrans/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// Gunakan /tmp/storage jika berjalan di Vercel (karena filesystem Vercel read-only)
if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL']) || env('VERCEL') == '1') {
    $app->useStoragePath('/tmp/storage');
}

return $app;