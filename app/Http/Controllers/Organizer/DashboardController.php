<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Pastikan user memiliki data profil organizer
        $organizer = $user->organizer;

        if (!$organizer) {
            abort(403, 'Profil kepanitiaan/organizer Anda belum terdaftar.');
        }

        // 1. Ambil Event khusus milik Organizer ini saja
        $events = Event::where('organizer_id', $organizer->id)->latest()->get();
        $totalEvents = $events->count();

        // 2. Hitung Total Tiket Terjual khusus event milik Organizer ini
        $totalTicketsSold = Transaction::whereHas('event', function ($query) use ($organizer) {
            $query->where('organizer_id', $organizer->id);
        })->where('status', 'PAID')->count();

        // 3. Analitik Total Pendapatan Khusus HIMA Ini
        $totalRevenue = Transaction::whereHas('event', function ($query) use ($organizer) {
            $query->where('organizer_id', $organizer->id);
        })->where('status', 'PAID')->sum('total_amount');

        // 4. Daftar Transaksi Terakhir
        $recentTransactions = Transaction::whereHas('event', function ($query) use ($organizer) {
            $query->where('organizer_id', $organizer->id);
        })->with('event')->latest()->take(5)->get();

        return view('organizer.dashboard', compact(
            'organizer',
            'events',
            'totalEvents',
            'totalTicketsSold',
            'totalRevenue',
            'recentTransactions'
        ));
    }
}