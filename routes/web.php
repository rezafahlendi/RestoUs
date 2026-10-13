<?php

use App\Http\Controllers\AdminReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerReservationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - RestoUs (Sistem Pemesanan Restoran Sederhana)
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. AUTENTIKASI (Login, Register, Google Demo, Logout)
// ==========================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/auth/google', [AuthController::class, 'loginGoogleDemo'])->name('auth.google-demo');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// 2. RUTE PUBLIK CUSTOMER (Bisa diakses tanpa login)
// ==========================================
Route::get('/', [CustomerReservationController::class, 'home'])->name('customer.home');
Route::get('/menu', [CustomerReservationController::class, 'menu'])->name('customer.menu');
Route::get('/rules', [CustomerReservationController::class, 'rules'])->name('customer.rules');

// ==========================================
// 3. RUTE CUSTOMER TERPROTEKSI (Wajib Login Customer)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/booking', [CustomerReservationController::class, 'bookingCreate'])->name('customer.booking.create');
    Route::get('/booking/check-availability', [CustomerReservationController::class, 'checkAvailability'])->name('customer.booking.check');
    Route::post('/booking', [CustomerReservationController::class, 'bookingStore'])->name('customer.booking.store');
    Route::get('/booking/{id}', [CustomerReservationController::class, 'bookingShow'])->name('customer.booking.show');
    Route::get('/booking/{id}/invoice', [CustomerReservationController::class, 'invoiceDownload'])->name('customer.booking.invoice');
    Route::get('/my-reservations', [CustomerReservationController::class, 'myReservations'])->name('customer.my-reservations');
});

// ==========================================
// 4. RUTE SUPER ADMIN TERPROTEKSI (Wajib Login & Role Admin)
// ==========================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.reservations.index');
    });
    Route::get('/reservations', [AdminReservationController::class, 'reservations'])->name('reservations.index');
    Route::patch('/reservations/{id}/status', [AdminReservationController::class, 'updateStatus'])->name('reservations.update-status');
    Route::get('/menus', [AdminReservationController::class, 'menus'])->name('menus.index');
    Route::put('/menus/{id}', [AdminReservationController::class, 'updateMenu'])->name('menus.update');
    Route::get('/tables', [AdminReservationController::class, 'tables'])->name('tables.index');
});
