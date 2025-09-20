<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function down(): void
    {
        // Periksa apakah foreign key ada sebelum mencoba menghapusnya
        $foreignKeyName = 'orders_user_id_foreign';

        // Periksa apakah foreign key 'orders_user_id_foreign' ada di tabel 'orders'
        $foreignKeys = DB::select('SHOW CREATE TABLE orders');
        $foreignKeyExists = false;

        // Cari apakah foreign key yang dimaksud ada dalam hasil SHOW CREATE TABLE
        foreach ($foreignKeys as $key) {
            if (strpos($key->{'Create Table'}, $foreignKeyName) !== false) {
                $foreignKeyExists = true;
                break;
            }
        }

        // Jika foreign key ada, lakukan penghapusan
        if ($foreignKeyExists) {
            Schema::table('orders', function (Blueprint $table) use ($foreignKeyName) {
                $table->dropForeign($foreignKeyName); // Hapus foreign key
            });
        }
    }
};
