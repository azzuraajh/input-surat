<?php

use App\Http\Controllers\ArsipController;
use App\Http\Controllers\ArsipImportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('arsips.index')
        : redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    // CRUD Arsip
    Route::resource('arsips', ArsipController::class);

    // Export (filter-aware — passes current query string)
    Route::get('/arsips/export/xlsx', [ArsipController::class, 'exportXlsx'])->name('arsips.export.xlsx');
    Route::get('/arsips/export/csv',  [ArsipController::class, 'exportCsv'])->name('arsips.export.csv');

    // Import via browser
    Route::get('/arsips/import',        [ArsipImportController::class, 'form'])->name('arsips.import.form');
    Route::post('/arsips/import',       [ArsipImportController::class, 'store'])->name('arsips.import.store');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
