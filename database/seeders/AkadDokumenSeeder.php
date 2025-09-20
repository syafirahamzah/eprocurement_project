<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\AkadDokumen;

class AkadDokumenSeeder extends Seeder
{
    /**
     * Jalankan seeder dokumen akad.
     */
    public function run(): void
    {
        AkadDokumen::create([
            'order_id' => 17,
            'negotiation_id' => 7,
            'akad_file' => 'akad/akad_17.pdf',
            'ktp_path' => 'ktp/ktp_17.jpg',
            'is_valid' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
