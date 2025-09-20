<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('agreements', function (Blueprint $table) {
            $table->string('agreement_file')->nullable()->after('agreed_at');
            $table->string('ktp_file')->nullable()->after('agreement_file');
        });
    }

    public function down(): void
    {
        Schema::table('agreements', function (Blueprint $table) {
            $table->dropColumn(['agreement_file', 'ktp_file']);
        });
    }
};

