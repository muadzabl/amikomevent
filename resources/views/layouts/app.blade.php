<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AmikomEventHub - Temukan Event Seru!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900">

    <!-- Navigation -->
    <nav class="glass sticky top-4 z-40 mx-4 mt-4 px-6 py-4 rounded-2xl border border-white/20 shadow-lg">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl">AH</div>
                    <span class="text-xl font-bold tracking-tight">AmikomEventHub</span>
                </a>
            </div>

            {{-- Links Desktop --}}
            <div class="hidden lg:flex gap-8 font-medium items-center text-sm">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Jelajahi</a>
                <a href="{{ route('katalog') }}" class="hover:text-indigo-600 transition">Katalog</a>
                <a href="{{ route('ticket') }}" class="hover:text-indigo-600 transition">Tiket Saya</a>
                <a href="{{ route('profil') }}" class="hover:text-indigo-600 transition">Profil</a>
                <a href="{{ route('bantuan') }}" class="hover:text-indigo-600 transition">Bantuan</a>
            </div>

            {{-- Tombol Auth & User Badge (Desktop & Mobile Header) --}}
            <div class="flex items-center gap-3">
                @auth
                    @php $role = auth()->user()->role; @endphp

                    {{-- Badge Nama User yang Sedang Login --}}
                    <a href="{{ route('profil') }}" class="flex items-center gap-2 bg-indigo-50 border border-indigo-100 px-3 py-1.5 rounded-xl hover:bg-indigo-100 transition">
                        <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="text-left hidden sm:block">
                            <span class="text-xs font-bold text-slate-800 block leading-tight">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] text-indigo-600 font-semibold uppercase tracking-wider block">
                                {{ $role === 'organizer' ? 'Partner' : ($role === 'admin' || $role === 'superadmin' ? 'Admin' : 'User') }}
                            </span>
                        </div>
                    </a>

                    @if (in_array($role, ['admin', 'superadmin']))
                        <a href="{{ route('admin.dashboard') }}"
                            class="hidden md:flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold text-xs hover:bg-indigo-700 transition shadow-sm">
                            Dashboard Admin
                        </a>
                    @elseif ($role === 'organizer')
                        <a href="{{ route('partner.dashboard') }}"
                            class="hidden md:flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-xl font-bold text-xs hover:bg-emerald-700 transition shadow-sm">
                            Dashboard Partner
                        </a>
                    @endif

                    {{-- Tombol Logout Desktop --}}
                    <form action="{{ route('logout') }}" method="POST" class="hidden md:block">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 border-2 border-slate-200 text-slate-600 rounded-xl font-bold text-xs hover:border-red-300 hover:text-red-600 transition">
                            Keluar
                        </button>
                    </form>
                @else
                    {{-- GUEST: Tampilkan Login & Daftar --}}
                    <a href="{{ route('login') }}"
                        class="hidden md:inline-block px-4 py-2 border-2 border-slate-200 text-slate-700 rounded-xl font-bold text-xs hover:border-indigo-600 hover:text-indigo-600 transition">
                        Login
                    </a>
                    <a href="{{ route('google.login') }}"
                        class="hidden md:inline-block px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold text-xs hover:bg-indigo-700 transition shadow-sm">
                        Daftar
                    </a>
                @endauth

                {{-- Tombol Mobile Menu Hamburger --}}
                <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')"
                    class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Dropdown Menu --}}
        <div id="mobile-menu" class="hidden lg:hidden mt-4 pt-4 border-t border-slate-200/80 space-y-3">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">Jelajahi</a>
            <a href="{{ route('katalog') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">Katalog</a>
            <a href="{{ route('ticket') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">Tiket Saya</a>
            <a href="{{ route('profil') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">Profil</a>
            <a href="{{ route('bantuan') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">Bantuan</a>

            <div class="pt-3 border-t border-slate-200/80 space-y-2">
                @auth
                    @if (in_array(auth()->user()->role, ['admin', 'superadmin']))
                        <a href="{{ route('admin.dashboard') }}" class="block w-full text-center px-4 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm">Dashboard Admin</a>
                    @elseif (auth()->user()->role === 'organizer')
                        <a href="{{ route('partner.dashboard') }}" class="block w-full text-center px-4 py-2.5 bg-emerald-600 text-white rounded-xl font-bold text-sm">Dashboard Partner</a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-center px-4 py-2.5 border-2 border-red-200 text-red-600 rounded-xl font-bold text-sm hover:bg-red-50">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2.5 border-2 border-indigo-600 text-indigo-600 rounded-xl font-bold text-sm">Login Customer</a>
                    <a href="{{ route('google.login') }}" class="block w-full text-center px-4 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm">Daftar dengan Google</a>
                @endauth
            </div>
        </div>
    </nav>


    @yield('content')

    <!-- Footer -->
    <footer class="bg-indigo-900 text-indigo-100 py-20 px-6 mt-20">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">

            {{-- Kolom 1 & 2: Logo & Deskripsi --}}
            <div class="space-y-4 col-span-2">
                <div class="flex items-center gap-2">
                    <div
                        class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-bold text-xl">
                        AH</div>
                    <span class="text-2xl font-bold text-white">AmikomEventHub</span>
                </div>
                <p class="max-w-xs text-indigo-300">Platform reservasi tiket event online terbaik untuk mahasiswa dan
                    penyelenggara profesional.</p>

                {{-- Kolom Kategori di bawah deskripsi (mobile) / inline pada desktop diganti jadi kolom sendiri --}}
            </div>

            {{-- Kolom 3: Kategori Dinamis dari DB --}}
            <div>
                <h4 class="text-white font-bold mb-6">Kategori</h4>
                <ul class="space-y-3">
                    @isset($categories)
                        @forelse ($categories as $category)
                            <li>
                                <a href="#events" class="hover:text-white transition text-indigo-300">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @empty
                            <li class="text-indigo-400 text-sm">Belum ada kategori.</li>
                        @endforelse
                    @endisset
                </ul>
            </div>

            {{-- Kolom 4: Navigasi & Kontak --}}
            <div class="space-y-10">
                <div>
                    <h4 class="text-white font-bold mb-6">Navigasi Akses</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition text-indigo-300">Beranda</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition text-indigo-300">Login Customer</a></li>
                        <li><a href="{{ route('partner.login') }}" class="hover:text-white transition text-indigo-300">Login Partner / Panitia</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-white transition text-indigo-300">Login Admin Panel</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6">Hubungi Kami</h4>
                    <ul class="space-y-4">
                        <li>support@eventtiket.com</li>
                        <li>+62 812 3456 7890</li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="max-w-7xl mx-auto pt-12 mt-12 border-t border-indigo-800 text-center text-indigo-400 text-sm">
            &copy; 2024 AmikomEventHub. Built with Laravel & Tailwind CSS.
        </div>
    </footer>

</body>

</html>