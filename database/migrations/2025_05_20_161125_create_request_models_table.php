<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestModelsTable extends Migration
{
    public function up()
    {
        Schema::create('request_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('status')->default('baru'); // contoh status: baru, diproses, selesai
            $table->text('description')->nullable();  // contoh kolom tambahan
            $table->timestamps();

            // Jika ada tabel vendor, buat foreign key
            // $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_models');
    }
}
