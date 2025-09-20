<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
    Schema::create('negotiation_messages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('negotiation_id')->constrained('negotiations')->onDelete('cascade');
    $table->text('message');
    $table->string('sender_role'); // customer/vendor
    $table->boolean('is_read')->default(false);
    $table->timestamps();
});

    }

    public function down()
    {
        Schema::dropIfExists('negotiation_messages');
    }
};
