<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create 6 realistic suppliers
        $suppliers = Supplier::factory(6)->create();

        // For each supplier, create 4-5 products
        $suppliers->each(function ($supplier) {
            $products = Product::factory(rand(4, 5))->create([
                'supplier_id' => $supplier->id
            ]);

            // For each product, create an inventory record
            $products->each(function ($product) {
                Inventory::factory()->create([
                    'product_id' => $product->id
                ]);
            });
        });
    }
}
