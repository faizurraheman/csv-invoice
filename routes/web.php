<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('invoices', InvoiceController::class);
    Route::post('/invoices/search', [InvoiceController::class, 'search'])->name('invoices.search');
});

Route::middleware(['admin'])->group(function () {
    Route::get('/admin/import', [AdminController::class, 'showImportForm'])->name('admin.import');
    Route::post('/admin/import', [AdminController::class, 'importCsv'])->name('admin.import.csv');
});

require __DIR__.'/auth.php';
