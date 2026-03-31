<?php

namespace Database\Factories;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    protected $model = Inventory::class;

    public function definition(): array
    {
        return [
            'quantity' => $this->faker->numberBetween(0, 500),
            'location' => $this->faker->word . ' Warehouse',
            'reorder_level' => $this->faker->numberBetween(5, 50),
            'last_restocked' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}