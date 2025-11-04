<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Laptop Dell XPS', 'price' => 7999.99, 'stock_quantity' => 12],
            ['name' => 'iPhone 16 Pro', 'price' => 6499.99, 'stock_quantity' => 8],
            ['name' => 'Sony WH-1000XM5', 'price' => 1599.99, 'stock_quantity' => 5],
            ['name' => 'Samsung Galaxy Tab S9', 'price' => 3599.99, 'stock_quantity' => 3],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(
                ['name' => $data['name']],
                ['price' => $data['price'], 'stock_quantity' => $data['stock_quantity']]
            );
        }
    }
}
