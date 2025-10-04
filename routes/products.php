<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Rotas de produtos com controle de acesso por roles
Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::resource('products', ProductController::class);
});
