<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customer = User::firstOrCreate(
    ['email' => 'customer@example.com'],
    [
        'name' => 'Customer Satu',
        'password' => Hash::make('customer1234'),
        'role' => 'customer',
    ]
);

$customer->assignRole('customer'); 

    }
}
