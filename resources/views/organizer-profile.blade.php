@extends('layouts.app')

@section('title', 'Profil Penyelenggara - ' . $organizer->name)

@section('content')
<main class="max-w-6xl mx-auto px-6 py-10">

    {{-- Banner & Identitas Penyelenggara --}}
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 rounded-3xl p-8 md:p-12 text-white shadow-2xl relative overflow-hidden mb-10">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col md:flex-row items-center md:items-start gap-8 relative z-10">
            {{-- Logo / Avatar Organisasi --}}
            <div class="w-28 h-28 md:w-32 md:h-32 bg-white text-indigo-900 rounded-3xl flex items-center justify-center font-black text-4xl md:text-5xl shadow-2xl border-4 border-white/20 flex-shrink-0">
                {{ strtoupper(substr($organizer->name, 0, 2)) }}
            </div>

            <div class="text-center md:text-left flex-1 space-y-3">
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                    <span class="px-3.5 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-ping"></span>
                        Verified Partner
                    </span>
                    <span class="px-3.5 py-1 bg-white/10 text-indigo-200 border border-white/20 rounded-full text-xs font-semibold">
                        Penyelenggara Resmi Amikom
                    </span>
                </div>

                <h1 class="text-3xl md:text-4xl font-black leading-tight">{{ $organizer->name }}</h1>
                <p class="text-indigo-200 text-sm md:text-base max-w-2xl leading-relaxed">
                    {{ $organizer->description ?? 'Penyelenggara resmi acara, workshop, seminar, dan konser mahasiswa di AmikomEventHub.' }}
                </p>

                {{-- Quick Info Kontak --}}
                <div class="pt-2 flex flex-wrap items-center justify-center md:justify-start gap-6 text-xs text-indigo-300">
                    @if($organizer->email)
                        <span class="flex items-center gap-1.5">
                            ✉️ {{ $organizer->email }}
                        </span>
                    @endif
                    @if($organizer->phone)
                        <span class="flex items-center gap-1.5">
                            📞 {{ $organizer->phone }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Grid Statistik Rekam Jejak (Trust Metrics) --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-8 border-t border-white/10 text-center md:text-left">
            <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                <p class="text-xs text-indigo-300 font-bold uppercase tracking-wider mb-1">Rata-rata Rating</p>
                <div class="flex items-center justify-center md:justify-start gap-2">
                    <span class="text-3xl font-black text-amber-400">{{ $organizer->averageRating() }}</span>
                    <div>
                        <div class="flex text-amber-400 text-xs">
                            @for($i = 1; $i <= 5; $i++)
                                <span>{{ $i <= round($organizer->averageRating()) ? '★' : '☆' }}</span>
                            @endfor
                        </div>
                        <span class="text-[10px] text-indigo-200 block font-medium">{{ $totalReviewsCount }} ulasan</span>
                    </div>
                </div>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                <p class="text-xs text-indigo-300 font-bold uppercase tracking-wider mb-1">Total Acara</p>
                <h3 class="text-2xl font-black text-white">{{ $events->count() }} <span class="text-xs font-normal text-indigo-300">Event</span></h3>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                <p class="text-xs text-indigo-300 font-bold uppercase tracking-wider mb-1">Tiket Terjual</p>
                <h3 class="text-2xl font-black text-emerald-400">{{ $totalTicketsSold }} <span class="text-xs font-normal text-indigo-300">Tiket</span></h3>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                <p class="text-xs text-indigo-300 font-bold uppercase tracking-wider mb-1">Tingkat Kepercayaan</p>
                <h3 class="text-2xl font-black text-indigo-200">100% <span class="text-xs font-normal text-indigo-300">Terverifikasi</span></h3>
            </div>
        </div>
    </div>

    {{-- Layout Utama: Rekam Jejak Ulasan (Kiri) & Acara (Kanan) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- Kolom Kiri: Rekam Jejak Ulasan & Breakdown Rating --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Header Ulasan --}}
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 flex items-center gap-2">
                        🌟 Rekam Jejak Ulasan & Rating
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Testimoni asli dari para peserta acara sebelumnya.</p>
                </div>
                <div class="bg-indigo-50 border border-indigo-200 px-4 py-2 rounded-2xl text-center">
                    <span class="text-xl font-black text-indigo-600 block leading-tight">{{ $organizer->averageRating() }} / 5.0</span>
                    <span class="text-[10px] text-slate-500 font-bold uppercase">Kepuasan Peserta</span>
                </div>
            </div>

            {{-- Breakdown Distribusi Bintang --}}
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-3">
                <h4 class="text-sm font-bold text-slate-800 mb-2">Distribusi Penilaian Bintang</h4>
                @foreach([5, 4, 3, 2, 1] as $star)
                    @php
                        $count = $ratingCounts[$star] ?? 0;
                        $percent = $totalReviewsCount > 0 ? round(($count / $totalReviewsCount) * 100) : 0;
                    @endphp
                    <div class="flex items-center gap-3 text-xs">
                        <div class="w-16 font-bold text-slate-600 flex items-center gap-1">
                            <span>{{ $star }}</span>
                            <span class="text-amber-400">★</span>
                        </div>
                        <div class="flex-1 bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-amber-400 h-full rounded-full transition-all" style="width: {{ $percent }}%"></div>
                        </div>
                        <span class="w-12 text-right font-semibold text-slate-400">{{ $percent }}%</span>
                    </div>
                @endforeach
            </div>

            {{-- Daftar Testimoni Peserta --}}
            <div class="space-y-4">
                @forelse($reviews as $review)
                    <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-sm hover:shadow-md transition space-y-3">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow">
                                    {{ strtoupper(substr($review->user->name ?? 'P', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-slate-900 text-sm">{{ $review->user->name ?? 'Peserta Event' }}</h4>
                                    <p class="text-[11px] text-slate-400 font-medium">
                                        Acara: <strong class="text-indigo-600">{{ $review->event->title ?? 'Event' }}</strong>
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-amber-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                                <span class="text-[10px] text-slate-400 font-medium block mt-0.5">
                                    {{ $review->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <p class="text-slate-700 text-sm leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100 italic">
                            "{{ $review->comment }}"
                        </p>
                    </div>
                @empty
                    <div class="bg-white border border-slate-200 rounded-3xl p-10 text-center">
                        <div class="text-4xl mb-3">💬</div>
                        <h4 class="font-bold text-slate-800">Belum Ada Ulasan Publik</h4>
                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                            Ulasan dan testimoni dari pembeli tiket pasca-acara akan otomatis ditampilkan di sini.
                        </p>
                    </div>
                @endforelse

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
            </div>

        </div>

        {{-- Kolom Kanan: Daftar Acara Penyelenggara --}}
        <div class="space-y-6">

            <h3 class="text-xl font-black text-slate-900 flex items-center gap-2">
                🗓️ Acara Diselenggarakan
            </h3>

            {{-- Tab Acara --}}
            <div class="space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-600">🚀 Acara Aktif & Mendatang ({{ $upcomingEvents->count() }})</h4>
                
                @forelse($upcomingEvents as $event)
                    <a href="{{ route('events.show', $event->id) }}" class="block bg-white border border-slate-200 hover:border-indigo-500 rounded-2xl p-4 shadow-sm hover:shadow-md transition group">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full inline-block mb-2">
                            {{ $event->category->name ?? 'Event' }}
                        </span>
                        <h4 class="font-bold text-slate-900 group-hover:text-indigo-600 transition line-clamp-1 text-sm">
                            {{ $event->title }}
                        </h4>
                        <p class="text-xs text-slate-500 mt-1">
                            📅 {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d M Y, H:i') }} WIB
                        </p>
                        <div class="flex justify-between items-center mt-3 pt-3 border-t border-slate-100 text-xs">
                            <span class="font-extrabold text-slate-900">
                                {{ $event->price == 0 ? 'GRATIS' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                            </span>
                            <span class="text-indigo-600 font-bold group-hover:translate-x-1 transition-transform">
                                Pesan Tiket →
                            </span>
                        </div>
                    </a>
                @empty
                    <p class="text-xs text-slate-400 italic bg-white p-4 rounded-xl border border-slate-100">Belum ada acara aktif yang dipublikasikan saat ini.</p>
                @endforelse
            </div>

            {{-- Acara Lampau --}}
            <div class="space-y-4 pt-4 border-t border-slate-200">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">🏁 Acara Selesai ({{ $pastEvents->count() }})</h4>

                @forelse($pastEvents as $event)
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Selesai</span>
                        <h5 class="font-bold text-slate-800 line-clamp-1">{{ $event->title }}</h5>
                        <p class="text-slate-400 text-[11px]">
                            📅 {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d M Y') }}
                        </p>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic">Belum ada rekam jejak acara lampau.</p>
                @endforelse
            </div>

        </div>

    </div>

</main>
@endsection
