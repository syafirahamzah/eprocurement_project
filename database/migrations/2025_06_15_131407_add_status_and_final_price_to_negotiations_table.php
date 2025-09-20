<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom status dan final_price ke tabel negotiations
     */
    public function up()
    {
        Schema::table('negotiations', function (Blueprint $table) {
            if (!Schema::hasColumn('negotiations', 'status')) {
                $table->enum('status', ['berlangsung', 'menunggu', 'disetujui'])->default('berlangsung')->after('customer_id');
            }

            if (!Schema::hasColumn('negotiations', 'final_price')) {
                $table->decimal('final_price', 15, 2)->nullable()->after('status');
            }
        });
    }

    /**
     * Hapus kolom status dan final_price saat rollback
     */
    public function down()
    {
        Schema::table('negotiations', function (Blueprint $table) {
            if (Schema::hasColumn('negotiations', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('negotiations', 'final_price')) {
                $table->dropColumn('final_price');
            }
        });
    }
};
