@extends('layouts.app')

@section('title', 'Tiket Saya')

@section('content')
<!-- CSS khusus untuk pencetakan PDF / Print -->
<style>
    @media print {
        nav, footer, .no-print, header {
            display: none !important;
        }
        body {
            background-color: #ffffff !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        main {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
        }
        .ticket-card {
            border: 2px solid #e2e8f0 !important;
            box-shadow: none !important;
            margin: 20px auto !important;
            max-width: 450px !important;
            page-break-inside: avoid;
        }
    }
</style>

<main class="max-w-5xl mx-auto px-6 py-12">
    <!-- Header Halaman -->
    <div class="mb-10 text-center no-print">
        <span class="px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider">
            🎫 Area Pengguna
        </span>
        <h1 class="text-4xl font-black text-slate-900 mt-3">Tiket Saya</h1>
        <p class="text-slate-500 font-medium mt-1">Daftar E-Ticket acara yang telah Anda pesan.</p>
    </div>

    <!-- Alert Notifikasi Session -->
    @if(session('success'))
        <div class="max-w-xl mx-auto mb-6 p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 font-bold text-sm rounded-2xl flex items-center gap-3 shadow-sm no-print">
            <span class="text-xl">🌟</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-xl mx-auto mb-6 p-4 bg-rose-100 border border-rose-300 text-rose-800 font-bold text-sm rounded-2xl flex items-center gap-3 shadow-sm no-print">
            <span class="text-xl">⚠️</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if ($transactions->isEmpty())
        <!-- TAMPILAN JIKA BELUM MEMILIKI TIKET -->
        <div class="max-w-md mx-auto bg-white rounded-3xl p-10 text-center border border-slate-100 shadow-sm no-print">
            <div class="w-20 h-20 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800 mb-2">Belum Ada Tiket Terbit</h3>
            <p class="text-slate-500 text-sm leading-relaxed mb-6">
                Anda belum memiliki pesanan tiket aktif saat ini. Jelajahi acara seru dan dapatkan tiketmu sekarang!
            </p>
            <a href="{{ route('home') }}"
               class="inline-block px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-indigo-200 transition">
                🚀 Jelajahi Event
            </a>
        </div>
    @else
        <!-- DAFTAR TIKET PENGGUNA -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            @foreach ($transactions as $transaction)
                @php
                    $isPaid = in_array(strtolower($transaction->status), ['paid', 'success', 'settlement', 'capture']);
                    $isUsed = (bool) $transaction->is_used;
                    $userReview = $transaction->event ? $transaction->event->reviews->first() : null;
                @endphp

                <div class="ticket-card bg-white rounded-3xl border {{ $isUsed ? 'border-slate-300 shadow-md' : 'border-slate-200 shadow-xl' }} overflow-hidden text-left flex flex-col justify-between relative">
                    
                    <!-- Header Tiket -->
                    <div class="p-6 {{ $isUsed ? 'bg-slate-700' : ($isPaid ? 'bg-indigo-600' : 'bg-amber-500') }} text-white relative">
                        <div class="flex justify-between items-center mb-2">
                            <span class="px-3 py-1 bg-white/20 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                E-Ticket Resmi
                            </span>

                            @if($isUsed)
                                <span class="px-3 py-1 bg-rose-500 text-white rounded-full text-xs font-black uppercase shadow-sm flex items-center gap-1">
                                    <span>🚫</span> SUDAH TERPAKAI
                                </span>
                            @else
                                <span class="px-3 py-1 bg-white text-slate-900 rounded-full text-xs font-black uppercase shadow-sm">
                                    {{ $isPaid ? 'LUNAS / AKTIF' : strtoupper($transaction->status) }}
                                </span>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold leading-snug mt-2">
                            {{ $transaction->event->title ?? 'Amikom Event' }}
                        </h3>
                    </div>

                    <!-- Banner Status Tiket Sudah Dipakai -->
                    @if($isUsed)
                        <div class="bg-rose-50 border-b border-rose-200 p-4 text-center">
                            <div class="flex items-center justify-center gap-2 text-rose-700 text-xs font-bold">
                                <span>🚨</span>
                                <span>Tiket ini SUDAH DIPAKAI CHECK-IN pada {{ $transaction->check_in_at ? \Carbon\Carbon::parse($transaction->check_in_at)->format('H:i \W\I\B, d M Y') : 'hari-H' }}. Tidak dapat digunakan lagi!</span>
                            </div>
                        </div>
                    @endif

                    <!-- Detail Informasi Tiket -->
                    <div class="p-6 space-y-4 border-b border-dashed border-slate-200">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Nama Pembeli</p>
                                <p class="text-sm font-bold text-slate-800">{{ $transaction->customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Order ID</p>
                                <p class="text-sm font-bold text-indigo-600 font-mono">{{ $transaction->order_id }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Tanggal & Waktu</p>
                                <p class="text-xs font-semibold text-slate-700">
                                    {{ \Carbon\Carbon::parse($transaction->event->date ?? now())->translatedFormat('d M Y, H:i') }} WIB
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Lokasi</p>
                                <p class="text-xs font-semibold text-slate-700">
                                    {{ $transaction->event->location ?? 'Online / Hybrid' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Area QR Code Dinamis -->
                    <div class="bg-slate-50 p-6 text-center">
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-3">
                            {{ $isUsed ? 'Status QR Code: VOID / EXPIRED' : 'Scan QR Code Ini saat Check-in' }}
                        </p>
                        
                        <div class="relative inline-block p-3 bg-white border-2 {{ $isUsed ? 'border-slate-300' : 'border-indigo-500' }} rounded-2xl shadow-sm overflow-hidden">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $transaction->order_id }}" 
                                 alt="QR Code {{ $transaction->order_id }}" 
                                 class="w-40 h-40 mx-auto {{ $isUsed ? 'opacity-20 filter grayscale' : '' }}">

                            @if($isUsed)
                                <div class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900/60 backdrop-blur-[2px] text-white p-2">
                                    <span class="text-2xl font-black text-rose-500 uppercase tracking-widest border-4 border-rose-500 rounded-xl px-3 py-1 -rotate-12 shadow-2xl bg-slate-950/90">
                                        USED
                                    </span>
                                    <span class="text-[10px] font-bold mt-2 text-slate-200 bg-slate-950/80 px-2.5 py-0.5 rounded-full">
                                        Sudah Masuk Event
                                    </span>
                                </div>
                            @endif
                        </div>

                        <p class="mt-3 font-mono font-black text-slate-700 tracking-widest text-sm">
                            {{ $transaction->order_id }}
                        </p>
                    </div>

                    <!-- Tombol Aksi PDF/Print -->
                    <div class="p-6 bg-white border-t border-slate-100 flex flex-col sm:flex-row gap-3 no-print">
                        <a href="{{ route('ticket.download', $transaction->id) }}"
                           class="flex-1 py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-center rounded-xl font-bold text-xs transition shadow-md shadow-indigo-200">
                            📥 Download PDF
                        </a>
                        <button onclick="window.print()"
                                class="flex-1 py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition">
                            🖨️ Cetak Tiket
                        </button>
                    </div>

                    <!-- BAGIAN ULASAN DAN PENILAIAN BINTANG (Hanya Tampil Setelah Tiket Di-Scan / Used) -->
                    @if($isUsed && $transaction->event)
                        <div class="p-6 bg-amber-50/70 border-t-2 border-amber-200 no-print">
                            @if(!$userReview)
                                <!-- Form Input Ulasan & Rating Bintang -->
                                <div class="bg-white p-5 rounded-2xl border border-amber-200 shadow-sm">
                                    <div class="flex items-center gap-2.5 mb-3">
                                        <span class="text-2xl">🌟</span>
                                        <div>
                                            <h4 class="text-sm font-extrabold text-slate-800">Berikan Ulasan & Rating Acara</h4>
                                            <p class="text-[11px] text-slate-500 font-medium">Tiket Anda telah discan! Bagaimana pengalaman Anda mengikuti acara ini?</p>
                                        </div>
                                    </div>

                                    <form action="{{ route('reviews.store', $transaction->event_id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <!-- Rating Bintang Interaktif -->
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1.5">Penilaian Bintang</label>
                                            <div class="flex items-center gap-1.5" id="star-rating-container-{{ $transaction->id }}">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <button type="button" 
                                                            onclick="setRating({{ $transaction->id }}, {{ $i }})" 
                                                            onmouseover="hoverRating({{ $transaction->id }}, {{ $i }})" 
                                                            onmouseleave="resetRating({{ $transaction->id }})"
                                                            class="star-btn-{{ $transaction->id }} text-slate-300 hover:scale-125 transition-all duration-200 focus:outline-none" 
                                                            data-value="{{ $i }}"
                                                            title="{{ $i }} Bintang">
                                                        <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24">
                                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                                        </svg>
                                                    </button>
                                                @endfor
                                                <span id="rating-text-{{ $transaction->id }}" class="text-xs font-bold text-amber-600 ml-2">Pilih 1-5 Bintang</span>
                                            </div>
                                            <input type="hidden" name="rating" id="rating-input-{{ $transaction->id }}" value="" required>
                                        </div>

                                        <!-- Input Catatan Ulasan -->
                                        <div>
                                            <label for="comment-{{ $transaction->id }}" class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Ulasan Anda</label>
                                            <textarea name="comment" 
                                                      id="comment-{{ $transaction->id }}" 
                                                      rows="3" 
                                                      required 
                                                      placeholder="Tulis ulasan, saran, atau kesan Anda mengenai acara ini..." 
                                                      class="w-full p-3 rounded-xl border border-slate-200 text-xs text-slate-800 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition outline-none resize-none"></textarea>
                                        </div>

                                        <!-- Tombol Kirim -->
                                        <button type="submit" 
                                                class="w-full py-3 px-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-black text-xs rounded-xl shadow-md shadow-amber-200 transition flex items-center justify-center gap-2">
                                            <span>🚀</span> Kirim Ulasan & Rating
                                        </button>
                                    </form>
                                </div>
                            @else
                                <!-- Tampilan Ulasan Yang Sudah Dikirim -->
                                <div class="bg-white p-5 rounded-2xl border border-emerald-200 shadow-sm">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg">🌟</span>
                                            <h4 class="text-xs font-extrabold text-slate-800">Ulasan & Penilaian Anda</h4>
                                        </div>
                                        <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full border border-emerald-200 flex items-center gap-1">
                                            <span>✓</span> Terkirim
                                        </span>
                                    </div>

                                    <!-- Bintang Rating Terkirim -->
                                    <div class="flex items-center gap-1 mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $userReview->rating ? 'text-amber-400 fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 24 24">
                                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                            </svg>
                                        @endfor
                                        <span class="text-xs font-bold text-amber-600 ml-1.5">({{ $userReview->rating }}/5)</span>
                                    </div>

                                    <p class="text-xs text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100 italic leading-relaxed">
                                        "{{ $userReview->comment }}"
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-semibold mt-2 text-right">
                                        Dikirim pada {{ $userReview->created_at ? $userReview->created_at->translatedFormat('d M Y, H:i') : 'hari ini' }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
            @endforeach
        </div>
    @endif
</main>

<script>
    const selectedRatings = {};

    function setRating(transactionId, rating) {
        selectedRatings[transactionId] = rating;
        document.getElementById('rating-input-' + transactionId).value = rating;
        updateStars(transactionId, rating);
        
        const labels = ['Sangat Buruk (1/5)', 'Buruk (2/5)', 'Cukup (3/5)', 'Bagus (4/5)', 'Sangat Bagus (5/5)'];
        const labelEl = document.getElementById('rating-text-' + transactionId);
        if (labelEl) {
            labelEl.innerText = labels[rating - 1];
        }
    }

    function hoverRating(transactionId, rating) {
        updateStars(transactionId, rating);
    }

    function resetRating(transactionId) {
        const current = selectedRatings[transactionId] || 0;
        updateStars(transactionId, current);
    }

    function updateStars(transactionId, rating) {
        const buttons = document.querySelectorAll('.star-btn-' + transactionId);
        buttons.forEach((btn, index) => {
            if (index < rating) {
                btn.classList.remove('text-slate-300');
                btn.classList.add('text-amber-400');
            } else {
                btn.classList.remove('text-amber-400');
                btn.classList.add('text-slate-300');
            }
        });
    }
</script>
@endsection