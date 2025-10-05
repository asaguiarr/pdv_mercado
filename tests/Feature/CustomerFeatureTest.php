<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class CustomerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        // Create an admin user for authentication
        $this->adminUser = User::factory()->create([
            'role' => 'admin',
            'active' => 1,
        ]);
    }

    #[Test]
    public function admin_can_view_customers_index()
    {
        $response = $this->actingAs($this->adminUser)->get(route('customers.index'));
        $response->assertStatus(200);
        $response->assertViewIs('customers.index');
    }

    #[Test]
    public function admin_can_create_a_customer()
    {
        $customerData = [
            'name' => 'Test Customer',
            'contact' => '123456789',
            'address' => '123 Test St',
            'email' => 'test@example.com',
            'rg' => '123456789',
            'cpf' => '12345678901',
            'birthdate' => '1990-01-01',
        ];

        $response = $this->actingAs($this->adminUser)->post(route('customers.store'), $customerData);
        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', ['email' => 'test@example.com']);
    }

    #[Test]
    public function admin_can_view_a_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($this->adminUser)->get(route('customers.show', $customer->id));
        $response->assertStatus(200);
        $response->assertViewIs('customers.show');
        $response->assertSee($customer->name);
    }

    #[Test]
    public function admin_can_update_a_customer()
    {
        $customer = Customer::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'contact' => '987654321',
            'address' => '456 Updated St',
            'email' => 'updated@example.com',
            'rg' => '987654321',
            'cpf' => '98765432109',
            'birthdate' => '1995-05-05',
        ];

        $response = $this->actingAs($this->adminUser)->put(route('customers.update', $customer->id), $updateData);
        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', ['email' => 'updated@example.com']);
    }

    #[Test]
    public function admin_can_delete_a_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($this->adminUser)->delete(route('customers.destroy', $customer->id));
        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
}
