<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

// Rotas de pedidos com controle de acesso por roles
Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::resource('orders', OrderController::class);
});
