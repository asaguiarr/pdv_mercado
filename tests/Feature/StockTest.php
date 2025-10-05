<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;

class StockTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user with admin role (to have access to PDV and stock routes)
        $this->user = User::factory()->create([
            'role' => 'admin'
        ]);
    }

    #[Test]
    public function adding_to_cart_reduces_stock_and_creates_stock_movement_out()
    {
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->actingAs($this->user)
            ->postJson('/pdv/add-item', [
                'product_id' => $product->id,
                'quantity' => 3
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $product->refresh();
        $this->assertEquals(7, $product->stock);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => 3,
            'reference_type' => 'cart_add',
            'notes' => 'Added to cart',
            'user_id' => $this->user->id
        ]);
    }

    #[Test]
    public function removing_from_cart_increases_stock_and_creates_stock_movement_in()
    {
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->actingAs($this->user)
            ->postJson('/pdv/remove-item', [
                'product_id' => $product->id,
                'quantity' => 2
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $product->refresh();
        $this->assertEquals(12, $product->stock);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 2,
            'reference_type' => 'cart_remove',
            'notes' => 'Removed from cart',
            'user_id' => $this->user->id
        ]);
    }

    #[Test]
    public function manual_entrada_increases_stock_and_creates_stock_movement_in()
    {
        $product = Product::factory()->create(['stock' => 5]);

        $response = $this->actingAs($this->user)
            ->post('/estoque/entrada', [
                'product_id' => $product->id,
                'quantity' => 10,
                'notes' => 'Manual entry test'
            ]);

        $response->assertRedirect();

        $product->refresh();
        $this->assertEquals(15, $product->stock);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 10,
            'reference_type' => 'manual',
            'notes' => 'Manual entry test',
            'user_id' => $this->user->id
        ]);
    }

    #[Test]
    public function concurrent_requests_dont_allow_negative_stock()
    {
        $product = Product::factory()->create(['stock' => 5]);

        // Simulate concurrent requests trying to add more items than available
        $responses = [];

        // First request should succeed
        $responses[] = $this->actingAs($this->user)
            ->postJson('/pdv/add-item', [
                'product_id' => $product->id,
                'quantity' => 5
            ]);

        // Second request should fail due to insufficient stock
        $responses[] = $this->actingAs($this->user)
            ->postJson('/pdv/add-item', [
                'product_id' => $product->id,
                'quantity' => 1
            ]);

        $responses[0]->assertStatus(200);
        $responses[1]->assertStatus(422);
        $responses[1]->assertJson(['error' => 'Insufficient stock']);

        $product->refresh();
        $this->assertEquals(0, $product->stock);
    }

    #[Test]
    public function stock_report_shows_correct_aggregates()
    {
        $product = Product::factory()->create(['stock' => 10]);

        // Create some stock movements
        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 20,
            'reference_type' => 'manual',
            'user_id' => $this->user->id
        ]);

        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => 5,
            'reference_type' => 'cart_add',
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)
            ->get('/stock/report');

        $response->assertStatus(200);

        // Check that the view has the correct data
        $response->assertViewHas('stockReport');

        $stockReport = $response->viewData('stockReport');

        $this->assertCount(1, $stockReport);
        $this->assertEquals($product->name, $stockReport[0]->name);
        $this->assertEquals(10, $stockReport[0]->current_stock);
        $this->assertEquals(20, $stockReport[0]->total_in);
        $this->assertEquals(5, $stockReport[0]->total_out);
    }

    #[Test]
    public function insufficient_stock_returns_422_error()
    {
        $product = Product::factory()->create(['stock' => 2]);

        $response = $this->actingAs($this->user)
            ->postJson('/pdv/add-item', [
                'product_id' => $product->id,
                'quantity' => 5
            ]);

        $response->assertStatus(422);
        $response->assertJson(['error' => 'Insufficient stock']);

        // Stock should remain unchanged
        $product->refresh();
        $this->assertEquals(2, $product->stock);
    }
}
