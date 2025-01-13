<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AuthController; // Gunakan AuthController baru
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect default route ke landing page
Route::get('/', function () {
    if (Auth::check()) {
        switch (Auth::user()->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'penjual':
                return redirect()->route('seller.dashboard');
            case 'pembeli':
                return redirect()->route('buyer.dashboard');
        }
    }
    return redirect('/landing');
});


// ** Guest Routes **
Route::get('/landing', [LandingController::class, 'index']);

// ** Auth Routes **
Route::controller(AuthController::class)->group(function () {
    // Register & Login Routes
    Route::get('/register', 'showRegisterForm')->name('register')->middleware('guest');
    Route::post('/register', 'register')->middleware('guest');
    Route::get('/login', 'showLoginForm')->name('login')->middleware('guest');
    Route::post('/login', 'login')->middleware('guest');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');

    // Email Verification
    Route::get('/verify-email/{id}', 'verifyEmail')->name('verify.email');

    // Password Reset Routes
    Route::get('/forgot-password', 'showForgotPasswordForm')->name('password.request')->middleware('guest');
    Route::post('/forgot-password', 'sendResetLinkEmail')->name('password.email')->middleware('guest');
    Route::get('/reset-password/{token}', function ($token) {
        $email = request('email'); // Ambil email dari query string
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email, // Pastikan email disertakan
        ]);
    })->middleware('guest')->name('password.reset.form');
    Route::post('/reset-password', 'resetPassword')->name('password.update')->middleware('guest');

    // QR Login Route
    Route::get('/qr-login/{token}', 'qrLogin')->name('qr.login')->middleware('guest');
});

// Dashboard Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

// Dashboard Penjual
Route::middleware(['auth', 'role:penjual'])->group(function () {
    Route::get('/seller/dashboard', [SellerController::class, 'index'])->name('seller.dashboard');
});

// Dashboard Pembeli
Route::middleware(['auth', 'role:pembeli'])->group(function () {
    Route::get('/buyer/dashboard', [BuyerController::class, 'index'])->name('buyer.dashboard');
});


// // ** Dashboard (Admin & Customer) **
// Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');

// // ** Admin Routes **
// Route::middleware(['auth', 'admin'])->group(function () {
//     // Tabel Routes
//     Route::resource('/table/profile', ProfileController::class);
//     Route::resource('/table/barang', BarangController::class);

//     // Transaksi Routes
//     Route::resource('/transaction/order', OrderController::class);
//     Route::resource('/transaction/checkout', PesananController::class);
// });

// // ** Customer Routes **
// Route::middleware(['auth', 'customer'])->group(function () {
//     // Order Routes
//     Route::get('/order', [CheckOutController::class, 'index']);
//     Route::get('/order/create', [CheckOutController::class, 'create']);
//     Route::post('/order', [KatalogController::class, 'store']);
//     Route::post('/order/store', [CheckOutController::class, 'store']);
//     Route::put('/order/update', [CheckOutController::class, 'update']);
//     Route::get('/order/destroy', [OrderController::class, 'destroy']);

//     // Wishlist Routes
//     Route::get('/wishlist', [WishlistController::class, 'index']);
//     Route::get('/wishlist/create', [WishlistController::class, 'create']);
// });

// // Middleware untuk Member (Customer yang sudah punya membership)
// Route::middleware(['auth', 'member'])->group(function () {
//     Route::get('/wishlist', [WishlistController::class, 'index']);
//     Route::get('/wishlist/create', [WishlistController::class, 'create']);
// });
