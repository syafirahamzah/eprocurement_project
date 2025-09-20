<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            [
                'name' => 'Semen Tiga Roda',
                'description' => 'Semen kualitas tinggi untuk konstruksi',
                'price' => 55000,
                'vendor_id' => 1,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'name' => 'Besi Beton 10mm',
                'description' => 'Besi beton untuk tulangan bangunan',
                'price' => 78000,
                'vendor_id' => 1,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'name' => 'Paku 5cm',
                'description' => 'Paku standar untuk pembangunan',
                'price' => 20000,
                'vendor_id' => 1,
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
    }
}
