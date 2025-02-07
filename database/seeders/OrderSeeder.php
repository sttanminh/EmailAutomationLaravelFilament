<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $products = Product::all();

        foreach ($customers as $customer) {
            $order = Order::create([
                'customer_id' => $customer->id,
                'order_number' => rand(1000, 9999),
                'total' => 0, // Will be updated below
                'status' => collect(['pending', 'approved', 'shipped', 'cancelled'])->random(),
                'created_at' => Carbon::now()->subDays(rand(0, 30)), // Random past date
            ]);

            // Attach random products to the order
            $total = 0;
            $randomProducts = $products->random(rand(1, 5)); // Select 1-5 random products

            foreach ($randomProducts as $product) {
                $quantity = rand(1, 5);
                $order->products()->attach($product->id, ['quantity' => $quantity]);
                $total += $product->price * $quantity;
            }

            // Update order total
            $order->update(['total' => $total]);
        }
    }
}
