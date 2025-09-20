<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Negotiation;

class NegotiationSeeder extends Seeder
{
    public function run(): void
    {
        Negotiation::create([
            'product_id'  => 1, // Pastikan product_id ini valid
            'vendor_id'   => 1, // ID vendor pemilik produk (pastikan ada)
            'customer_id' => 2, // ID customer (misalnya user_id 2)
            'status'      => 'menunggu',
        ]);
    }
}
