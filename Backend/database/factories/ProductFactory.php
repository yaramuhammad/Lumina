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
            'name' => fake()->word,
            'description' => implode(' ', fake()->paragraphs(7)),
            'price' => fake()->randomFloat(2, 15, 1000),
            'sub_category_id' => rand(1, 8),
        ];
    }
}
