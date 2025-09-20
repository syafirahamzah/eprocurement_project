<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('negotiations', function (Blueprint $table) {
        $table->decimal('proposed_price', 12, 2)->nullable()->change(); // Boleh juga kasih ->default(0)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
