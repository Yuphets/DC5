<?php

namespace Database\Factories;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    protected $model = Inventory::class;

    public function definition(): array
    {
        $warehouses = ['North Warehouse', 'South Warehouse', 'East Warehouse', 'West Warehouse', 'Central Depot'];
        return [
            'quantity' => $this->faker->numberBetween(0, 500),
            'location' => $this->faker->randomElement($warehouses),
            'reorder_level' => $this->faker->numberBetween(10, 50),
            'last_restocked' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
