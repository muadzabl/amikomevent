<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Partner - {{ $organizer->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-emerald-50/40 font-sans">
    <div class="min-h-screen flex flex-col">

        <!-- Navbar Partner (Hijau) -->
        <header class="bg-gradient-to-r from-emerald-700 via-emerald-600 to-teal-700 text-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 md:px-6 py-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/amikom-logo.png') }}" alt="Logo Amikom" class="w-12 h-12 object-contain bg-white rounded-xl p-1 shadow-md flex-shrink-0">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="text-lg md:text-2xl font-black">{{ $organizer->name }}</h1>
                            <span class="px-2.5 py-0.5 bg-emerald-400/20 text-emerald-200 border border-emerald-400/40 text-[10px] font-black rounded-full uppercase tracking-wider">Partner Active</span>
                        </div>
                        <p class="text-xs text-emerald-100 mt-0.5">Logged in as: <strong class="text-white">{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3 w-full md:w-auto justify-between md:justify-end">
                    <a href="{{ route('scan.index') }}" target="_blank" class="bg-white text-emerald-700 hover:bg-emerald-50 px-4 py-2 rounded-xl text-xs font-black transition shadow-lg flex items-center gap-1.5">
                        📷 Scanner Hari-H
                    </a>
                    <a href="{{ route('home') }}" class="bg-emerald-800/60 hover:bg-emerald-800 border border-emerald-500/40 px-4 py-2 rounded-xl text-xs font-bold transition">
                        ← Beranda
                    </a>
                    <form action="{{ route('partner.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-rose-600 hover:bg-rose-700 px-4 py-2 rounded-xl text-xs font-bold transition shadow">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto w-full px-4 md:px-6 py-8 flex-1">
            
            <!-- Cards Analitik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Acara -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Acara Diselenggarakan</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $totalEvents }} <span class="text-sm font-normal text-slate-500">Event</span></h3>
                </div>

                <!-- Total Tiket Terjual (Dapat Diklik untuk melihat daftar pembeli) -->
                <a href="#daftar-pembeli"
                   class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:border-emerald-500 hover:shadow-lg transition-all duration-300 group cursor-pointer relative overflow-hidden block">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Total Tiket Terjual</p>
                            <h3 class="text-3xl font-black text-emerald-600">{{ $totalTicketsSold }} <span class="text-sm font-normal text-slate-500">Tiket</span></h3>
                        </div>
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white rounded-lg text-[10px] font-bold uppercase transition">
                            Lihat Pembeli ↓
                        </span>
                    </div>
                    <p class="text-xs text-emerald-500 group-hover:underline mt-3 font-semibold">Klik untuk melihat rincian siapa saja pembeli tiket →</p>
                </a>

                <!-- Analitik Pendapatan -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Total Pendapatan</p>
                    <h3 class="text-3xl font-black text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
            </div>

            <!-- Tabel Event Milik HIMA Ini -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/amikom-logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                        <h2 class="text-lg font-bold text-slate-800">Daftar Acara Kepanitiaan</h2>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-emerald-50/60 text-xs uppercase text-slate-400 font-semibold border-b">
                            <tr>
                                <th class="p-4">Nama Acara</th>
                                <th class="p-4">Tanggal</th>
                                <th class="p-4">Harga Tiket</th>
                                <th class="p-4">Lokasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($events as $event)
                                <tr class="hover:bg-emerald-50/30 transition">
                                    <td class="p-4 font-bold text-slate-800">{{ $event->title }}</td>
                                    <td class="p-4">{{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}</td>
                                    <td class="p-4 font-bold text-emerald-600">Rp {{ number_format($event->price, 0, ',', '.') }}</td>
                                    <td class="p-4">{{ $event->location }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-6 text-center text-slate-400">Belum ada acara yang dibuat oleh organisasi ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Daftar Pembeli Tiket -->
            <div id="daftar-pembeli" class="bg-white rounded-2xl shadow-sm border-2 border-emerald-100 overflow-hidden mb-8 scroll-mt-6">
                <div class="p-6 border-b border-slate-100 bg-emerald-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-black text-slate-900">👥 Daftar Pembeli Tiket</h2>
                            <span class="px-3 py-0.5 bg-emerald-600 text-white rounded-full text-xs font-bold">
                                {{ $transactions->count() }} Pesanan
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Daftar pelanggan yang telah membeli tiket untuk acara organisasi Anda.</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-emerald-50/60 text-xs uppercase text-slate-400 font-semibold border-b">
                            <tr>
                                <th class="p-4">Nama Pembeli</th>
                                <th class="p-4">Kontak Pelanggan</th>
                                <th class="p-4">Nama Acara</th>
                                <th class="p-4">Order ID</th>
                                <th class="p-4">Nominal</th>
                                <th class="p-4">Tanggal Transaksi</th>
                                <th class="p-4">Status Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($transactions as $transaction)
                                @php
                                    $isPaid = in_array(strtolower($transaction->status), ['paid', 'success', 'settlement', 'capture']);
                                @endphp
                                <tr class="hover:bg-emerald-50/30 transition">
                                    <td class="p-4 font-bold text-slate-800">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center font-bold text-xs">
                                                {{ strtoupper(substr($transaction->customer_name ?? 'U', 0, 1)) }}
                                            </div>
                                            <span>{{ $transaction->customer_name }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-xs">
                                        <div class="font-medium text-slate-800">{{ $transaction->customer_email }}</div>
                                        <div class="text-slate-400 font-mono mt-0.5">{{ $transaction->customer_phone ?? '-' }}</div>
                                    </td>
                                    <td class="p-4 font-semibold text-emerald-900">{{ $transaction->event->title ?? '-' }}</td>
                                    <td class="p-4 font-mono text-xs font-bold text-slate-600">{{ $transaction->order_id }}</td>
                                    <td class="p-4 font-bold text-emerald-600">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                    <td class="p-4 text-xs text-slate-500">
                                        {{ $transaction->created_at ? $transaction->created_at->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $isPaid ? 'bg-emerald-100 text-emerald-700 border border-emerald-300' : 'bg-amber-100 text-amber-700 border border-amber-300' }}">
                                            {{ $isPaid ? 'LUNAS / PAID' : strtoupper($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-slate-400 font-medium">
                                        Belum ada transaksi pembeli tiket untuk acara ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

        <!-- Footer Partner -->
        <footer class="bg-emerald-800 text-emerald-200 text-center py-4 text-xs">
            <div class="flex items-center justify-center gap-2">
                <img src="{{ asset('images/amikom-logo.png') }}" alt="Logo" class="w-5 h-5 object-contain">
                <span>© 2026 AmikomEventHub — Panel Partner & Kepanitiaan.</span>
            </div>
        </footer>

    </div>
</body>
</html>