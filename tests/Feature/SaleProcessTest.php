<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SaleProcessTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $customer;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user for authentication
        $this->user = User::factory()->create();

        // Create a customer
        $this->customer = Customer::factory()->create();

        // Create a product with stock
        $this->product = Product::factory()->create([
            'stock' => 10,
            'sale_price' => 100.00,
        ]);
    }

    /** @test */
    public function it_processes_a_sale_without_delivery()
    {
        $this->actingAs($this->user);

        $response = $this->postJson(route('pdv.sales.store'), [
            'cart' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 2,
                    'price' => $this->product->sale_price,
                ],
            ],
            'payment_method' => 'dinheiro',
            'discount' => 10,
            'customer_id' => $this->customer->id,
            'delivery_type' => 'balcao',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['sale' => ['id', 'status', 'total', 'payment_method']]);
        $this->assertDatabaseHas('sales', [
            'id' => $response->json('sale.id'),
            'status' => 'closed',
            'payment_method' => 'dinheiro',
        ]);
        $this->assertDatabaseHas('sale_items', [
            'sale_id' => $response->json('sale.id'),
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'stock' => 8, // stock reduced by 2
        ]);
    }

    /** @test */
    public function it_processes_a_sale_with_delivery_and_creates_order()
    {
        $this->actingAs($this->user);

        $response = $this->postJson(route('pdv.sales.store'), [
            'cart' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 3,
                    'price' => $this->product->sale_price,
                ],
            ],
            'payment_method' => 'cartao',
            'discount' => 0,
            'customer_id' => $this->customer->id,
            'delivery_type' => 'entrega',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['sale' => ['id', 'status', 'total', 'payment_method']]);
        $this->assertDatabaseHas('sales', [
            'id' => $response->json('sale.id'),
            'status' => 'pending',
            'payment_method' => 'cartao',
        ]);
        $this->assertDatabaseHas('sale_items', [
            'sale_id' => $response->json('sale.id'),
            'product_id' => $this->product->id,
            'quantity' => 3,
        ]);
        $this->assertDatabaseHas('orders', [
            'customer_id' => $this->customer->id,
            'status' => 'todo',
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'stock' => 7, // stock reduced by 3
        ]);
    }

    /** @test */
    public function it_fails_when_insufficient_stock()
    {
        $this->actingAs($this->user);

        $response = $this->postJson(route('pdv.sales.store'), [
            'cart' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 20,
                    'price' => $this->product->sale_price,
                ],
            ],
            'payment_method' => 'pix',
            'discount' => 0,
            'customer_id' => $this->customer->id,
            'delivery_type' => 'balcao',
        ]);

        $response->assertStatus(500);
        $this->assertStringContainsString('Insufficient stock', $response->json('error'));
    }
}
