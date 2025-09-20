<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('negotiations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
        $table->integer('quantity'); 
        $table->integer('proposed_price');
        $table->string('status'); 
        $table->timestamps();
        $table->decimal('final_price', 12, 2)->nullable();
    $table->boolean('is_approved')->default(false);
    });
    }

    public function down(): void {
        Schema::dropIfExists('negotiations');
    }
};
