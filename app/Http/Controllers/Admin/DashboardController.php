<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Organizer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Menjumlahkan semua nominal total_price dari kolom Transaksi Lunas
        $totalRevenue = Transaction::whereIn('status', ['settlement', 'success'])->sum('total_price');
        
        // 2. Menghitung Berapa orang tamu yang tiketnya sudah Lunas
        $ticketsSold = Transaction::whereIn('status', ['settlement', 'success'])->count();
        
        // 3. Menghitung Jumlah Acara Mendatang yang aktif diselenggarakan
        $activeEvents = Event::where('date', '>=', now())->count();
        $totalEvents = Event::count();
        
        // 4. Menghitung Transaksi Ngadat (Status belum dibayar pelanggan / Expired)
        $pendingOrders = Transaction::where('status', 'pending')->count();
        
        // 5. Total Pengguna & Total Partner Organisasi
        $totalUsers = User::count();
        $totalPartners = Organizer::count();

        // 6. Menyertakan 5 daftar riwayat pesanan (History) paling mutakhir di panel
        $recentTransactions = Transaction::with('event')->latest()->take(5)->get();

        // =========================================================
        // DATA GRAFIK PERTUMBUHAN (6 BULAN TERAKHIR)
        // =========================================================
        $months = [];
        $userGrowthData = [];
        $eventGrowthData = [];
        $revenueData = [];

        // Loop 6 bulan terakhir hingga bulan ini
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('M Y');
            $year = $date->year;
            $month = $date->month;

            $months[] = $monthName;

            // Pertumbuhan Registrasi Pengguna per Bulan
            $userCount = User::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            $userGrowthData[] = $userCount;

            // Pertumbuhan Event diselenggarakan per Bulan
            $eventCount = Event::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            $eventGrowthData[] = $eventCount;

            // Pendapatan per Bulan
            $rev = Transaction::whereIn('status', ['settlement', 'success'])
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('total_price');
            $revenueData[] = (int) $rev;
        }

        // Kalkulasi Pertumbuhan Bulan Ini vs Bulan Lalu (%)
        $usersThisMonth = end($userGrowthData);
        $usersLastMonth = $userGrowthData[4] ?? 0;
        $userGrowthPct = $usersLastMonth > 0 
            ? round((($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100, 1)
            : ($usersThisMonth > 0 ? 100 : 0);

        $eventsThisMonth = end($eventGrowthData);
        $eventsLastMonth = $eventGrowthData[4] ?? 0;
        $eventGrowthPct = $eventsLastMonth > 0 
            ? round((($eventsThisMonth - $eventsLastMonth) / $eventsLastMonth) * 100, 1)
            : ($eventsThisMonth > 0 ? 100 : 0);

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'totalEvents',
            'pendingOrders',
            'totalUsers',
            'totalPartners',
            'recentTransactions',
            'months',
            'userGrowthData',
            'eventGrowthData',
            'revenueData',
            'userGrowthPct',
            'eventGrowthPct'
        ));
    }
}