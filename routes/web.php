<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;

Auth::routes();

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/about', function () {
    return view('pages.about');
})->name('about');


Route::view('/privacy-policy', 'pages.privacy')->name('privacy');
Route::view('/terms', 'pages.terms')->name('terms');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:Admin')->group(function () {
        Route::get('/dashboard/users', [DashboardController::class, 'users'])->name('dashboard.users');
        Route::get('/dashboard/logs', [DashboardController::class, 'logs'])->name('dashboard.logs');

        Route::post('/dashboard/users', [DashboardController::class, 'usersStore'])->name('dashboard.users.store');
        Route::patch('/dashboard/users/{userId}', [DashboardController::class, 'usersUpdate'])->name('dashboard.users.update');
        Route::delete('/dashboard/users/{userId}', [DashboardController::class, 'usersDestroy'])->name('dashboard.users.destroy');
    });

    Route::middleware('role:Admin,Employee')->group(function () {
        Route::get('/dashboard/rooms', [DashboardController::class, 'rooms'])->name('dashboard.rooms');
        Route::get('/dashboard/amenities', [DashboardController::class, 'amenities'])->name('dashboard.amenities');
        Route::get('/dashboard/reservations', [DashboardController::class, 'reservations'])->name('dashboard.reservations');
        Route::get('/dashboard/approvals', [DashboardController::class, 'approvals'])->name('dashboard.approvals');

        Route::post('/dashboard/rooms', [DashboardController::class, 'roomsStore'])->name('dashboard.rooms.store');
        Route::patch('/dashboard/rooms/{roomId}', [DashboardController::class, 'roomsUpdate'])->name('dashboard.rooms.update');
        Route::delete('/dashboard/rooms/{roomId}', [DashboardController::class, 'roomsDestroy'])->name('dashboard.rooms.destroy');

        Route::post('/dashboard/amenities', [DashboardController::class, 'amenitiesStore'])->name('dashboard.amenities.store');
        Route::patch('/dashboard/amenities/{amenityId}', [DashboardController::class, 'amenitiesUpdate'])->name('dashboard.amenities.update');
        Route::delete('/dashboard/amenities/{amenityId}', [DashboardController::class, 'amenitiesDestroy'])->name('dashboard.amenities.destroy');

        Route::post('/dashboard/reservations', [DashboardController::class, 'reservationsStore'])->name('dashboard.reservations.store');
        Route::patch('/dashboard/reservations/{reservationId}', [DashboardController::class, 'reservationsUpdate'])->name('dashboard.reservations.update');
        Route::delete('/dashboard/reservations/{reservationId}', [DashboardController::class, 'reservationsDestroy'])->name('dashboard.reservations.destroy');

        Route::post('/dashboard/approvals/{reservationId}/approve', [DashboardController::class, 'approvalsApprove'])->name('dashboard.approvals.approve');
        Route::post('/dashboard/approvals/{reservationId}/reject', [DashboardController::class, 'approvalsReject'])->name('dashboard.approvals.reject');
    });

    Route::middleware('role:Customer')->group(function () {
        Route::get('/dashboard/my-reservations', [DashboardController::class, 'myReservations'])->name('dashboard.my-reservations');
        Route::get('/dashboard/reserve', [DashboardController::class, 'reserve'])->name('dashboard.reserve');
    });
});
