<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->numerify('PROD#####'),
            'name' => $this->faker->word,
            'cost_price' => $this->faker->randomFloat(2, 10, 100),
            'profit_margin' => $this->faker->randomFloat(2, 10, 50),
            'sale_price' => $this->faker->randomFloat(2, 20, 200),
            'stock' => $this->faker->numberBetween(0, 100),
            'active' => true,
            'description' => $this->faker->sentence,
        ];
    }
}
