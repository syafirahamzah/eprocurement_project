<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
{
    $admin = User::firstOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name' => 'Admin Utama',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]
    );

    $admin->assignRole('admin'); // penting
}
}
