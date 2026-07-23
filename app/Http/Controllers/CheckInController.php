<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckInController extends Controller
{
    /**
     * Halaman Utama QR Scanner untuk Panitia Registrasi Hari-H
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Filter event berdasarkan organizer jika role organizer
        if ($user && $user->role === 'organizer') {
            $organizer = $user->organizer;
            $events = $organizer ? Event::where('organizer_id', $organizer->id)->orderBy('date', 'desc')->get() : collect();
        } else {
            $events = Event::orderBy('date', 'desc')->get();
        }

        // Stats Check-In Hari Ini
        $validStatuses = ['PAID', 'paid', 'success', 'SUCCESS', 'settlement', 'capture'];
        $totalPaidTickets = Transaction::whereIn('status', $validStatuses)->count();
        $totalCheckedIn   = Transaction::whereIn('status', $validStatuses)->where('is_used', true)->count();

        return view('scanner.index', compact('events', 'totalPaidTickets', 'totalCheckedIn'));
    }

    /**
     * Endpoint API / AJAX Process Scan QR Code Ticket
     */
    public function scan(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);

        $orderId = trim($request->order_id);
        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        // 1. TIKET TIDAK DITEMUKAN / PALSU
        if (!$transaction) {
            return response()->json([
                'success' => false,
                'status_code' => 'INVALID',
                'title'   => '⛔ TIKET TIDAK VALID!',
                'message' => 'Kode TRX / QR Code ini tidak terdaftar di sistem AmikomEventHub. Waspada penyusup / tiket palsu!',
            ], 404);
        }

        // 2. TIKET BELUM LUNAS
        $validStatuses = ['PAID', 'paid', 'success', 'SUCCESS', 'settlement', 'capture'];
        if (!in_array($transaction->status, $validStatuses)) {
            return response()->json([
                'success' => false,
                'status_code' => 'UNPAID',
                'title'   => '⚠️ TIKET BELUM LUNAS!',
                'message' => 'Status transaksi: ' . strtoupper($transaction->status) . '. Peserta belum memverifikasi pembayaran.',
                'data'    => [
                    'customer_name' => $transaction->customer_name,
                    'event_title'   => $transaction->event ? $transaction->event->title : '-',
                    'order_id'      => $transaction->order_id,
                ]
            ], 400);
        }

        // 3. TIKET SUDAH PERNAH DIGUNAKAN (DOUBLE ENTRY PROTECTION)
        if ($transaction->is_used) {
            $formattedTime = $transaction->check_in_at 
                ? Carbon::parse($transaction->check_in_at)->format('H:i:s \W\I\B \p\a\d\a d M Y')
                : 'sebelumnya';

            return response()->json([
                'success' => false,
                'status_code' => 'ALREADY_USED',
                'title'   => '🚨 CEGAH DOUBLE ENTRY!',
                'message' => "Tiket ini SUDAH PERNAH DIGUNAKAN untuk masuk pada {$formattedTime}. Akses ditolak!",
                'data'    => [
                    'customer_name' => $transaction->customer_name,
                    'event_title'   => $transaction->event ? $transaction->event->title : '-',
                    'order_id'      => $transaction->order_id,
                    'check_in_at'   => $formattedTime,
                ]
            ], 400);
        }

        // 4. BERHASIL CHECK-IN PERTAMA KALI
        $checkInTime = now();
        $transaction->is_used = true;
        $transaction->check_in_at = $checkInTime;
        $transaction->save();

        // Hitung ulang total check-in
        $totalPaidTickets = Transaction::whereIn('status', $validStatuses)->count();
        $totalCheckedIn   = Transaction::whereIn('status', $validStatuses)->where('is_used', true)->count();

        return response()->json([
            'success'     => true,
            'status_code' => 'SUCCESS',
            'title'       => '✅ CHECK-IN BERHASIL!',
            'message'     => 'Status tiket diubah menjadi USED. Peserta resmi diizinkan masuk.',
            'data'        => [
                'customer_name'    => $transaction->customer_name,
                'customer_email'   => $transaction->customer_email,
                'event_title'      => $transaction->event ? $transaction->event->title : '-',
                'order_id'         => $transaction->order_id,
                'time'             => $checkInTime->format('H:i:s') . ' WIB',
                'total_checked_in' => $totalCheckedIn,
                'total_paid'       => $totalPaidTickets,
            ]
        ]);
    }
}