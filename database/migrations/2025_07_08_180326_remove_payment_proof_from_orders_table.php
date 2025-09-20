<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {
            // Drop kolom hanya jika kolom itu ada
            if (Schema::hasColumn('orders', 'payment_proof')) {
                $table->dropColumn('payment_proof');
            }
        });
    }



public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        // Menambahkan kembali kolom payment_proof jika rollback
        $table->string('payment_proof')->nullable();
    });
}

};
