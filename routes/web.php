<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\HostController;
use Illuminate\Support\Facades\Route;

// Landing Page Route (Default)
Route::get('/', [LandingPageController::class, 'index'])->name('landing.beranda');
Route::get('/beranda', [LandingPageController::class, 'index']);
Route::get('/inovasi', [LandingPageController::class, 'inovasi'])->name('landing.inovasi');
Route::get('/fitur-utama', [LandingPageController::class, 'fiturUtama'])->name('landing.fitur-utama');
Route::get('/kontak', [LandingPageController::class, 'kontak'])->name('landing.kontak');

Route::get('/auth/google', [LoginController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

// Complete Profile for Google users
Route::get('/auth/complete-profile', [LoginController::class, 'showCompleteProfile'])->name('auth.complete-profile');
Route::post('/auth/complete-profile', [LoginController::class, 'completeProfile'])->name('auth.complete-profile.submit');

// Registration routes
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.submit');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::middleware('auth')->group(function () {
    Route::get('/home', [StationController::class, 'index']);
    Route::get('/stations/{id}', [StationController::class, 'show'])->name('stations.show');
    Route::get('/my-bookings', [BookingController::class, 'index']);
    Route::view('/profile', 'profile.show')->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/book', [BookingController::class, 'store']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::prefix('host')->middleware('host')->group(function () {
        Route::get('/dashboard', [HostController::class, 'index']);
        Route::get('/create', [HostController::class, 'create']);
        Route::post('/store', [HostController::class, 'store']);
    });

    // Admin routes - only accessible by admin role
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/add-station', [App\Http\Controllers\AdminController::class, 'addStation'])->name('admin.add-station');
        Route::get('/create-station', [App\Http\Controllers\AdminController::class, 'createStation'])->name('admin.create-station');
        Route::post('/create-station', [App\Http\Controllers\AdminController::class, 'storeStation'])->name('admin.store-station');
        Route::get('/edit-station/{id}', [App\Http\Controllers\AdminController::class, 'editStation'])->name('admin.edit-station');
        Route::put('/update-station/{id}', [App\Http\Controllers\AdminController::class, 'updateStation'])->name('admin.update-station');
        Route::delete('/delete-station/{id}', [App\Http\Controllers\AdminController::class, 'deleteStation'])->name('admin.delete-station');
        Route::get('/user-management', [App\Http\Controllers\AdminController::class, 'userManagement'])->name('admin.user-management');
        Route::get('/create-user', [App\Http\Controllers\AdminController::class, 'createUser'])->name('admin.create-user');
        Route::post('/create-user', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('admin.store-user');
        Route::get('/edit-user/{id}', [App\Http\Controllers\AdminController::class, 'editUser'])->name('admin.edit-user');
        Route::put('/update-user/{id}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('admin.update-user');
        Route::delete('/delete-user/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.delete-user');
    });

    // Driver routes - only accessible by driver role
    Route::prefix('driver')->middleware('role:driver')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\DriverController::class, 'index'])->name('driver.dashboard');
        Route::get('/reservations', [App\Http\Controllers\DriverController::class, 'reservations'])->name('driver.reservations');
        Route::get('/invoice', [App\Http\Controllers\DriverController::class, 'invoice'])->name('driver.invoice');
        Route::get('/station/{id}', [\App\Http\Controllers\DriverController::class, 'showStation'])->name('driver.station.show');
        
        // Profile routes
        Route::get('/profile', [App\Http\Controllers\DriverController::class, 'profile'])->name('driver.profile');
        Route::get('/profile/edit', [App\Http\Controllers\DriverController::class, 'editProfile'])->name('driver.profile.edit');
        Route::put('/profile/update', [App\Http\Controllers\DriverController::class, 'updateProfile'])->name('driver.profile.update');
        Route::get('/profile/password', [App\Http\Controllers\DriverController::class, 'showPasswordForm'])->name('driver.profile.password');
        Route::put('/profile/password/update', [App\Http\Controllers\DriverController::class, 'updatePassword'])->name('driver.profile.password.update');
    });

    // Owner routes - only accessible by warga role
    Route::prefix('owner')->middleware('role:warga')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\OwnerController::class, 'index'])->name('owner.dashboard');
        Route::get('/profile', [App\Http\Controllers\OwnerController::class, 'profile'])->name('owner.profile');
        Route::get('/transaction/{id}', [App\Http\Controllers\OwnerController::class, 'showTransactionDetail'])->name('owner.transaction.detail');
    });
});
