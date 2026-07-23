@extends('layouts.app')

@section('title', 'Pusat Bantuan & FAQ')

@section('content')
<main class="max-w-4xl mx-auto px-6 py-12">
    {{-- Header --}}
    <div class="text-center mb-12">
        <span class="px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider">
            💡 Pusat Bantuan
        </span>
        <h1 class="text-4xl font-black text-slate-900 mt-3">Ada yang Bisa Kami Bantu?</h1>
        <p class="text-slate-500 font-medium mt-2">Temukan jawaban atas pertanyaan umum mengenai reservasi tiket & event.</p>
    </div>

    {{-- Daftar FAQ --}}
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-2">1. Bagaimana cara memesan tiket event di AmikomEventHub?</h3>
            <p class="text-slate-600 text-sm leading-relaxed">
                Pilih event yang ingin Anda ikuti di halaman <a href="{{ route('home') }}" class="text-indigo-600 font-bold underline">Beranda</a> atau <a href="{{ route('katalog') }}" class="text-indigo-600 font-bold underline">Katalog</a>, lalu klik <strong>"Lihat Detail"</strong>. Isikan data pemesan dan selesaikan pembayaran melalui Midtrans.
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-2">2. Metode pembayaran apa saja yang didukung?</h3>
            <p class="text-slate-600 text-sm leading-relaxed">
                Kami mendukung berbagai pilihan pembayaran aman via Midtrans Payment Gateway, termasuk Transfer Bank (Virtual Account), E-Wallet (GoPay, ShopeePay, QRIS), dan kartu kredit.
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-2">3. Di mana saya dapat menemukan E-Ticket yang sudah saya beli?</h3>
            <p class="text-slate-600 text-sm leading-relaxed">
                Setelah pembayaran berhasil, E-Ticket Anda dapat dilihat kapan saja melalui menu <a href="{{ route('ticket') }}" class="text-indigo-600 font-bold underline">Tiket Saya</a>. E-Ticket juga dikirimkan otomatis ke email terdaftar Anda.
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-2">4. Bagaimana cara mendaftar sebagai Partner / Panitia HIMA & UKM?</h3>
            <p class="text-slate-600 text-sm leading-relaxed">
                Panitia dan penyelenggara dapat masuk ke <a href="{{ route('partner.login') }}" class="text-emerald-600 font-bold underline">Portal Login Partner</a> menggunakan akun kredensial yang telah didaftarkan dan diverifikasi oleh Admin.
            </p>
        </div>
    </div>

    {{-- Kontak Layanan Pelanggan --}}
    <div class="mt-12 p-8 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl text-white shadow-xl flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h3 class="text-2xl font-black">Masih Butuh Bantuan?</h3>
            <p class="text-indigo-100 text-sm mt-1">Tim Customer Care kami siap membantu pertanyaan dan kendala Anda.</p>
        </div>
        <div class="flex gap-4">
            <a href="mailto:support@amikomevent.ac.id" class="px-6 py-3 bg-white text-indigo-600 rounded-2xl font-bold text-sm shadow-md hover:bg-slate-100 transition">
                📧 Email Support
            </a>
        </div>
    </div>
</main>
@endsection
