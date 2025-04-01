<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\TemporaryDepositController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('transfers.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes des transferts
    Route::resource('transfers', TransferController::class);
    Route::patch('transfers/{transfer}/status', [TransferController::class, 'updateStatus'])->name('transfers.update-status');
    Route::get('transfers/{transfer}/receipt', [TransferController::class, 'receipt'])->name('transfers.receipt');
    Route::get('/transfers/export/excel', [TransferController::class, 'exportExcel'])->name('transfers.export.excel');
    Route::get('/transfers/export/csv', [TransferController::class, 'exportCsv'])->name('transfers.export.csv');
    Route::get('/transfers/export/pdf', [TransferController::class, 'exportPdf'])->name('transfers.export.pdf');

    // Routes pour la gestion des utilisateurs
    Route::resource('users', UserController::class);
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::get('users/{user}/transfers', [UserController::class, 'transfers'])->name('users.transfers');

    // Routes pour la gestion des pays
    Route::resource('countries', CountryController::class);

    // Routes pour la gestion des devises
    Route::resource('currencies', CurrencyController::class);

    // Routes pour les dépôts temporaires
    Route::resource('deposits', TemporaryDepositController::class);
    Route::get('deposits/{deposit}/add-payment', [TemporaryDepositController::class, 'addPayment'])->name('deposits.add-payment');
    Route::post('deposits/{deposit}/payments', [TemporaryDepositController::class, 'storePayment'])->name('deposits.store-payment');
});

require __DIR__.'/auth.php';
