<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Event $event)
    {
        // Validasi input
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        // Cek apakah user sudah pernah memberi ulasan di event ini
        $existingReview = Review::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk acara ini.');
        }

        // Simpan ulasan
        Review::create([
            'user_id'  => auth()->id(),
            'event_id' => $event->id,
            'rating'   => $request->rating,
            'comment'  => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan dan penilaian Anda!');
    }
}