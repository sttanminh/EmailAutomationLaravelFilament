<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brands = ['Nike', 'Adidas', 'Puma', 'Reebok', 'Under Armour'];

        foreach (range(1, 20) as $index) {
            Product::create([
                'name' => 'Product ' . $index,
                'brand' => $brands[array_rand($brands)],
                'price' => rand(50, 500),
                'sku' => 'PROD-' . Str::upper(Str::random(8)),
                'quantity' => rand(10, 100),
                'image' => 'products/demo.jpg', // ✅ Placeholder image
                'is_visible' => (bool) rand(0, 1),
            ]);
        }
    }
}
