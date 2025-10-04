<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
            'active' => true,
        ]);
        $this->customer = Customer::factory()->create();
    }

    /** @test */
    public function admin_can_view_customer_history_index()
    {
        $response = $this->actingAs($this->adminUser)->get(route('customer_histories.index'));
        $response->assertStatus(200);
        $response->assertViewIs('customer_histories.index');
    }

    /** @test */
    public function admin_can_view_customer_history_show()
    {
        $history = CustomerHistory::factory()->create([
            'customer_id' => $this->customer->id,
            'user_id' => $this->adminUser->id,
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('customer_histories.show', $history->id));
        $response->assertStatus(200);
        $response->assertViewIs('customer_histories.show');
        $response->assertSee($history->action);
    }
}
