<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('negotiations', function (Blueprint $table) {
        if (!Schema::hasColumn('negotiations', 'offered_price')) {
            $table->decimal('offered_price', 15, 2)->nullable()->after('status');
        }
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('negotiations', function (Blueprint $table) {
            //
        });
    }
};
