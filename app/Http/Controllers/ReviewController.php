<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Event $event)
    {
        // Validasi input
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $user = auth()->user();

        // 1. Cek apakah user sudah membeli tiket di event ini
        $validStatuses = ['PAID', 'paid', 'success', 'SUCCESS', 'settlement', 'capture'];
        $hasTicket = Transaction::where('event_id', $event->id)
            ->where('customer_email', $user->email)
            ->whereIn('status', $validStatuses)
            ->exists();

        // Izinkan juga jika user adalah admin atau penyelenggara untuk pengujian
        $isStaff = in_array($user->role, ['admin', 'superadmin', 'organizer']);

        if (!$hasTicket && !$isStaff) {
            return back()->with('error', '⚠️ Ulasan dan rating hanya dapat diberikan oleh pembeli sah tiket acara ini.');
        }

        // 2. Cek apakah user sudah pernah memberi ulasan di event ini
        $existingReview = Review::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan dan ulasan untuk acara ini.');
        }

        // 3. Simpan Ulasan
        Review::create([
            'user_id'  => $user->id,
            'event_id' => $event->id,
            'rating'   => $request->rating,
            'comment'  => trim($request->comment),
        ]);

        return back()->with('success', '🌟 Terima kasih! Ulasan & rating Anda telah berhasil dipublikasikan.');
    }
}