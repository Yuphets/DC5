<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $categories = ['Laptop', 'Smartphone', 'Tablet', 'Monitor', 'Keyboard', 'Mouse', 'Headset', 'Printer', 'Scanner', 'Router'];
        $adjectives = ['Ultra', 'Pro', 'Max', 'Plus', 'Elite', 'Essential', 'Premium', 'Advanced'];
        $productName = $this->faker->randomElement($adjectives) . ' ' . $this->faker->randomElement($categories) . ' ' . $this->faker->numberBetween(1000, 9999);

        return [
            'name' => $productName,
            'sku' => strtoupper(substr($this->faker->word, 0, 3) . '-' . $this->faker->unique()->numberBetween(1000, 9999)),
            'description' => $this->faker->paragraphs(2, true),
            'price' => $this->faker->randomFloat(2, 50, 2000),
        ];
    }
}
