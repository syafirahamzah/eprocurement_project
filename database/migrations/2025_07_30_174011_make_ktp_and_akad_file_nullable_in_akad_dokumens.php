<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('akad_dokumens', function (Blueprint $table) {
            $table->string('ktp_path')->nullable()->change();
            $table->string('akad_file')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('akad_dokumens', function (Blueprint $table) {
            $table->string('ktp_path')->nullable(false)->change();
            $table->string('akad_file')->nullable(false)->change();
        });
    }
};

