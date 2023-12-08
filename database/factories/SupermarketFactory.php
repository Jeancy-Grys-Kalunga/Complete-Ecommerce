<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supermarket>
 */
class SupermarketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->company(),
            'description' => $this->faker->paragraph(),
            'thumbail' => 'https://via.placeholder.com/1000',
            'address' => $this->faker->address(),
            'slug' => function (array $attributes) {
                return Str::slug($attributes['title']);
            },
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
