@extends('layouts.app')
@section('title', 'Pembayaran Berhasil')

@section('content')
<!-- CSS KHUSUS PRINT / CETAK PDF -->
<style>
    @media print {
        /* Sembunyikan elemen pengganggu (Navbar, Footer, Tombol, Header Pesan) */
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

        /* Buat E-Ticket tampil bersih tepat di tengah halaman cetak */
        .ticket-card {
            border: 2px solid #e2e8f0 !important;
            box-shadow: none !important;
            margin: 20px auto !important;
            max-width: 450px !important;
            page-break-inside: avoid;
        }
    }
</style>

<main class="max-w-3xl mx-auto px-6 py-12 text-center">
    <!-- Header Konfirmasi (Disembunyikan saat di-print) -->
    <div class="mb-8 no-print">
        <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-black text-slate-800 mb-2">Pemesanan Berhasil!</h2>
        <p class="text-slate-500 text-sm">
            E-Ticket telah diterbitkan dan dikirim ke email: <strong class="text-slate-700">{{ $transaction->customer_email }}</strong>
        </p>
    </div>

    <!-- KARTU E-TICKET UTAMA (Hanya bagian ini yang tercetak rapi) -->
    <div class="ticket-card bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden max-w-md mx-auto text-left mb-8">
        
        <!-- Header Tiket -->
        <div class="bg-indigo-600 p-6 text-white">
            <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-xs font-semibold tracking-wider uppercase mb-2">E-Ticket Resmi</span>
            <h3 class="text-xl font-bold leading-snug">{{ $transaction->event->title ?? 'Amikom Event' }}</h3>
        </div>

        <!-- Detail Informasi Transaksi -->
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
                    <p class="text-xs font-semibold text-slate-700">{{ $transaction->event->location ?? 'Online / Hybrid' }}</p>
                </div>
            </div>
        </div>

        <!-- AREA QR CODE DINAMIS -->
        <div class="bg-slate-50 p-6 text-center">
            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-3">Scan QR Code Ini untuk Check-in</p>
            
            <div class="inline-block p-3 bg-white border-2 border-indigo-500 rounded-2xl shadow-sm">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $transaction->order_id }}" 
                     alt="QR Code Tiket" 
                     class="w-44 h-44 mx-auto">
            </div>

            <p class="mt-3 font-mono font-black text-slate-700 tracking-widest text-sm">
                {{ $transaction->order_id }}
            </p>
        </div>
    </div>

    <!-- Tombol Aksi (Disembunyikan saat di-print) -->
    <div class="no-print flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="{{ route('home') }}" class="w-full sm:w-auto px-8 py-3 bg-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-300 transition">
            Kembali ke Beranda
        </a>
        <button onclick="window.print()" class="w-full sm:w-auto px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>
</main>
@endsection