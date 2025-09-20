<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('akad_dokumens', function (Blueprint $table) {
        if (!Schema::hasColumn('akad_dokumens', 'negotiation_id')) {
            $table->foreignId('negotiation_id')
                  ->nullable()
                  ->after('order_id')
                  ->constrained()
                  ->onDelete('set null');
        }
    });
}



    public function down(): void
{
    Schema::table('akad_dokumens', function (Blueprint $table) {
        if (Schema::hasColumn('akad_dokumens', 'negotiation_id')) {
            $table->dropForeign(['negotiation_id']);
            $table->dropColumn('negotiation_id');
        }
    });
}

};
