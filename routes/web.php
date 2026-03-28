<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin', function () {
        return 'Admin only page';
    })->middleware('role:admin')->name('admin.layouts.app');

    Route::get('/bookings/manage', function () {
        return 'Manage bookings page';
    })->middleware('permission:manage bookings')->name('bookings.manage');
});

require __DIR__.'/auth.php';

