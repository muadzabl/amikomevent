<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Partner / Panitia - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-6">

    <div class="w-full max-w-md">
        {{-- Logo Amikom & Judul --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 mb-6">
                <img src="{{ asset('images/amikom-logo.png') }}" alt="Logo Amikom" class="w-12 h-12 object-contain">
                <span class="text-xl font-bold text-slate-800">AmikomEventHub</span>
            </a>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold mb-3">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                Panel Partner & Panitia
            </div>
            <h1 class="text-2xl font-black text-slate-900">Login Partner</h1>
            <p class="text-slate-500 text-sm mt-1">Masuk ke dashboard kepanitiaan Anda.</p>
        </div>

        {{-- Card Form --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl p-8">

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-2xl mb-6 text-sm font-medium">
                    <div class="font-bold mb-1">Login Gagal:</div>
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-2xl mb-6 text-sm font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('partner.login.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email Partner</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl outline-none transition font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                        placeholder="panitia@email.com" required autocomplete="email">
                </div>
                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                    <input id="password" type="password" name="password"
                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl outline-none transition font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                        placeholder="••••••••" required autocomplete="current-password">
                </div>
                <button type="submit"
                    class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold text-lg shadow-lg shadow-emerald-200 transition">
                    Masuk ke Dashboard Partner
                </button>
            </form>
        </div>

        {{-- Navigasi Jalur Login Lain --}}
        <div class="mt-6 text-center space-y-2">
            <p class="text-slate-400 text-xs font-semibold">Butuh akses peran lain?</p>
            <div class="flex flex-wrap items-center justify-center gap-4 text-xs font-bold">
                <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">👤 Login Customer</a>
                <span class="text-slate-300">•</span>
                <a href="{{ route('admin.login') }}" class="text-slate-600 hover:underline">🛡️ Login Admin</a>
            </div>
            <a href="{{ route('home') }}" class="block text-slate-400 text-xs hover:text-slate-600 transition mt-2">← Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>
