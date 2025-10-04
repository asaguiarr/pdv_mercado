<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;

// Dashboard Administrativo
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/admin-dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});
