<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

// Rotas de clientes com controle de acesso por roles
Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::resource('customers', CustomerController::class);
    Route::get('customers/{id}/edit-contact', [CustomerController::class, 'editContact'])->name('customers.edit-contact');
    Route::put('customers/{id}/update-contact', [CustomerController::class, 'updateContact'])->name('customers.update-contact');
});
