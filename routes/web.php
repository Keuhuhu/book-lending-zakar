<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowingController;
use App\Http\Controllers\Admin\FineController as AdminFineController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StockLogController;


// Public
Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/books/{book}', [CatalogController::class, 'show'])->name('catalog.show');

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// User Area
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{book}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');

    Route::post('/checkout', [BorrowingController::class, 'checkout'])
        ->middleware('check.fines')
        ->name('checkout');
    Route::get('/borrowings', [BorrowingController::class, 'myHistory'])->name('borrowings.index');
    Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show'])->name('borrowings.show');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{book}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::post('/reviews/{book}', [ReviewController::class, 'store'])->name('reviews.store');

    Route::get('/fines', [FineController::class, 'index'])->name('fines.index');
    Route::post('/fines/{fine}/pay', [FineController::class, 'pay'])->name('fines.pay');
});

// Admin Area
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('books', BookController::class);
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);

    Route::get('borrowings', [AdminBorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('borrowings/{borrowing}', [AdminBorrowingController::class, 'show'])->name('borrowings.show');
    Route::patch('borrowings/{borrowing}/approve', [AdminBorrowingController::class, 'approve'])->name('borrowings.approve');
    Route::patch('borrowings/{borrowing}/reject', [AdminBorrowingController::class, 'reject'])->name('borrowings.reject');
    Route::patch('borrowings/{borrowing}/return', [AdminBorrowingController::class, 'confirmReturn'])->name('borrowings.return');
    // ✅ BARU — Download E-Receipt PDF
    Route::get('borrowings/{borrowing}/receipt', [AdminBorrowingController::class, 'downloadReceipt'])->name('borrowings.receipt');

    Route::get('fines', [AdminFineController::class, 'index'])->name('fines.index');
    Route::patch('fines/{fine}/paid', [AdminFineController::class, 'markPaid'])->name('fines.paid');

    // ✅ BARU — Reports dengan export PDF & Excel
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('stock-logs', [StockLogController::class, 'index'])->name('stock-logs.index');
});