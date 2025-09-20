<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('request_models', function (Blueprint $table) {
        $table->unsignedBigInteger('customer_id')->nullable()->after('id');
        // Jika ada tabel users sebagai customer, tambahkan foreign key:
        // $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('request_models', function (Blueprint $table) {
        // $table->dropForeign(['customer_id']); // jika pakai foreign key
        $table->dropColumn('customer_id');
    });
}

};
