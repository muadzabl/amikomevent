<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        /* Collapsed / Mini Sidebar mode on Desktop */
        @media (min-width: 1024px) {
            aside.sidebar-mini {
                width: 5rem !important; /* 80px / w-20 */
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
            aside.sidebar-mini .sidebar-text {
                display: none !important;
            }
            aside.sidebar-mini .sidebar-header {
                justify-content: center !important;
            }
            aside.sidebar-mini .sidebar-item {
                justify-content: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            aside.sidebar-mini #sidebarChevron {
                transform: rotate(180deg);
            }
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 flex min-h-screen relative overflow-x-hidden">

    <!-- Mobile Backdrop Overlay -->
    <div id="sidebarOverlay" onclick="toggleSidebar()" 
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden transition-opacity duration-300 opacity-0 lg:hidden">
    </div>

    <!-- Blue Sidebar -->
    <aside id="sidebar" 
           class="fixed inset-y-0 left-0 z-50 w-64 bg-indigo-900 text-indigo-100 flex flex-col p-6 space-y-8 h-screen transition-all duration-300 ease-in-out transform -translate-x-full lg:translate-x-0 lg:sticky lg:top-0 shrink-0 shadow-2xl lg:shadow-none">
        
        <div class="sidebar-header flex items-center justify-between gap-2">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-extrabold text-xl shadow-md shrink-0">AH</div>
                <span class="sidebar-text text-xl font-bold text-white tracking-tight whitespace-nowrap">AmikomEventHub</span>
            </div>
            
            <!-- Tombol Tutup/Geser di Atas Sidebar Biru (Chevron <<) -->
            <button onclick="toggleSidebar()" 
                    class="p-2 rounded-xl bg-indigo-800/60 text-indigo-200 hover:text-white hover:bg-indigo-700/80 transition flex items-center justify-center shrink-0 border border-indigo-700/50" 
                    title="Tutup / Geser Sidebar">
                <svg id="sidebarChevron" class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
            </button>
        </div>

        <nav class="flex-1 space-y-2 overflow-y-auto sidebar-scroll pr-1">
            <p class="sidebar-text text-[10px] font-bold uppercase tracking-widest text-indigo-400 mb-4 px-2">Main Menu</p>

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               title="Dashboard"
               class="sidebar-item flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-800 text-white shadow-sm' : 'hover:bg-indigo-800/70 text-indigo-100' }} rounded-xl font-bold transition">
                <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-300' : 'text-indigo-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                <span class="sidebar-text whitespace-nowrap">Dashboard</span>
            </a>

            {{-- Kelola Event --}}
            <a href="{{ route('admin.events.index') }}"
               title="Kelola Event"
               class="sidebar-item flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.events.*') ? 'bg-indigo-800 text-white shadow-sm' : 'hover:bg-indigo-800/70 text-indigo-100' }} rounded-xl font-bold transition">
                <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.events.*') ? 'text-indigo-300' : 'text-indigo-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="sidebar-text whitespace-nowrap">Kelola Event</span>
            </a>

            {{-- Laporan Transaksi --}}
            <a href="{{ route('admin.transactions.index') }}"
               title="Laporan Transaksi"
               class="sidebar-item flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.transactions.*') ? 'bg-indigo-800 text-white shadow-sm' : 'hover:bg-indigo-800/70 text-indigo-100' }} rounded-xl font-bold transition">
                <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.transactions.*') ? 'text-indigo-300' : 'text-indigo-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span class="sidebar-text whitespace-nowrap">Laporan Transaksi</span>
            </a>

            {{-- Kategori --}}
            <a href="{{ route('admin.categories.index') }}"
               title="Kategori"
               class="sidebar-item flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-800 text-white shadow-sm' : 'hover:bg-indigo-800/70 text-indigo-100' }} rounded-xl font-bold transition">
                <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.categories.*') ? 'text-indigo-300' : 'text-indigo-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                <span class="sidebar-text whitespace-nowrap">Kategori</span>
            </a>

            {{-- Partner --}}
            <a href="{{ route('admin.partners.index') }}"
               title="Partner"
               class="sidebar-item flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.partners.*') ? 'bg-indigo-800 text-white shadow-sm' : 'hover:bg-indigo-800/70 text-indigo-100' }} rounded-xl font-bold transition">
                <svg class="w-6 h-6 shrink-0 {{ request()->routeIs('admin.partners.*') ? 'text-indigo-300' : 'text-indigo-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="sidebar-text whitespace-nowrap">Partner</span>
            </a>
        </nav>

        <div class="pt-6 border-t border-indigo-800">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" 
                        title="Keluar"
                        class="sidebar-item w-full flex items-center gap-3 px-4 py-3 text-indigo-300 hover:text-white hover:bg-indigo-800/50 rounded-xl transition font-medium text-left">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="sidebar-text whitespace-nowrap">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="flex-1 flex flex-col min-w-0 transition-all duration-300">
        <!-- Main Content -->
        <main class="flex-1 p-6 md:p-10 overflow-y-auto w-full">
            <!-- Header -->
            <header class="flex justify-between items-center mb-8 w-full gap-4 pb-6 border-b border-slate-200/80">
                <div class="flex items-center gap-4">
                    <!-- Tombol Buka/Geser Sidebar Kembali (Header Button) -->
                    <button id="sidebarToggle" onclick="toggleSidebar()" 
                            class="p-2.5 rounded-xl bg-indigo-900 border border-indigo-800 text-white hover:bg-indigo-800 transition shadow-sm flex items-center justify-center gap-2 group" 
                            title="Buka / Geser Sidebar">
                        <svg class="w-6 h-6 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-black text-slate-900">@yield('page_title', 'Dashboard')</h1>
                        <p class="text-xs md:text-sm text-slate-500 font-medium mt-0.5">@yield('page_subtitle', 'Selamat datang kembali, Admin!')</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="font-bold text-sm text-slate-800 leading-tight">Admin</p>
                        <p class="text-xs text-slate-400">Penyelenggara Utama</p>
                    </div>
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-white rounded-2xl shadow-sm border border-slate-200 flex items-center justify-center p-1">
                        <img src="https://ui-avatars.com/api/?name=admin&background=6366f1&color=fff" class="rounded-xl w-full h-full object-cover">
                    </div>
                </div>
            </header>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl mb-6 font-bold text-sm flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- JavaScript for Sidebar Toggle with State Persistence -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const isDesktop = window.innerWidth >= 1024;

            if (isDesktop) {
                // Toggle mode mini (icon-only) pada sidebar biru di desktop
                const isMini = sidebar.classList.contains('sidebar-mini');
                if (isMini) {
                    sidebar.classList.remove('sidebar-mini');
                    localStorage.setItem('admin_sidebar_mini', 'false');
                } else {
                    sidebar.classList.add('sidebar-mini');
                    localStorage.setItem('admin_sidebar_mini', 'true');
                }
            } else {
                // Toggle slide overlay pada mobile
                const isHidden = sidebar.classList.contains('-translate-x-full');
                if (isHidden) {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                    setTimeout(() => overlay.classList.remove('opacity-0'), 10);
                } else {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('opacity-0');
                    setTimeout(() => overlay.classList.add('hidden'), 300);
                }
            }
        }

        // Restore saved sidebar preference on load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const isDesktop = window.innerWidth >= 1024;
            const savedState = localStorage.getItem('admin_sidebar_mini');

            if (isDesktop && savedState === 'true') {
                sidebar.classList.add('sidebar-mini');
            }
        });
    </script>
</body>
</html>