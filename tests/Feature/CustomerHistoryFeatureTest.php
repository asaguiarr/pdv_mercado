<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class CustomerHistoryFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->create([
            'role' => 'admin',
            'active' => 1,
        ]);
        $this->customer = Customer::factory()->create();
    }

    #[Test]
    public function admin_can_view_customer_history_in_customer_show()
    {
        $history = CustomerHistory::factory()->create([
            'customer_id' => $this->customer->id,
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('customers.show', $this->customer->id));
        $response->assertStatus(200);
        $response->assertViewIs('customers.show');
        $response->assertSee($history->action);
        $response->assertSee($history->description);
    }

    #[Test]
    public function customer_history_is_created_on_customer_actions()
    {
        // Create customer and check history
        $customerData = [
            'name' => 'Test Customer',
            'contact' => '123456789',
            'address' => '123 Test St',
            'email' => 'test@example.com',
            'rg' => '123456789',
            'cpf' => '12345678901',
            'birthdate' => '1990-01-01',
        ];

        $this->actingAs($this->adminUser)->post(route('customers.store'), $customerData);

        $customer = Customer::where('email', 'test@example.com')->first();
        $this->assertDatabaseHas('customer_histories', [
            'customer_id' => $customer->id,
            'action' => 'created',
        ]);

        // Update customer
        $updateData = array_merge($customerData, ['name' => 'Updated Name']);
        $this->actingAs($this->adminUser)->put(route('customers.update', $customer->id), $updateData);

        $this->assertDatabaseHas('customer_histories', [
            'customer_id' => $customer->id,
            'action' => 'updated',
        ]);
    }
}
