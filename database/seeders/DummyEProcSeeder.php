<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Tracking;
use App\Models\Notification;
use App\Models\Negotiation;

class DummyEProcSeeder extends Seeder
{
    public function run(): void
    {
        $vendor = User::where('email', 'vendor@example.com')->first();
        $customer = User::where('email', 'customer@example.com')->first();

        if (!$vendor || !$customer) {
            $this->command->warn("Vendor atau Customer belum dibuat. Jalankan seeder user terlebih dahulu.");
            return;
        }

        $product = Product::create([
            'vendor_id' => $vendor->id, // ganti ke 'vendor_id' jika itu nama kolomnya
            'name' => 'Semen Super',
            'description' => 'Semen premium untuk proyek besar',
            'price' => 75000,
            'stock' => 100,
        ]);

        $negotiation = Negotiation::create([
            'product_id' => $product->id,
            'customer_id' => $customer->id,
            'vendor_id' => $vendor->id,
            'quantity' => 10,
            'proposed_price' => 70000,
            'status' => 'disetujui',
        ]);

        $order = Order::create([
            'negotiation_id' => $negotiation->id,
            'product_id' => $product->id,
            'vendor_id' => $vendor->id,
            'customer_id' => $customer->id,
            'user_id' =>1,
            'agreed_price' => 70000,
            'status' => 'dikirim',
            'payment_status' => 'unpaid', // tambahkan ini
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Tracking::create([
            'order_id' => $order->id,
            'status' => 'dikirim',
            'estimated_delivery' => now()->addDays(3),
        ]);

        Notification::create([
            'user_id' => $customer->id,
            'title' => 'Pesanan Dikirim',
            'message' => 'Pesanan Anda sedang dalam perjalanan.',
            'is_read' => false,
        ]);
    }
}
