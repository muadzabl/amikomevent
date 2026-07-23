<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use App\Models\Event;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    /**
     * Tampilkan Halaman Profil Penyelenggara / HIMA / UKM beserta Rekam Jejak Ulasan
     */
    public function show(Organizer $organizer)
    {
        $organizer->load(['events.category']);

        // Dapatkan semua event yang diselenggarakan oleh HIMA / UKM ini
        $events = $organizer->events()->orderBy('date', 'desc')->get();
        
        // Filter Acara Mendatang & Acara Lampau
        $upcomingEvents = $events->filter(fn($e) => $e->date ? \Carbon\Carbon::parse($e->date)->isFuture() : true);
        $pastEvents     = $events->filter(fn($e) => $e->date ? \Carbon\Carbon::parse($e->date)->isPast() : false);

        // Hitung total tiket terjual oleh penyelenggara ini
        $eventIds = $events->pluck('id');
        $validStatuses = ['PAID', 'paid', 'success', 'SUCCESS', 'settlement', 'capture'];
        $totalTicketsSold = Transaction::whereIn('event_id', $eventIds)
            ->whereIn('status', $validStatuses)
            ->count();

        // Dapatkan semua ulasan & testimoni dari acara-acara penyelenggara ini
        $reviews = Review::with(['user', 'event'])
            ->whereIn('event_id', $eventIds)
            ->latest()
            ->paginate(10);

        // Breakdown Statistik Distribusi Bintang (1-5)
        $allReviews = Review::whereIn('event_id', $eventIds)->get();
        $totalReviewsCount = $allReviews->count();

        $ratingCounts = [
            5 => $allReviews->where('rating', 5)->count(),
            4 => $allReviews->where('rating', 4)->count(),
            3 => $allReviews->where('rating', 3)->count(),
            2 => $allReviews->where('rating', 2)->count(),
            1 => $allReviews->where('rating', 1)->count(),
        ];

        return view('organizer-profile', compact(
            'organizer', 'events', 'upcomingEvents', 'pastEvents', 
            'totalTicketsSold', 'reviews', 'ratingCounts', 'totalReviewsCount'
        ));
    }
}
