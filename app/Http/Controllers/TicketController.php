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
        // Cari transaksi berdasarkan email user yang sedang login
        $transaction = Transaction::with('event')
            ->where('customer_email', auth()->user()->email)
            ->latest()
            ->first();

        // Jika belum ada transaksi dengan email tersebut, 
        // ambil transaksi terakhir di database agar halaman tetap bisa di-test
        if (!$transaction) {
            $transaction = Transaction::with('event')->latest()->first();
        }

        // Jika database benar-benar kosong
        if (!$transaction) {
            abort(404, 'Belum ada data transaksi untuk ditampilkan.');
        }

        // Kirim variabel $transaction (singular) ke view ticket.blade.php
        return view('ticket', compact('transaction'));
    }

    // Method untuk Download PDF E-Ticket
    public function downloadPdf($id)
    {
        // Cari transaksi berdasarkan ID
        $transaction = Transaction::with(['event'])->findOrFail($id);

        // Render tampilan khusus PDF
        $pdf = Pdf::loadView('emails.ticket_pdf', compact('transaction'));
        
        // Download file PDF
        return $pdf->download('E-Ticket-' . $transaction->order_id . '.pdf');
    }
}