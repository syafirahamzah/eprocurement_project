<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    //Schema::table('customers', function (Blueprint $table) {
       // $table->string('email')->unique(); // Menambahkan kolom email yang unik
    //});
}

public function down()
{
  //  Schema::table('customers', function (Blueprint $table) {
       // $table->dropColumn('email'); // Menghapus kolom email jika migrasi di-rollback
   // });
 }

};
