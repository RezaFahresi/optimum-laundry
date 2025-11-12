<?php

use App\Http\Controllers\Admin\ComplaintSuggestionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\PengeluaranController;
use App\Http\Controllers\Admin\PriceListController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceTypeController;
use App\Http\Controllers\admin\StokController;
use App\Http\Controllers\Admin\Transaction\PrintTransactionController;
use App\Http\Controllers\Admin\Transaction\TransactionController;
use App\Http\Controllers\Admin\Transaction\TransactionSessionController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\Admin\VoucherController;
use Illuminate\Support\Facades\Route;




Route::get('/', [DashboardController::class, 'index'])->name('index');

Route::group([
    'prefix' => 'transactions',
    'as' => 'transactions.',
], function () {
    Route::get('/create', [TransactionController::class, 'create'])->name('create');
    Route::get('/', [TransactionController::class, 'index'])->name('index');
    Route::post('/', [TransactionController::class, 'store'])->name('store');
    Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
    Route::patch('/{transaction}', [TransactionController::class, 'update'])->name('update');

    Route::post('/session', [TransactionSessionController::class, 'store'])->name('session.store');
    Route::get('/session/{rowId}', [TransactionSessionController::class, 'destroy'])->name('session.destroy');

    Route::get('/print/{transaction}', [PrintTransactionController::class, 'index'])->name('print.index');
});

Route::group([
    'prefix' => 'price-lists',
    'as' => 'price-lists.',
], function () {
    Route::get('/', [PriceListController::class, 'index'])->name('index');
    Route::post('/', [PriceListController::class, 'store'])->name('store');
    Route::get('/{priceList}', [PriceListController::class, 'show'])->name('show');
    Route::patch('/{priceList}', [PriceListController::class, 'update'])->name('update');
});

Route::post('/items', [ItemController::class, 'store'])->name('items.store');

Route::post('/services', [ServiceController::class, 'store'])->name('services.store');

Route::get('/members', [MemberController::class, 'index'])->name('members.index');
Route::delete('/memberhapus/{id}', [MemberController::class, 'destroy'])->name('members.destroy');

Route::group([
    'prefix' => 'vouchers',
    'as' => 'vouchers.',
], function () {
    Route::get('/', [VoucherController::class, 'index'])->name('index');
    Route::post('/', [VoucherController::class, 'store'])->name('store');
    Route::patch('/{voucher}', [VoucherController::class, 'update'])->name('update');
});

Route::group([
    'prefix' => 'complaint-suggestions',
    'as' => 'complaint-suggestions.',
], function () {
    Route::get('/', [ComplaintSuggestionController::class, 'index'])->name('index');
    Route::get('/{complaintSuggestion}', [ComplaintSuggestionController::class, 'show'])->name('show');
    Route::patch('/{complaintSuggestion}', [ComplaintSuggestionController::class, 'update'])->name('update');
});

Route::group([
    'prefix' => 'reports',
    'as' => 'reports.',
], function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::post('/print', [ReportController::class, 'print'])->name('print');
    Route::post('/get-month', [ReportController::class, 'getMonth'])->name('get-month');
});

// Route::get('/laporanview', 'laporanview');

Route::group([
    'prefix' => 'service-types',
    'as' => 'service-types.',
], function () {
    Route::get('/{serviceType}', [ServiceTypeController::class, 'show'])->name('show');
    Route::patch('/{serviceType}', [ServiceTypeController::class, 'update'])->name('update');
});

Route::get('/user', [UserController::class, 'index']);
Route::post('/usersimpan', [UserController::class, 'usersimpan']);
Route::get('useredit/{id}', [UserController::class, 'useredit']);
Route::post('userupdate/{id}', [UserController::class, 'userupdate']);
Route::delete('userhapus/{id}', [UserController::class, 'userdestroy']);

Route::get('/pengeluaran', [PengeluaranController::class, 'index']);
Route::post('/pengeluaransimpan', [PengeluaranController::class, 'pengeluaransimpan']);
Route::get('pengeluaranedit/{id}', [PengeluaranController::class, 'pengeluaranedit']);
Route::post('pengeluaranupdate/{id}', [PengeluaranController::class, 'pengeluaranupdate']);
Route::delete('pengeluaranhapus/{id}', [PengeluaranController::class, 'pengeluarandestroy']);

// stokbarang
Route::get('/stok', [StokController::class, 'index']);
Route::post('/stoksimpan', [StokController::class, 'stoksimpan']);
Route::get('stokedit/{id}', [StokController::class, 'stokedit']);
Route::post('stokupdate/{id}', [StokController::class, 'stokupdate']);
Route::delete('stokhapus/{id}', [StokController::class, 'stokdestroy']);

Route::get('/jadwal', [JadwalController::class, 'index']);
Route::get('/prosesJadwal/{id}', [JadwalController::class, 'prosesJadwal']);
Route::get('/getPrice', [JadwalController::class, 'getPrice']);
Route::post('/simpanJadwal', [JadwalController::class, 'simpanJadwal']);
Route::put('updateJadwal/{id}', [JadwalController::class, 'updateJadwal']);
Route::delete('hapusJadwal/{id}', [JadwalController::class, 'hapusJadwal']);

Route::get('/log', [LogController::class, 'index']);

Route::get('/reports/chart', [ReportController::class, 'chartData'])->name('admin.reports.chartData');

Route::get('/jadwalbayar/{id}', [JadwalController::class, 'bayar']);
