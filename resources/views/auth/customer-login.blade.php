<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Customer - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-6">

    <div class="w-full max-w-md">
        {{-- Logo & Judul --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">AH</div>
                <span class="text-xl font-bold text-slate-800">AmikomEventHub</span>
            </a>
            <h1 class="text-2xl font-black text-slate-900">Selamat Datang! 👋</h1>
            <p class="text-slate-500 text-sm mt-1">Masuk ke akun Anda untuk memesan tiket.</p>
        </div>

        {{-- Card Form --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl p-8">

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-2xl mb-6 text-sm font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-2xl mb-6 text-sm font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl outline-none transition font-medium focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"
                        placeholder="email@contoh.com" required autocomplete="email">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                    <input type="password" name="password"
                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl outline-none transition font-medium focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"
                        placeholder="••••••••" required autocomplete="current-password">
                </div>
                <button type="submit"
                    class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-lg shadow-lg shadow-indigo-200 transition">
                    Masuk Sekarang
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-3 text-slate-400 font-bold">Atau</span></div>
            </div>

            {{-- Google Login --}}
            <a href="{{ route('google.login') }}" class="w-full py-3.5 bg-white border-2 border-slate-200 text-slate-700 rounded-2xl font-bold flex items-center justify-center gap-3 hover:bg-slate-50 hover:border-slate-300 transition text-sm">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Lanjutkan dengan Google</span>
            </a>
        </div>

        {{-- Link Jalur Login Lain --}}
        <div class="mt-6 text-center space-y-2">
            <p class="text-slate-400 text-xs font-semibold">Memiliki peran khusus di platform?</p>
            <div class="flex flex-wrap items-center justify-center gap-4 text-xs font-bold">
                <a href="{{ route('partner.login') }}" class="text-emerald-600 hover:underline">🏛️ Login Partner</a>
                <span class="text-slate-300">•</span>
                <a href="{{ route('admin.login') }}" class="text-slate-500 hover:underline">🛡️ Login Admin</a>
            </div>
            <a href="{{ route('home') }}" class="block text-slate-400 text-xs hover:text-slate-600 transition mt-2">← Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>
