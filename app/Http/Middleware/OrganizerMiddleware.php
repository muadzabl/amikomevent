<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizerMiddleware
{
    /**
     * Pastikan hanya user dengan role 'organizer' yang dapat mengakses dashboard partner.
     * Jika belum login -> redirect ke /partner/login
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('partner.login');
        }

        if (auth()->user()->role !== 'organizer') {
            return redirect()->route('partner.login')
                ->withErrors(['email' => 'Silakan login sebagai Partner/Panitia untuk mengakses halaman ini.']);
        }

        return $next($request);
    }
}

