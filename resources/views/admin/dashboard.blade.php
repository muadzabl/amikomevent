@extends('layouts.admin')
@section('title', 'Admin Dashboard Analytics')
@section('page_title', 'Dashboard & Analisis Grafik Pertumbuhan')
@section('page_subtitle', 'Pantau ringkasan performa pengguna, penyelenggaraan event, dan pendapatan platform.')

@section('content')

<!-- Stats Grid Top Header Cards (Dapat Diklik / Interactive Buttons) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- 1. Total Pendapatan Tiket -> Direct Link ke Laporan Transaksi -->
    <a href="{{ route('admin.transactions.index') }}" 
       class="group bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-indigo-400 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden block">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-indigo-50 group-hover:bg-indigo-600 text-indigo-600 group-hover:text-white rounded-2xl flex items-center justify-center font-bold text-xl transition-colors">
                💰
            </div>
            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Realtime
            </span>
        </div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1 group-hover:text-indigo-600 transition">Total Pendapatan Tiket</p>
        <h3 class="text-2xl font-black text-slate-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100">
            <span class="text-slate-400 text-xs font-medium">Dari {{ number_format($ticketsSold, 0, ',', '.') }} tiket lunas</span>
            <span class="text-xs font-bold text-indigo-600 group-hover:translate-x-1 transition-transform">Lihat Transaksi →</span>
        </div>
    </a>

    <!-- 2. Pertumbuhan Pengguna -> Direct Link/Scroll ke Grafik Pengguna -->
    <a href="#grafik-pengguna" 
       class="group bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-blue-400 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden block">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-blue-50 group-hover:bg-blue-600 text-blue-600 group-hover:text-white rounded-2xl flex items-center justify-center font-bold text-xl transition-colors">
                👥
            </div>
            <span class="text-xs font-bold {{ $userGrowthPct >= 0 ? 'text-emerald-600 bg-emerald-50 border border-emerald-200' : 'text-rose-600 bg-rose-50 border border-rose-200' }} px-2.5 py-1 rounded-full flex items-center gap-1">
                @if($userGrowthPct >= 0)
                    ↑ +{{ $userGrowthPct }}%
                @else
                    ↓ {{ $userGrowthPct }}%
                @endif
            </span>
        </div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1 group-hover:text-blue-600 transition">Total Pengguna Terdaftar</p>
        <h3 class="text-2xl font-black text-slate-900">{{ number_format($totalUsers, 0, ',', '.') }} <span class="text-sm font-semibold text-slate-500">User</span></h3>
        <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100">
            <span class="text-slate-400 text-xs font-medium">Pengunjung terautentikasi</span>
            <span class="text-xs font-bold text-blue-600 group-hover:translate-x-1 transition-transform">Lihat Grafik ↓</span>
        </div>
    </a>

    <!-- 3. Event Diselenggarakan -> Direct Link ke Kelola Event -->
    <a href="{{ route('admin.events.index') }}" 
       class="group bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-amber-400 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden block">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-amber-50 group-hover:bg-amber-500 text-amber-600 group-hover:text-white rounded-2xl flex items-center justify-center font-bold text-xl transition-colors">
                🎪
            </div>
            <span class="text-xs font-bold {{ $eventGrowthPct >= 0 ? 'text-amber-700 bg-amber-50 border border-amber-200' : 'text-slate-600 bg-slate-50 border border-slate-200' }} px-2.5 py-1 rounded-full flex items-center gap-1">
                @if($eventGrowthPct >= 0)
                    ↑ +{{ $eventGrowthPct }}%
                @else
                    ↓ {{ $eventGrowthPct }}%
                @endif
            </span>
        </div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1 group-hover:text-amber-600 transition">Event Diselenggarakan</p>
        <h3 class="text-2xl font-black text-slate-900">{{ $totalEvents }} <span class="text-sm font-semibold text-slate-500">Event</span></h3>
        <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100">
            <span class="text-xs font-bold text-emerald-600">{{ $activeEvents }} Event Aktif</span>
            <span class="text-xs font-bold text-amber-600 group-hover:translate-x-1 transition-transform">Kelola Event →</span>
        </div>
    </a>

    <!-- 4. Organisasi / Panitia -> Direct Link ke Manajemen Partner -->
    <a href="{{ route('admin.partners.index') }}" 
       class="group bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-purple-400 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden block">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-purple-50 group-hover:bg-purple-600 text-purple-600 group-hover:text-white rounded-2xl flex items-center justify-center font-bold text-xl transition-colors">
                🏛️
            </div>
            <span class="text-xs font-bold text-purple-600 bg-purple-50 border border-purple-200 px-2.5 py-1 rounded-full">
                Partner Active
            </span>
        </div>
        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1 group-hover:text-purple-600 transition">Organisasi / Panitia</p>
        <h3 class="text-2xl font-black text-slate-900">{{ $totalPartners }} <span class="text-sm font-semibold text-slate-500">HIMA/UKM</span></h3>
        <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100">
            <span class="text-slate-400 text-xs font-medium">Penyelenggara aktif</span>
            <span class="text-xs font-bold text-purple-600 group-hover:translate-x-1 transition-transform">Kelola Partner →</span>
        </div>
    </a>

</div>

<!-- GRAFIK VISUALISASI ANALITIK MANAGEMENT -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">

    <!-- GRAFIK 1: PERTUMBUHAN PENGGUNA TERDAFTAR -->
    <div id="grafik-pengguna" class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between scroll-mt-6">
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6 border-b pb-4 border-slate-100">
                <div>
                    <h3 class="font-black text-xl text-slate-900 flex items-center gap-2">
                        📈 Grafik Pertumbuhan Pengguna
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Tren pendaftaran akun pengguna baru per bulan (6 Bulan Terakhir)</p>
                </div>
                <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-xl text-xs font-bold self-start sm:self-auto">
                    +{{ $userGrowthPct }}% Bulan Ini
                </span>
            </div>

            <!-- Canvas Chart User Growth -->
            <div class="relative h-64 w-full">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 font-medium">
            <span>Total Pengguna: <strong class="text-slate-700">{{ $totalUsers }} User</strong></span>
            <span>Sumber Data: System Registration Logs</span>
        </div>
    </div>

    <!-- GRAFIK 2: PERTUMBUHAN PENYELENGGARAAN EVENT -->
    <div id="grafik-event" class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between scroll-mt-6">
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6 border-b pb-4 border-slate-100">
                <div>
                    <h3 class="font-black text-xl text-slate-900 flex items-center gap-2">
                        🎪 Grafik Penyelenggaraan Event
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Jumlah acara kampus & HIMA/UKM yang dipublikasikan per bulan</p>
                </div>
                <span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl text-xs font-bold self-start sm:self-auto">
                    {{ $totalEvents }} Total Acara
                </span>
            </div>

            <!-- Canvas Chart Event Growth -->
            <div class="relative h-64 w-full">
                <canvas id="eventGrowthChart"></canvas>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 font-medium">
            <span>Event Aktif: <strong class="text-emerald-600">{{ $activeEvents }} Aktif</strong></span>
            <span>Sumber Data: Event Publisher Database</span>
        </div>
    </div>

</div>

<!-- GRAFIK 3: TREN PENDAPATAN & PENJUALAN TIKET (FULL WIDTH) -->
<div id="grafik-pendapatan" class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/80 shadow-sm mb-10 scroll-mt-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6 border-b pb-4 border-slate-100">
        <div>
            <h3 class="font-black text-xl text-slate-900 flex items-center gap-2">
                💳 Grafik Pertumbuhan Transaksi & Pendapatan Tiket
            </h3>
            <p class="text-xs text-slate-500 mt-1">Akumulasi nominal transaksi sukses penjualan tiket per bulan (Rupiah)</p>
        </div>
        <div class="text-right">
            <span class="text-xs font-bold text-slate-400 block">Total Omset Platform</span>
            <span class="text-lg font-black text-indigo-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
        </div>
    </div>
    <div class="relative h-72 w-full">
        <canvas id="revenueChart"></canvas>
    </div>
</div>

<!-- Latest Sales Table -->
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-8">
    <div class="p-6 md:p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
        <div>
            <h3 class="font-black text-xl text-slate-900">📑 Riwayat Transaksi Terbaru</h3>
            <p class="text-xs text-slate-500 mt-0.5">5 Pesanan tiket terakhir yang masuk ke dalam sistem.</p>
        </div>
        <a href="{{ route('admin.transactions.index') }}" class="px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl text-xs font-bold transition flex items-center gap-1">
            Lihat Semua Transaksi →
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest border-b border-slate-100">
                <tr>
                    <th class="px-8 py-4">Tgl Transaksi / Order ID</th>
                    <th class="px-8 py-4">Nama Pembeli</th>
                    <th class="px-8 py-4">Nama Event</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 text-right">Total Bayar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($recentTransactions as $trx)
                <tr class="hover:bg-slate-50/70 transition">
                    <td class="px-8 py-5 text-slate-600">
                        <div class="font-bold text-slate-800">{{ $trx->created_at ? $trx->created_at->format('d M Y, H:i') : '-' }}</div>
                        <span class="font-mono text-xs text-slate-400">{{ $trx->order_id }}</span>
                    </td>
                    <td class="px-8 py-5">
                        <p class="font-bold text-slate-900 uppercase text-xs">{{ $trx->customer_name }}</p>
                        <p class="text-xs text-slate-400">{{ $trx->customer_email }}</p>
                    </td>
                    <td class="px-8 py-5 font-semibold text-indigo-950 truncate max-w-xs">{{ $trx->event->title ?? '-' }}</td>
                    <td class="px-8 py-5 whitespace-nowrap">
                        @if(in_array(strtolower($trx->status), ['settlement', 'success', 'paid', 'capture']))
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-extrabold uppercase tracking-wider border border-emerald-300">LUNAS</span>
                        @elseif(strtolower($trx->status) === 'pending')
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-extrabold uppercase tracking-wider border border-amber-300">PENDING</span>
                        @else
                            <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-extrabold uppercase tracking-wider border border-rose-300">{{ strtoupper($trx->status) }}</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 font-black text-emerald-600 whitespace-nowrap text-right">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-10 text-center text-slate-400 font-medium">Belum ada transaksi recorded</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- SCRIPT INISIALISASI CHART.JS --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    
    // Data dari Controller PHP
    const labels = {!! json_encode($months) !!};
    const userGrowthData = {!! json_encode($userGrowthData) !!};
    const eventGrowthData = {!! json_encode($eventGrowthData) !!};
    const revenueData = {!! json_encode($revenueData) !!};

    // -------------------------------------------------------------
    // 1. GRAFIK PERTUMBUHAN PENGGUNA (LINE CHART GRADIENT BLUE)
    // -------------------------------------------------------------
    const ctxUser = document.getElementById('userGrowthChart').getContext('2d');
    const gradientBlue = ctxUser.createLinearGradient(0, 0, 0, 250);
    gradientBlue.addColorStop(0, 'rgba(59, 130, 246, 0.35)');
    gradientBlue.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

    new Chart(ctxUser, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pengguna Baru',
                data: userGrowthData,
                borderColor: '#2563eb',
                borderWidth: 3,
                backgroundColor: gradientBlue,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: 'bold' },
                    bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                    padding: 12,
                    cornerRadius: 12,
                    callbacks: {
                        label: function(context) {
                            return ' ' + context.parsed.y + ' Pengguna Baru Baru Terdaftar';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#64748b' }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: { family: 'Plus Jakarta Sans', size: 11 },
                        color: '#64748b'
                    },
                    grid: { color: '#f1f5f9' }
                }
            }
        }
    });

    // -------------------------------------------------------------
    // 2. GRAFIK PERTUMBUHAN EVENT (BAR CHART AMBER/ORANGE)
    // -------------------------------------------------------------
    const ctxEvent = document.getElementById('eventGrowthChart').getContext('2d');
    
    new Chart(ctxEvent, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Event Diselenggarakan',
                data: eventGrowthData,
                backgroundColor: '#f59e0b',
                hoverBackgroundColor: '#d97706',
                borderRadius: 10,
                borderSkipped: false,
                barThickness: 24
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: 'bold' },
                    bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                    padding: 12,
                    cornerRadius: 12,
                    callbacks: {
                        label: function(context) {
                            return ' ' + context.parsed.y + ' Event Baru Dipublikasikan';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#64748b' }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: { family: 'Plus Jakarta Sans', size: 11 },
                        color: '#64748b'
                    },
                    grid: { color: '#f1f5f9' }
                }
            }
        }
    });

    // -------------------------------------------------------------
    // 3. GRAFIK TREN PENDAPATAN (AREA CHART INDIGO/PURPLE)
    // -------------------------------------------------------------
    const ctxRev = document.getElementById('revenueChart').getContext('2d');
    const gradientIndigo = ctxRev.createLinearGradient(0, 0, 0, 300);
    gradientIndigo.addColorStop(0, 'rgba(79, 70, 229, 0.35)');
    gradientIndigo.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

    new Chart(ctxRev, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Pendapatan (Rp)',
                data: revenueData,
                borderColor: '#4f46e5',
                borderWidth: 3.5,
                backgroundColor: gradientIndigo,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#4f46e5',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: 'bold' },
                    bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                    padding: 12,
                    cornerRadius: 12,
                    callbacks: {
                        label: function(context) {
                            return ' Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' }, color: '#64748b' }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', size: 11 },
                        color: '#64748b',
                        callback: function(value) {
                            if (value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + ' Jt';
                            if (value >= 1000) return 'Rp ' + (value/1000).toFixed(0) + ' Rb';
                            return 'Rp ' + value;
                        }
                    },
                    grid: { color: '#f1f5f9' }
                }
            }
        }
    });

});
</script>
@endsection