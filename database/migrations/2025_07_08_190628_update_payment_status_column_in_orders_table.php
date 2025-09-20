<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePaymentStatusColumnInOrdersTable extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_status', 10)->change();
        });
    }

    public function down(): void
    {
       // Schema::table('orders', function (Blueprint $table) {
         //   $table->string('payment_status', 5)->change();
       // });
    }
};