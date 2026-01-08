<?php

namespace Database\Seeders;

use App\Models\Klasifikasi;
use Illuminate\Database\Seeder;

class KlasifikasiSeeder extends Seeder
{
    public function run()
    {
        Klasifikasi::insert([
            ['nama' => 'Umum', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Dinas', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Rahasia', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
