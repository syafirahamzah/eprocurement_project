<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        Notification::create([
            'user_id' => 2, // ID customer
            'title' => 'Pesan Baru',
            'message' => 'Ada pesan baru dalam negosiasi produk Cat Dinding',
            'type' => 'negotiation',
            'product_id' => 1,        // Pastikan ID produk ini ada
            'negotiation_id' => 1,    // Pastikan ID negosiasi ini ada
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => 2,
            'title' => 'Pesanan Dikirim',
            'message' => 'Pesanan Anda sedang dalam perjalanan.',
            'type' => 'delivery',
            'product_id' => 2,        // Ganti sesuai ID produk yang valid
            'is_read' => false,
        ]);
    }
}
