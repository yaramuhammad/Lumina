<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductColor>
 */
class ProductColorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hex_code' => fake()->hexColor,
            'product_id' => rand(1, 50),
            'price' => fake()->randomFloat(2, 15, 1000),
            'quantity' => rand(0, 100),
        ];
    }
}
