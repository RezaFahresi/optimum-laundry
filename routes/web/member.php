<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\PointController;
use App\Http\Controllers\Member\VoucherController;
use App\Http\Controllers\Member\DashboardController;
use App\Http\Controllers\Member\PriceListController;
use App\Http\Controllers\Member\TransactionController;
use App\Http\Controllers\Member\ComplaintSuggestionController;
use App\Http\Controllers\DeveloperController;

Route::get('/', [DashboardController::class, 'index'])->name('index');
Route::get('/price-lists', [PriceListController::class, 'index'])->name('price_lists.index');
Route::get('/points', [PointController::class, 'index'])->name('points.index');
Route::get('/vouchers/redeem/{voucher}', [VoucherController::class, 'store'])->name('vouchers.store');
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
Route::post('/complaint/{transaction}', [TransactionController::class, 'complaint'])->name('transactions.complaint');

Route::get('/complaint-suggestions', [ComplaintSuggestionController::class, 'index'])->name('complaints.index');
Route::post('/complaint-suggestions', [ComplaintSuggestionController::class, 'store'])->name('complaints.store');
Route::get('/transactions-create', [TransactionController::class, 'create'])->name('transactions.create');
Route::get('/transactions-tambah', [TransactionController::class, 'tambah'])->name('member.transactions.tambah');
Route::post('/transactions-store', [TransactionController::class, 'store'])->name('member.transactions.store');
Route::post('/simpanJadwal', [TransactionController::class, 'simpanJadwal'])->name('member.transactions.simpanJadwal');
Route::put('/updateSchedule/{id}', [TransactionController::class, 'updateSchedule'])
    ->name('member.transactions.updateSchedule');

Route::get('/get-price', [TransactionController::class, 'getPrice'])->name('member.transactions.get-price');
Route::post('/transactions-tambah-session', [TransactionController::class, 'tambahKeSession']);
Route::delete('/transactions-hapus-session/{index}', [TransactionController::class, 'hapusSession']);
Route::delete('/hapusSchedule/{id}', [TransactionController::class, 'hapusSchedule']);

Route::get('/developer-bio', [DeveloperController::class, 'showBio'])->name('developer.bio');
