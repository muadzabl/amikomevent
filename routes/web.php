<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CheckInController;

// ==========================================
// RUTE USER AREA / PUBLIK (Dapat diakses tanpa login)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/bantuan', [HomeController::class, 'bantuan'])->name('bantuan');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/profil', [HomeController::class, 'profil'])->name('profil');
Route::get('/katalog', [HomeController::class, 'katalog'])->name('katalog');

// RUTE LOGIN GOOGLE SOCIALITE 
Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

// Rute Integrasi Checkout & Midtrans Payment Gateway
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');


// ==========================================
// RUTE USER TERAUTENTIKASI (WAJIB LOGIN)
// ==========================================
Route::middleware(['auth'])->group(function () {
    // Fitur Tiket Saya & Download PDF Tiket
    Route::get('/my-ticket', [TicketController::class, 'show'])->name('ticket');
    Route::get('/ticket/download/{id}', [TicketController::class, 'downloadPdf'])->name('ticket.download');

    // Fitur Rating & Ulasan
    Route::post('/events/{event}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});


// ==========================================
// RUTE ADMIN AREA (Melalui Panel Admin)
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        if (auth()->check()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    });

    // Otentikasi Admin
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Area Admin Terproteksi (Wajib Login Admin)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Manajemen Data Master
        Route::resource('events', EventAdminController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('partners', PartnerController::class);

        // Laporan Transaksi Admin
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');

        // Fitur Check-in Tiket via Scan QR Code
        Route::get('scan-ticket', [TransactionController::class, 'scanIndex'])->name('tickets.scan');
        Route::post('checkin-ticket', [TransactionController::class, 'processCheckin'])->name('tickets.checkin');
    });
});

// ==========================================
// WEBHOOK WEB / MIDTRANS CALLBACK (Wajib di Luar Prefix Admin)
// ==========================================
Route::post('/midtrans/callback', [MidtransWebhookController::class, 'handle']);

// ==========================================
// RUTE DASHBOARD ORGANIZER / HIMA (Multi-Tenant)
// ==========================================
Route::middleware(['auth', 'role:organizer'])->prefix('organizer')->name('organizer.')->group(function () {
    // Dashboard Analitik Pendapatan HIMA/UKM
    Route::get('/dashboard', [\App\Http\Controllers\Organizer\DashboardController::class, 'index'])->name('dashboard');
});

// ==========================================
// RUTE SCANNER CHECK-IN PENJAGA PINTU
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/scan-ticket', [CheckInController::class, 'index'])->name('scan.index');
    Route::post('/scan-ticket/process', [CheckInController::class, 'scan'])->name('scan.process');
});

// ==========================================
// RUTE SUPERADMIN (Pengawasan Kelayakan Penyelenggara)
// ==========================================
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', function () {
        $organizers = \App\Models\Organizer::with('user')->get();
        $totalEvents = \App\Models\Event::count();
        $totalRevenueAll = \App\Models\Transaction::where('status', 'PAID')->sum('total_amount');

        return view('superadmin.dashboard', compact('organizers', 'totalEvents', 'totalRevenueAll'));
    })->name('dashboard');
});