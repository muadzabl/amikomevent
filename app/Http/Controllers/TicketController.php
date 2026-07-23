<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    // Menampilkan halaman "Tiket Saya"
    public function show()
    {
        $user = auth()->user();

        // Ambil semua transaksi berdasarkan email user yang sedang login
        $transactions = Transaction::with('event')
            ->where('customer_email', $user->email)
            ->latest()
            ->get();

        return view('ticket', compact('transactions'));
    }

    // Method untuk Download PDF E-Ticket
    public function downloadPdf($id)
    {
        $user = auth()->user();

        // Cari transaksi berdasarkan ID
        $transaction = Transaction::with(['event'])->findOrFail($id);

        // Pastikan tiket milik user yang sedang login (kecuali admin/superadmin)
        if (!in_array($user->role, ['admin', 'superadmin']) && strtolower($transaction->customer_email) !== strtolower($user->email)) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki wewenang untuk mengunduh tiket ini.');
        }

        // Render tampilan khusus PDF
        $pdf = Pdf::loadView('emails.ticket_pdf', compact('transaction'));

        // Download file PDF
        return $pdf->download('E-Ticket-' . $transaction->order_id . '.pdf');
    }
}