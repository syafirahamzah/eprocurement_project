<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->integer('price');
        $table->integer('stock');


        // Ini bagian penting untuk relasi dengan vendor
        $table->unsignedBigInteger('vendor_id');
        $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');

        $table->timestamps();
    });
}


    public function down(): void {
        Schema::dropIfExists('products');
    }
};