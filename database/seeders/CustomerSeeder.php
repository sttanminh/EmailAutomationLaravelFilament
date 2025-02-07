<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::factory(10)->create([
            'password' => Hash::make('password'), // Default password
        ]);
    }
}
