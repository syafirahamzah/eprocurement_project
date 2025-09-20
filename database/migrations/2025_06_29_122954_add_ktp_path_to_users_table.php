<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('ktp_path')->nullable()->after('address');
    });
}

public function down(): void
{
    // Periksa apakah kolom 'ktp_path' ada, baru lakukan drop
    if (Schema::hasColumn('users', 'ktp_path')) {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ktp_path');
        });
    }
}


};
