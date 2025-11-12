<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\ProfilePasswordController;
use App\Http\Controllers\Profile\ProfilePhotoController;
use App\Http\Controllers\Member\TransactionController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DeveloperController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', function () {
    return view('landing');
})->middleware('language');

Route::get('/developer-bio', [DeveloperController::class, 'showBio'])->name('developer.bio');

Route::get('runSchedules', [ScheduleController::class, 'runSchedules'])->name('runSchedules');

// ======================
// Auth routes
// ======================
Route::middleware('language')->group(function () {
    Route::get('login', [LoginController::class, 'show'])->name('login.show')->middleware('islogin');
    Route::post('login', [LoginController::class, 'authenticate'])->name('login.authenticate');
    Route::get('logout', [LoginController::class, 'logout'])->name('login.logout');

    Route::get('register', [RegisterController::class, 'show'])->name('register.show');
    Route::post('register', [RegisterController::class, 'register'])->name('register.register');

    Route::get('register-admin', [RegisterController::class, 'registerAdminView'])->name('register.admin');
    Route::post('register-admin', [RegisterController::class, 'registerAdmin'])->name('register.register_admin');
});

// ======================
// Profile routes
// ======================
Route::prefix('profile')->middleware(['language', 'islogin'])->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/photo/delete', [ProfilePhotoController::class, 'destroy'])->name('profile.photo.destroy');
    Route::patch('/password', [ProfilePasswordController::class, 'update'])->name('profile.password.update');
});

// ======================
// Set Language route
// ======================
Route::get('/{locale}', LocaleController::class);

// ======================
// Member-only routes
// ======================
Route::prefix('member')->middleware(['language', 'auth', 'role:member'])->name('member.')->group(function () {
    //Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    //Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
    //Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
});

// // ======================
// // Debug route untuk cek role
// // ======================
// Route::get('/cek-role', function () {
//     return Auth::check() ? Auth::user()->role : 'Belum login';
// })->middleware('auth');

