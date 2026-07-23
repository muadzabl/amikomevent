@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<main class="max-w-3xl mx-auto px-6 py-12">
    <div class="mb-8 text-center">
        <span class="px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider">
            👤 Akun Saya
        </span>
        <h1 class="text-3xl font-black text-slate-900 mt-3">Profil Pengguna</h1>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden p-8 space-y-6">
        <div class="flex items-center gap-6 pb-6 border-b border-slate-100">
            <div class="w-20 h-20 bg-indigo-600 text-white rounded-full flex items-center justify-center font-black text-3xl shadow-lg shadow-indigo-200">
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-900">{{ $user->name }}</h2>
                <p class="text-slate-500 font-medium">{{ $user->email }}</p>
                <span class="inline-block mt-2 px-3 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-full text-xs font-bold uppercase">
                    Role: {{ strtoupper($user->role ?? 'CUSTOMER') }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
            <a href="{{ route('ticket') }}" class="p-6 bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-300 rounded-2xl transition group">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-2xl">🎫</span>
                    <span class="text-xs font-bold text-indigo-600 group-hover:translate-x-1 transition-transform">Buka →</span>
                </div>
                <h4 class="font-bold text-slate-900">Tiket Saya</h4>
                <p class="text-xs text-slate-500 mt-1">Lihat dan unduh E-Ticket acara Anda.</p>
            </a>

            @if(in_array($user->role, ['admin', 'superadmin']))
                <a href="{{ route('admin.dashboard') }}" class="p-6 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-2xl transition group">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-2xl">🛡️</span>
                        <span class="text-xs font-bold text-indigo-600 group-hover:translate-x-1 transition-transform">Masuk →</span>
                    </div>
                    <h4 class="font-bold text-slate-900">Dashboard Admin</h4>
                    <p class="text-xs text-slate-500 mt-1">Kelola data master dan transaksi.</p>
                </a>
            @elseif($user->role === 'organizer')
                <a href="{{ route('partner.dashboard') }}" class="p-6 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-2xl transition group">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-2xl">🏛️</span>
                        <span class="text-xs font-bold text-emerald-600 group-hover:translate-x-1 transition-transform">Masuk →</span>
                    </div>
                    <h4 class="font-bold text-slate-900">Dashboard Partner</h4>
                    <p class="text-xs text-slate-500 mt-1">Kelola acara dan pantau pendapatan.</p>
                </a>
            @endif
        </div>

        <div class="pt-6 border-t border-slate-100 text-center">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-8 py-3 bg-red-50 hover:bg-red-100 text-red-600 font-bold rounded-2xl text-sm transition">
                    🚪 Keluar Dari Akun
                </button>
            </form>
        </div>
    </div>
</main>
@endsection
