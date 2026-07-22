<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    // 1. Mengarahkan pengguna ke halaman login Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Menerima balasan/callback dari Google
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan email atau google_id
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Jika user sudah ada di DB, perbarui google_id jika belum terhubung
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);
            } else {
                // Jika user belum ada, buatkan akun baru secara otomatis
                $user = User::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'google_id'         => $googleUser->getId(),
                    'email_verified_at' => now(),
                    'password'          => Hash::make(Str::random(16)), // Password acak aman
                ]);
            }

            // Autentikasi/login-kan user
            Auth::login($user);

            return redirect()->route('admin.dashboard')->with('success', 'Berhasil login menggunakan akun Google!');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login via Google. Silakan coba lagi.');
        }
    }
}