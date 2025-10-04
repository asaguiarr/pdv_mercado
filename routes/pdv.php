<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdvController;

// Verificação de senha para operações administrativas
Route::middleware(['auth', 'role:super_admin,admin,cashier'])->post('verify-admin-password', [PdvController::class, 'verifyAdminPassword'])->name('pdv.verify-admin-password');
