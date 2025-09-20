<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('negotiation_id')->nullable()->constrained();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('vendor_id')->constrained('users');
            $table->foreignId('customer_id')->constrained('users');
            $table->decimal('agreed_price', 15, 2);
            
            // ENUM status
            $table->enum('status', [
                'menunggu konfirmasi',
                'diproses',
                'dikirim',
                'selesai',
                'dibatalkan'
            ])->default('menunggu konfirmasi');
            

        

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
