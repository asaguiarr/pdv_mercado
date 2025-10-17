<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdvController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\Pdv\ProductController;

// Verificação de senha para operações administrativas
Route::middleware(['auth', 'role:super_admin,admin,cashier'])->post('verify-admin-password', [PdvController::class, 'verifyAdminPassword'])->name('pdv.verify-admin-password');

// ==========================================
// PDV (VENDAS)
// ==========================================
Route::middleware(['auth', 'role:super_admin,admin,cashier,user'])
    ->prefix('pdv')
    ->name('pdv.')
    ->group(function () {
        Route::get('/', [PdvController::class, 'index'])->name('sales');
        Route::get('create', [SaleController::class, 'create'])->name('sales.create');
        Route::post('sale', [SaleController::class, 'store'])->name('sales.store');
        Route::get('product', [PdvController::class, 'getProduct'])->name('product.show');
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show');
        Route::post('add-item', [PdvController::class, 'addItem'])->name('add-item');
        Route::post('remove-item', [PdvController::class, 'removeItem'])->name('remove-item');
        Route::get('{id}', [PdvController::class, 'show'])->name('sales.show');
        Route::get('history/{customer}', [PdvController::class, 'history'])->name('history');

        // Abertura e fechamento de caixa
        Route::get('open-cash', [PdvController::class, 'openCash'])->name('open-cash');
        Route::post('open-cash', [PdvController::class, 'openCashStore'])->name('open-cash.store');
        Route::get('close-cash', [PdvController::class, 'closeCash'])->name('close-cash');
        Route::post('close-cash', [PdvController::class, 'closeCashStore'])->name('close-cash.store');

        // Relatórios de vendas
        Route::get('report', [PdvController::class, 'report'])->name('report');
});