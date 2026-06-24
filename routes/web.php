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

// ==========================================
// RUTE USER AREA / PUBLIK (Dapat diakses tanpa login)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/my-ticket', [TicketController::class, 'show'])->name('ticket');
Route::get('/bantuan', [HomeController::class, 'bantuan'])->name('bantuan');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/profil', [HomeController::class, 'profil'])->name('profil');
Route::get('/katalog', [HomeController::class, 'katalog'])->name('katalog');

// Rute Integrasi Checkout & Midtrans Payment Gateway (Pertemuan 10 & 11)
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');


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

        // Laporan Transaksi Admin (Pertemuan 10)
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    });
});