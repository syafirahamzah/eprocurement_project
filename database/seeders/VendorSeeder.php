<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class VendorSeeder extends Seeder
{
    public function run()
    {
        $vendor = User::firstOrCreate(
    ['email' => 'vendor@example.com'],
    [
        'name' => 'Vendor Satu',
        'password' => Hash::make('vendor1234'),
        'role' => 'vendor',
    ]
);

$vendor->assignRole('vendor'); 

    }
}
