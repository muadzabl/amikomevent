<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="min-h-screen bg-slate-900 flex items-center justify-center p-6 text-slate-100">

    <div class="w-full max-w-md">
        {{-- Logo & Judul --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">AH</div>
                <span class="text-xl font-bold text-white">AmikomEventHub</span>
            </a>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 rounded-full text-xs font-bold mb-3 uppercase tracking-wider">
                🛡️ Panel Administrator Sistem
            </div>
            <h1 class="text-2xl font-black text-white">Login Admin</h1>
            <p class="text-slate-400 text-sm mt-1">Masukkan kredensial akun administrator Anda.</p>
        </div>

        {{-- Card Form --}}
        <div class="bg-slate-800 rounded-3xl border border-slate-700 shadow-2xl p-8">

            @if ($errors->any())
                <div class="bg-rose-950/80 border border-rose-500/50 text-rose-300 p-4 rounded-2xl mb-6 text-sm font-medium">
                    <div class="font-bold mb-1">Akses Gagal:</div>
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('success'))
                <div class="bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 p-4 rounded-2xl mb-6 text-sm font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Email Admin</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-5 py-3.5 bg-slate-900 border border-slate-700 rounded-2xl outline-none transition font-medium text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20"
                        placeholder="admin@amikom.ac.id" required autocomplete="email">
                </div>
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Password Admin</label>
                    <input id="password" type="password" name="password"
                        class="w-full px-5 py-3.5 bg-slate-900 border border-slate-700 rounded-2xl outline-none transition font-medium text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20"
                        placeholder="••••••••" required autocomplete="current-password">
                </div>
                <button type="submit"
                    class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl font-bold text-lg shadow-lg shadow-indigo-600/30 transition">
                    Masuk ke Panel Admin
                </button>
            </form>
        </div>

        {{-- Navigasi Jalur Login Lain --}}
        <div class="mt-6 text-center space-y-2">
            <p class="text-slate-400 text-xs font-semibold">Jalur Login Lainnya:</p>
            <div class="flex flex-wrap items-center justify-center gap-4 text-xs font-bold">
                <a href="{{ route('partner.login') }}" class="text-emerald-400 hover:underline">🏛️ Login Partner</a>
                <span class="text-slate-600">•</span>
                <a href="{{ route('login') }}" class="text-indigo-400 hover:underline">👤 Login Customer</a>
            </div>
            <a href="{{ route('home') }}" class="block text-slate-500 text-xs hover:text-slate-400 transition mt-2">← Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>