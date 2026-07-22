<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckInController extends Controller
{
    // Halaman Kamera Scanner
    public function index()
    {
        return view('scanner.index');
    }

    // Endpoint API/AJAX untuk Validasi QR Code yang di-scan
    public function scan(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);

        $transaction = Transaction::with('event')->where('order_id', $request->order_id)->first();

        // 1. Jika Tiket Tidak Ditemukan
        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => '❌ TIKET TIDAK VALID / TIDAK DITEMUKAN!'
            ], 404);
        }

        // 2. Jika Tiket Belum Lunas
        if (strtolower($transaction->status) !== 'success' && strtolower($transaction->status) !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => '⚠️ TIKET BELUM LUNAS (Status: ' . strtoupper($transaction->status) . ')'
            ], 400);
        }

        // 3. Jika Tiket Sudah Pernah Dipakai Check-in (Cegah Double Entry)
        if ($transaction->is_used) {
            $time = Carbon::parse($transaction->check_in_at)->format('d M Y, H:i');
            return response()->json([
                'success' => false,
                'message' => "🚨 TIKET SUDAH PERNAH DIGUNAKAN pada {$time}!"
            ], 400);
        }

        // 4. BERHASIL CHECK-IN
        $transaction->update([
            'is_used' => true,
            'check_in_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => '✅ CHECK-IN BERHASIL! Silakan Masuk.',
            'data' => [
                'customer_name' => $transaction->customer_name,
                'event_title'   => $transaction->event ? $transaction->event->title : '-',
                'order_id'      => $transaction->order_id,
                'time'          => now()->format('H:i:s WIB'),
            ]
        ]);
    }
}