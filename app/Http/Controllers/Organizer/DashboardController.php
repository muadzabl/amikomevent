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
            abort(403, 'Profil kepanitiaan/organizer Anda belum terdaftar. Hubungi Admin.');
        }

        // 1. Ambil Event khusus milik Organizer ini saja
        $events = Event::where('organizer_id', $organizer->id)->latest()->get();
        $totalEvents = $events->count() ?? 0;

        $paidStatuses = ['PAID', 'paid', 'success', 'SUCCESS', 'settlement', 'capture'];

        // 2. Hitung Total Tiket Terjual
        $totalTicketsSold = (int) Transaction::whereHas('event', function ($query) use ($organizer) {
            $query->where('organizer_id', $organizer->id);
        })->whereIn('status', $paidStatuses)->count();

        // 3. Total Pendapatan
        $totalRevenue = (float) Transaction::whereHas('event', function ($query) use ($organizer) {
            $query->where('organizer_id', $organizer->id);
        })->whereIn('status', $paidStatuses)->sum('total_price');

        // 4. Daftar Semua Transaksi Pembeli Tiket
        $transactions = Transaction::whereHas('event', function ($query) use ($organizer) {
            $query->where('organizer_id', $organizer->id);
        })->with('event')->latest()->get();

        return view('organizer.dashboard', compact(
            'organizer',
            'events',
            'totalEvents',
            'totalTicketsSold',
            'totalRevenue',
            'transactions'
        ));
    }
}