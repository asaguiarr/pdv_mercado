
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PdvController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\SaleController;

// ==========================================
// REDIRECIONAMENTO
// ==========================================
Route::redirect('/', 'login');

// ==========================================
// AUTENTICAÇÃO
// ==========================================
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset
Route::prefix('password')->group(function () {
    Route::get('reset', fn () => view('auth.passwords.email'))->name('password.request');
    Route::post('email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset/{token}', fn ($token) => view('auth.passwords.reset', ['token' => $token]))->name('password.reset');
    Route::post('reset', [AuthController::class, 'resetPassword'])->name('password.update');
});

// ==========================================
// DASHBOARD
// ==========================================
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ==========================================
// PRODUTOS
// ==========================================
Route::middleware(['auth', 'role:admin,super_admin'])->resource('products', ProductController::class);

// ==========================================
// FORNECEDORES
// ==========================================
Route::middleware(['auth', 'role:admin,super_admin'])->resource('suppliers', \App\Http\Controllers\SupplierController::class);

// ==========================================
// CLIENTES
// ==========================================
Route::middleware(['auth', 'role:admin,super_admin'])->resource('customers', CustomerController::class);
Route::middleware(['auth', 'role:admin,super_admin'])->get('customers/{id}/edit-contact', [CustomerController::class, 'editContact'])->name('customers.edit-contact');
Route::middleware(['auth', 'role:admin,super_admin'])->put('customers/{id}/update-contact', [CustomerController::class, 'updateContact'])->name('customers.update-contact');

// ==========================================
// PEDIDOS (moved to routes/orders.php)
// ==========================================

// ==========================================
// ADMINISTRAÇÃO DE USUÁRIOS
// ==========================================
Route::middleware(['auth', 'role:admin,super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', fn () => redirect()->route('admin.users.index'));
        Route::resource('users', AdminUserController::class)->names([
            'index'   => 'users.index',
            'create'  => 'users.create',
            'store'   => 'users.store',
            'show'    => 'users.show',
            'edit'    => 'users.edit',
            'update'  => 'users.update',
            'destroy' => 'users.destroy',
        ]);
});



// ==========================================
// VENDAS (API)
// ==========================================
Route::middleware(['auth', 'role:super_admin,admin,cashier,user'])->post('/sales', [\App\Http\Controllers\SaleController::class, 'createSale'])->name('api.sales.create');

// ==========================================
// ESTOQUE
// ==========================================
Route::middleware(['auth', 'role:estoquista,admin,super_admin'])
    ->prefix('estoque')
    ->name('estoque.')
    ->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::get('/entrada', [StockController::class, 'entrada'])->name('entrada');
        Route::post('/entrada', [StockController::class, 'storeEntrada'])->name('entrada.store');
        Route::get('/invoice-entrada', [StockController::class, 'invoiceEntrada'])->name('invoice_entrada');
        Route::post('/invoice-entrada', [StockController::class, 'storeInvoiceEntrada'])->name('invoice_entrada.store');
        Route::get('/saida', [StockController::class, 'saida'])->name('saida');
        Route::post('/saida', [StockController::class, 'storeSaida'])->name('saida.store');
        Route::get('/relatorio', [StockController::class, 'relatorio'])->name('relatorio');
});

// ==========================================
// RELATÓRIO DE ESTOQUE
// ==========================================
Route::middleware(['auth', 'role:estoquista,admin,super_admin'])
    ->prefix('stock')
    ->name('stock.')
    ->group(function () {
        Route::get('/report', [StockReportController::class, 'index'])->name('report');
});

// ==========================================
// ROTA FALLBACK (404)
// ==========================================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

use App\Http\Controllers\AccountsReceivableController;
use App\Http\Controllers\AccountsPayableController;

// ==========================================
// ARQUIVOS DE ROTAS ADICIONAIS
// ==========================================
require __DIR__.'/products.php';
require __DIR__.'/customers.php';
require __DIR__.'/orders.php';
require __DIR__.'/pdv.php';

Route::middleware(['auth', 'role:admin,super_admin'])->resource('accounts_receivable', AccountsReceivableController::class);
Route::middleware(['auth', 'role:admin,super_admin'])->patch('accounts_receivable/{id}/mark-as-paid', [AccountsReceivableController::class, 'markAsPaid'])->name('accounts_receivable.mark_as_paid');
Route::middleware(['auth', 'role:admin,super_admin'])->resource('accounts_payable', AccountsPayableController::class);
Route::middleware(['auth', 'role:admin,super_admin'])->get('cash_flow', [App\Http\Controllers\CashFlowController::class, 'index'])->name('cash_flow.index');

// Reverse geocode route
Route::middleware('auth')->get('reverse-geocode', function (\Illuminate\Http\Request $request) {
    $lat = $request->query('lat');
    $lon = $request->query('lon');

    if (!$lat || !$lon) {
        return response()->json(['error' => 'Latitude and longitude required'], 400);
    }

    $response = \Illuminate\Support\Facades\Http::get("https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lon}");

    if ($response->successful()) {
        $data = $response->json();
        return response()->json([
            'address' => $data['display_name'] ?? 'Address not found',
            'postcode' => $data['address']['postcode'] ?? null,
        ]);
    }

    return response()->json(['error' => 'Failed to retrieve address'], 500);
})->name('reverse-geocode');
