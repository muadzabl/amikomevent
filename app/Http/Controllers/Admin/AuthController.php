<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login Admin.
     */
    public function showLogin()
    {
        // Jika sudah login sebagai admin, langsung ke dashboard
        if (auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses login Admin.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Pastikan yang login memang admin atau superadmin
            if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini bukan akun Admin. Silakan gunakan halaman login yang sesuai.',
                ])->onlyInput('email');
            }

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau Password yang Anda berikan tidak terdaftar di database kami.',
        ])->onlyInput('email');
    }

    /**
     * Proses logout Admin.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Anda telah berhasil keluar dari panel admin.');
    }
}
