@extends('layouts.app')

@section('content')

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 space-y-8">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">#1 Event Platform</span>
            <h1 class="text-5xl md:text-7xl font-extrabold leading-tight">
                Temukan & Pesan <span class="text-indigo-600">Tiket Event</span> Impianmu.
            </h1>
            <p class="text-lg text-slate-500 max-w-lg leading-relaxed">
                Dari konser musik hingga workshop teknologi, semua ada di genggamanmu. Pesan aman & cepat dengan Midtrans.
            </p>
            <div class="flex gap-4">
                <a href="#events" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:scale-105 transition-transform">
                    Mulai Jelajah
                </a>
                <a href="#" class="px-8 py-4 border-2 border-slate-200 rounded-2xl font-bold text-lg hover:border-indigo-600 hover:text-indigo-600 transition">
                    Cara Pesan
                </a>
            </div>
        </div>
        <div class="flex-1 relative">
            <div class="absolute -top-10 -left-10 w-64 h-64 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <img src="assets/concert.png" alt="Concert" class="rounded-[2rem] shadow-2xl relative z-10 w-full object-cover aspect-[4/5] object-center">
            <div class="absolute -bottom-6 -left-6 glass p-6 rounded-2xl shadow-xl z-20 border border-white">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold uppercase">Terverifikasi</p>
                        <p class="font-bold">Pembayaran Aman via Midtrans</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kategori Filter -->
    @if ($categories->isNotEmpty())
    <section class="max-w-7xl mx-auto px-6 py-12">
        <div class="mb-8">
            <h2 class="text-2xl font-extrabold mb-1">Jelajahi Kategori</h2>
            <p class="text-slate-500 font-medium">Temukan event sesuai minatmu</p>
        </div>
        <div class="flex flex-wrap gap-3">
            {{-- Tombol Semua --}}
            <a href="{{ url('/') }}"
               class="px-5 py-2.5 rounded-2xl font-bold text-sm shadow-sm transition border-2
                      {{ !$selectedCategory ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white border-slate-200 hover:border-indigo-600 hover:text-indigo-600' }}">
                Semua
            </a>
            {{-- Tombol per Kategori --}}
            @foreach ($categories as $category)
                <a href="{{ url('/?category=' . $category->id) }}"
                   class="px-5 py-2.5 rounded-2xl font-bold text-sm shadow-sm transition border-2
                          {{ $selectedCategory == $category->id ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white border-slate-200 hover:border-indigo-600 hover:text-indigo-600' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Events Grid -->
    <section id="events" class="max-w-7xl mx-auto px-6 py-10">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-extrabold mb-2">
                    @if ($selectedCategory)
                        Event: {{ $categories->firstWhere('id', $selectedCategory)?->name }}
                    @else
                        Event Terdekat
                    @endif
                </h2>
                <p class="text-slate-500 font-medium">Jangan sampai ketinggalan acara seru minggu ini!</p>
            </div>
        </div>

        @if ($events->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($events as $event)
                    <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden">
                        <div class="relative overflow-hidden aspect-[3/4]">
                            @if ($event->poster_path)
                                <img src="{{ asset('storage/' . $event->poster_path) }}"
                                     alt="{{ $event->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-indigo-100 flex items-center justify-center text-indigo-300">
                                    <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            @if ($event->category)
                                <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600">
                                    {{ $event->category->name }}
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 group-hover:text-indigo-600 transition">{{ $event->title }}</h3>
                            <div class="flex items-center gap-2 text-slate-500 text-sm mb-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $event->date->format('d F Y, H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>{{ $event->location }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-4 border-t">
                                <span class="text-2xl font-black text-indigo-600">
                                    {{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                                </span>
                                <a href="{{ url('event/' . $event->id) }}"
                                   class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 text-slate-400">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="font-semibold text-lg">Tidak ada event untuk kategori ini.</p>
                <a href="{{ url('/') }}" class="mt-4 inline-block text-indigo-600 font-bold hover:underline">Lihat semua event</a>
            </div>
        @endif
    </section>

    <!-- Partner Section -->
    @if ($partners->isNotEmpty())
    <section class="bg-slate-50 py-20 mt-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold mb-2">Partner Kami</h2>
                <p class="text-slate-500 font-medium">Didukung oleh berbagai organisasi dan perusahaan terpercaya</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach ($partners as $partner)
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 p-6 flex flex-col items-center justify-center gap-3">
                        @if ($partner->logo_url)
                            <img src="{{ $partner->logo_url }}"
                                 alt="Logo {{ $partner->name }}"
                                 class="h-12 w-auto object-contain"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-12 h-12 bg-indigo-600 rounded-xl hidden items-center justify-center text-white font-black text-lg">
                                {{ strtoupper(substr($partner->name, 0, 1)) }}
                            </div>
                        @else
                            <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black text-lg">
                                {{ strtoupper(substr($partner->name, 0, 1)) }}
                            </div>
                        @endif
                        <p class="text-sm font-bold text-slate-700 text-center">{{ $partner->name }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

@endsection