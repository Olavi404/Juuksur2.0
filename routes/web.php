<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookingController::class, 'create'])->name('bookings.create');
Route::get('/available-times', [BookingController::class, 'availableTimes'])->name('bookings.available-times');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/admin/bookings', [AdminBookingController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.bookings.index');
