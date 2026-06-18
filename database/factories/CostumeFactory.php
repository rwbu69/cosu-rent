<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Costume>
 */
class CostumeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'series' => $this->faker->words(2, true),
            'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL', 'All Size']),
            'base_price' => $this->faker->randomFloat(2, 50000, 300000),
        ];
    }
}
