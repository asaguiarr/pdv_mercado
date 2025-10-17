<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;
use App\Http\Controllers\SaleController;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

// Authenticate a user
$user = \App\Models\User::first();
if (!$user) {
    $user = \App\Models\User::create([
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);
}
auth()->login($user);

echo "Testing SaleController...\n";

// Set up a test product
$product = Product::first();
if (!$product) {
    $product = Product::create([
        'name' => 'Test Product',
        'sale_price' => 10.00,
        'stock' => 10,
        'active' => true,
    ]);
    echo "Created test product: {$product->name}\n";
} else {
    $product->update(['stock' => 10]);
    echo "Updated test product stock to 10: {$product->name}\n";
}

// Set up a test customer
$customer = Customer::first();
if (!$customer) {
    $customer = Customer::create([
        'name' => 'Test Customer',
        'email' => 'test@example.com',
        'cpf' => '12345678901',
        'rg' => '12345678',
        'contact' => '123456789',
        'birthdate' => '1990-01-01',
        'address' => 'Test Address',
        'active' => true,
    ]);
    echo "Created test customer: {$customer->name}\n";
}

// Test createSale method (API)
$controller = new SaleController();

$requestData = [
    'customer' => [
        'name' => 'Test Customer',
        'email' => 'test@example.com',
        'contact' => '123456789',
        'cpf' => '12345678901',
        'rg' => '12345678',
        'birthdate' => '1990-01-01',
        'address' => 'Test Address',
    ],
    'products' => [
        [
            'id' => $product->id,
            'quantity' => 2,
            'unit_price' => 10.00,
        ]
    ],
    'payment_method' => 'dinheiro',
    'discount' => 0,
    'delivery_type' => 'retirada',
];

$request = new Request();
$request->merge($requestData);

try {
    $response = $controller->createSale($request);
    $responseData = $response->getData(true);

    if ($responseData['success']) {
        echo "Sale created successfully!\n";
        echo "Sale ID: {$responseData['sale']['id']}\n";

        // Check stock was decremented
        $product->refresh();
        echo "Product stock after sale: {$product->stock}\n";

        // Check StockMovement was created
        $stockMovement = StockMovement::where('product_id', $product->id)->where('reference_type', 'sale')->first();
        if ($stockMovement) {
            echo "StockMovement created: type={$stockMovement->type}, quantity={$stockMovement->quantity}, reference_type={$stockMovement->reference_type}\n";
        } else {
            echo "ERROR: StockMovement not created!\n";
        }

        // Check SaleItem was created
        $saleItem = SaleItem::where('sale_id', $responseData['sale']['id'])->first();
        if ($saleItem) {
            echo "SaleItem created: quantity={$saleItem->quantity}, unit_price={$saleItem->unit_price}\n";
        } else {
            echo "ERROR: SaleItem not created!\n";
        }
    } else {
        echo "Sale creation failed: {$responseData['message']}\n";
        if (isset($responseData['errors'])) {
            print_r($responseData['errors']);
        }
    }
} catch (Exception $e) {
    echo "Exception during sale creation: {$e->getMessage()}\n";
}

// Test insufficient stock
echo "\nTesting insufficient stock...\n";
$requestData['products'][0]['quantity'] = 100; // More than available stock
$request->merge($requestData);

try {
    $response = $controller->createSale($request);
    $responseData = $response->getData(true);
    if (!$responseData['success']) {
        echo "Correctly prevented sale due to insufficient stock.\n";
    } else {
        echo "ERROR: Sale should have been prevented due to insufficient stock!\n";
    }
} catch (Exception $e) {
    echo "Exception (expected for insufficient stock): {$e->getMessage()}\n";
}

echo "Testing completed.\n";
