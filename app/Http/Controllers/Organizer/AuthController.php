<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login Partner/Panitia.
     */
    public function showLogin()
    {
        // Jika sudah login sebagai organizer, langsung ke dashboard
        if (auth()->check() && auth()->user()->role === 'organizer') {
            return redirect()->route('partner.dashboard');
        }

        return view('organizer.auth.login');
    }

    /**
     * Proses login Partner/Panitia.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Pastikan yang login memang organizer
            if (auth()->user()->role !== 'organizer') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini bukan akun Partner/Panitia. Silakan gunakan halaman login yang sesuai.',
                ]);
            }

            return redirect()->route('partner.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau Password yang Anda berikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    /**
     * Proses logout Partner/Panitia.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('partner.login')
            ->with('success', 'Anda telah berhasil keluar.');
    }
}
