<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 1000), // Випадкова ціна від 10 до 1000
            'image' => $this->faker->imageUrl(640, 480, 'products', true, 'Faker'), // Посилання на випадкове зображення
        ];
    }
}
