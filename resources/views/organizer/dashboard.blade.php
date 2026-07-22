<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Organizer - {{ $organizer->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 font-sans">
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <header class="bg-indigo-700 text-white shadow-md">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-black">{{ $organizer->name }}</h1>
                    <p class="text-xs text-indigo-200">Panel Kelola Acara & Analitik Kepanitiaan</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="bg-indigo-800 text-xs px-3 py-1 rounded-full border border-indigo-500">Role: Organizer</span>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-xs font-bold transition">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto w-full px-6 py-8 flex-1">
            
            <!-- Cards Analitik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Acara -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Acara Diselenggarakan</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $totalEvents }} <span class="text-sm font-normal text-slate-500">Event</span></h3>
                </div>

                <!-- Total Tiket Terjual -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Total Tiket Terjual</p>
                    <h3 class="text-3xl font-black text-indigo-600">{{ $totalTicketsSold }} <span class="text-sm font-normal text-slate-500">Tiket</span></h3>
                </div>

                <!-- Analitik Pendapatan -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Total Pendapatan HIMA</p>
                    <h3 class="text-3xl font-black text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
            </div>

            <!-- Tabel Event Milik HIMA Ini -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-slate-800">Daftar Acara Kepanitiaan</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-400 font-semibold border-b">
                            <tr>
                                <th class="p-4">Nama Acara</th>
                                <th class="p-4">Tanggal</th>
                                <th class="p-4">Harga Tiket</th>
                                <th class="p-4">Lokasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($events as $event)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-4 font-bold text-slate-800">{{ $event->title }}</td>
                                    <td class="p-4">{{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}</td>
                                    <td class="p-4 font-bold text-indigo-600">Rp {{ number_format($event->price, 0, ',', '.') }}</td>
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

        </main>
    </div>
</body>
</html>