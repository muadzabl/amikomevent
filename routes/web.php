<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\Organizer\AuthController as OrganizerAuthController;
use App\Http\Controllers\Organizer\DashboardController as OrganizerDashboardController;

// ==========================================
// [SEMENTARA] RESET PASSWORD - HAPUS SETELAH DIPAKAI
// ==========================================
Route::get('/reset-akun-temp-x7k2', function () {
    $users = [
        ['email' => 'hmssi@amikom.ac.id',   'role' => 'organizer', 'password' => 'password123'],
        ['email' => 'admin@amikom.ac.id',    'role' => 'admin',     'password' => 'password'],
    ];
    $results = [];
    foreach ($users as $u) {
        $user = \App\Models\User::where('email', $u['email'])->first();
        if ($user) {
            $user->password = bcrypt($u['password']);
            $user->role = $u['role'];
            $user->save();
            $results[] = "✅ {$u['email']} → password: {$u['password']} | role: {$u['role']}";
        } else {
            $user = \App\Models\User::create([
                'name'     => ucfirst(explode('@', $u['email'])[0]),
                'email'    => $u['email'],
                'password' => bcrypt($u['password']),
                'role'     => $u['role'],
            ]);
            $results[] = "✅ [BARU] {$u['email']} → password: {$u['password']} | role: {$u['role']}";
        }

        // Buat profil organizer jika belum ada
        if ($u['role'] === 'organizer' && $user) {
            $existing = \App\Models\Organizer::where('user_id', $user->id)->first();
            if (!$existing) {
                \App\Models\Organizer::create([
                    'user_id'     => $user->id,
                    'name'        => 'HMSSI Amikom',
                    'description' => 'Himpunan Mahasiswa Sistem & Sains Informasi Amikom',
                    'is_verified' => true,
                ]);
                $results[] = "✅ Profil organizer HMSSI dibuat!";
            } else {
                $existing->is_verified = true;
                $existing->save();
                $results[] = "✅ Profil organizer sudah ada (is_verified=true)";
            }
        }
    }
    return '<pre style="font-size:16px;padding:20px;">'.implode("\n", $results)."\n\n⚠️ HAPUS ROUTE INI SETELAH SELESAI!</pre>";
});

// ==========================================
// RUTE USER / CUSTOMER AREA - PUBLIK
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/bantuan', [HomeController::class, 'bantuan'])->name('bantuan');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/katalog', [HomeController::class, 'katalog'])->name('katalog');
Route::get('/organizers/{organizer}', [\App\Http\Controllers\OrganizerController::class, 'show'])->name('organizers.show');

// ==========================================
// JALUR 1: CUSTOMER / USER BIASA - Login & Logout
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.customer-login');
    })->name('login');

    Route::post('/login', function (\Illuminate\Http\Request $request) {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = auth()->user()->role;

            // Redirect berdasarkan role setelah login
            if (in_array($role, ['admin', 'superadmin'])) {
                return redirect()->route('admin.dashboard');
            }
            if ($role === 'organizer') {
                return redirect()->route('partner.dashboard');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau Password yang Anda masukkan salah.',
        ])->onlyInput('email');
    })->name('login.post');
});

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout')->middleware('auth');

// Google Socialite OAuth
Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

// Checkout & Midtrans Payment Gateway
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');

// ==========================================
// RUTE USER TERAUTENTIKASI (WAJIB LOGIN)
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/my-ticket', [TicketController::class, 'show'])->name('ticket');
    Route::get('/ticket/download/{id}', [TicketController::class, 'downloadPdf'])->name('ticket.download');
    Route::get('/profil', [HomeController::class, 'profil'])->name('profil');
    Route::post('/events/{event}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// ==========================================
// JALUR 2: PARTNER / ORGANIZER (HMSSI/HIMA)
// Route prefix: /partner  — named: partner.*
// ==========================================
Route::prefix('partner')->name('partner.')->group(function () {

    // Redirect /partner ke login jika belum login
    Route::get('/', function () {
        if (auth()->check() && auth()->user()->role === 'organizer') {
            return redirect()->route('partner.dashboard');
        }
        return redirect()->route('partner.login');
    });

    // Auth - tidak perlu login
    Route::middleware('guest')->group(function () {
        Route::get('/login', [OrganizerAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [OrganizerAuthController::class, 'login'])->name('login.post');
    });

    Route::post('/logout', [OrganizerAuthController::class, 'logout'])->name('logout')->middleware('auth');

    // Area Terproteksi Partner
    Route::middleware(['organizer'])->group(function () {
        Route::get('/dashboard', [OrganizerDashboardController::class, 'index'])->name('dashboard');
    });
});

// Backward compat: /organizer/dashboard → /partner/dashboard
Route::get('/organizer/dashboard', function () {
    if (auth()->check() && auth()->user()->role === 'organizer') {
        return redirect()->route('partner.dashboard');
    }
    return redirect()->route('partner.login');
})->name('organizer.dashboard');

// ==========================================
// JALUR 3: ADMIN AREA (Panel Admin)
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        if (auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    });

    // Auth Admin - tidak perlu login
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout')->middleware('auth');

    // Area Admin Terproteksi (Wajib Login Admin)
    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Manajemen Data Master
        Route::resource('events', EventAdminController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('partners', PartnerController::class);

        // Laporan Transaksi Admin
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

        // Fitur Check-in Tiket via Scan QR Code
        Route::get('/scan-ticket', [TransactionController::class, 'scanIndex'])->name('tickets.scan');
        Route::post('/checkin-ticket', [TransactionController::class, 'processCheckin'])->name('tickets.checkin');
    });
});

// ==========================================
// WEBHOOK MIDTRANS (Di luar prefix admin)
// ==========================================
Route::post('/midtrans/callback', [MidtransWebhookController::class, 'handle']);

// ==========================================
// SCANNER CHECK-IN (Untuk Penjaga Pintu)
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/scan-ticket', [CheckInController::class, 'index'])->name('scan.index');
    Route::post('/scan-ticket/process', [CheckInController::class, 'scan'])->name('scan.process');
});

// ==========================================
// SUPERADMIN (Pengawasan Global)
// ==========================================
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', function () {
        $organizers      = \App\Models\Organizer::with('user')->get();
        $totalEvents     = \App\Models\Event::count();
        $totalRevenueAll = \App\Models\Transaction::whereIn('status', ['PAID', 'paid', 'success', 'SUCCESS', 'settlement', 'capture'])->sum('total_price');

        return view('superadmin.dashboard', compact('organizers', 'totalEvents', 'totalRevenueAll'));
    })->name('dashboard');
});