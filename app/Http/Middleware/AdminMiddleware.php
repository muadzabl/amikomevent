<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Pastikan hanya user dengan role 'admin' atau 'superadmin' yang dapat mengakses.
     * Jika belum login -> redirect ke /admin/login
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Anda tidak memiliki akses ke panel admin.']);
        }

        return $next($request);
    }
}

