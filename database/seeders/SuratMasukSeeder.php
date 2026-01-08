<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuratMasukSeeder extends Seeder
{
    public function run()
    {
        $rows = [];
        $today = Carbon::today();

        for ($i = 1; $i <= 15; $i++) {
            $no = 100 + $i; // contoh nomor surat
            $rows[] = [
                'nomor_surat' => 'SM-2025/'.str_pad($no, 3, '0', STR_PAD_LEFT),
                'tanggal_diterima' => $today->copy()->subDays(15 - $i)->toDateString(),
                'pengirim' => "Pengirim {$i}",
                'perihal' => "Perihal contoh {$i}",
                'klasifikasi_id' => 1, // pastikan ada klasifikasi id=1
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('surat_masuk')->insert($rows);
    }
}
