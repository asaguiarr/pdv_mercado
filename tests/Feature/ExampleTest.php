<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_application_returns_a_successful_response(): void
    {
        $user = User::factory()->create(['active' => 1]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }
}
