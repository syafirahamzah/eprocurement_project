<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->integer('stock')->default(0)->change();  // Mengubah kolom stock menjadi integer dan memberi default 0
    });
}

public function down(): void
{
    // Pastikan kolom 'stock' ada sebelum mencoba menghapusnya
    if (Schema::hasColumn('products', 'stock')) {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }
}




};
