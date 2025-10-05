<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\CashStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class SaleProcessTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_sale_process_with_cash_open()
    {
        // Create user and open cash
        $user = User::factory()->create();
        $this->actingAs($user);

        CashStatus::create([
            'user_id' => $user->id,
            'initial_balance' => 100.00,
            'status' => 'open',
        ]);

        $product = Product::factory()->create(['stock' => 10, 'sale_price' => 50]);

        $response = $this->postJson('/pdv/sale', [
            'cart' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'price' => 50,
                ],
            ],
            'payment_method' => 'dinheiro',
            'delivery_type' => 'retirada',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('sales', [
            'user_id' => $user->id,
            'total' => 100,
            'payment_status' => 'paid',
        ]);
        $this->assertDatabaseHas('cash_movements', [
            'type' => 'entry',
            'amount' => 100,
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseHas('stock_movements', [
            'type' => 'out',
            'quantity' => 2,
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function test_sale_process_without_cash_open()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create(['stock' => 10, 'sale_price' => 50]);

        $response = $this->postJson('/pdv/sale', [
            'cart' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'price' => 50,
                ],
            ],
            'payment_method' => 'dinheiro',
            'delivery_type' => 'retirada',
        ]);

        $response->assertStatus(422);
        $response->assertJson(['error' => 'Caixa não está aberto. Abra o caixa antes de realizar vendas em dinheiro.']);
    }

    #[Test]
    public function test_sale_process_with_debit_card()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create(['stock' => 10, 'sale_price' => 50]);

        $response = $this->postJson('/pdv/sale', [
            'cart' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'price' => 50,
                ],
            ],
            'payment_method' => 'debito',
            'delivery_type' => 'retirada',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('sales', [
            'user_id' => $user->id,
            'total' => 100,
            'payment_status' => 'paid',
            'payment_method' => 'debito',
        ]);
        $this->assertDatabaseHas('stock_movements', [
            'type' => 'out',
            'quantity' => 2,
            'user_id' => $user->id,
        ]);
        // Debit card payments should not create cash movements
        $this->assertDatabaseMissing('cash_movements', [
            'sale_id' => $response->json('sale.id'),
        ]);
    }

    #[Test]
    public function test_sale_process_with_credit_card()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create(['stock' => 10, 'sale_price' => 50]);

        $response = $this->postJson('/pdv/sale', [
            'cart' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'price' => 50,
                ],
            ],
            'payment_method' => 'credito',
            'delivery_type' => 'retirada',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('sales', [
            'user_id' => $user->id,
            'total' => 100,
            'payment_status' => 'paid',
            'payment_method' => 'credito',
        ]);
        $this->assertDatabaseHas('stock_movements', [
            'type' => 'out',
            'quantity' => 2,
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function test_sale_process_with_pix()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create(['stock' => 10, 'sale_price' => 50]);

        $response = $this->postJson('/pdv/sale', [
            'cart' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'price' => 50,
                ],
            ],
            'payment_method' => 'pix',
            'delivery_type' => 'retirada',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('sales', [
            'user_id' => $user->id,
            'total' => 100,
            'payment_status' => 'paid',
            'payment_method' => 'pix',
        ]);
        $this->assertDatabaseHas('stock_movements', [
            'type' => 'out',
            'quantity' => 2,
            'user_id' => $user->id,
        ]);
    }
}
