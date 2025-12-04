<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Klasifikasi;

class KlasifikasiSeeder extends Seeder {
    public function run() {
        Klasifikasi::insert([
            ['nama'=>'Umum','created_at'=>now(),'updated_at'=>now()],
            ['nama'=>'Dinas','created_at'=>now(),'updated_at'=>now()],
            ['nama'=>'Rahasia','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
