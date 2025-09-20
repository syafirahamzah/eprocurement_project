<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        // Mengubah kolom 'status' menjadi ENUM dengan nilai baru
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['menunggu konfirmasi', 'dikirim', 'selesai', 'dibatalkan'])
                  ->default('menunggu konfirmasi')
                  ->change();  // Menggunakan change() untuk mengubah kolom yang ada
        });
    }

    public function down(): void
    {
        // Kembalikan kolom 'status' ke tipe sebelumnya (misalnya VARCHAR atau lainnya)
        DB::statement("ALTER TABLE orders MODIFY status VARCHAR(255) NOT NULL");
    }
};
