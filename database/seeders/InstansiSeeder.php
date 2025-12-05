<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instansi;

class InstansiSeeder extends Seeder
{
    public function run(): void
    {
        $instansis = [
            [
                'nama' => 'Rumah Sakit Islam Siti Hajar Mataram',
                'kode' => 'RSI',
                'alamat' => 'Jl. Kesehatan No. 1, Mataram',
                'telepon' => '0370-123456',
                'email' => 'rsi@yarsi-ntb.ac.id',
            ],
            [
                'nama' => 'Institut Kesehatan Yarsi Mataram',
                'kode' => 'IKYM',
                'alamat' => 'Jl. Pendidikan No. 2, Mataram',
                'telepon' => '0370-234567',
                'email' => 'ikym@yarsi-ntb.ac.id',
            ],
            [
                'nama' => 'SMK Yarsi Mataram',
                'kode' => 'SMK',
                'alamat' => 'Jl. Pendidikan No. 3, Mataram',
                'telepon' => '0370-345678',
                'email' => 'smk@yarsi-ntb.ac.id',
            ],
            [
                'nama' => 'SMA IT Yarsi Mataram',
                'kode' => 'SMAIT',
                'alamat' => 'Jl. Pendidikan No. 4, Mataram',
                'telepon' => '0370-456789',
                'email' => 'smait@yarsi-ntb.ac.id',
            ],
            [
                'nama' => 'SMP IT Yarsi Mataram',
                'kode' => 'SMPIT',
                'alamat' => 'Jl. Pendidikan No. 5, Mataram',
                'telepon' => '0370-567890',
                'email' => 'smpit@yarsi-ntb.ac.id',
            ],
            [
                'nama' => 'SD IT Fauziah Yarsi Mataram',
                'kode' => 'SDIT',
                'alamat' => 'Jl. Pendidikan No. 6, Mataram',
                'telepon' => '0370-678901',
                'email' => 'sdit@yarsi-ntb.ac.id',
            ],
            [
                'nama' => 'TK Yarsi Mataram',
                'kode' => 'TK',
                'alamat' => 'Jl. Pendidikan No. 7, Mataram',
                'telepon' => '0370-789012',
                'email' => 'tk@yarsi-ntb.ac.id',
            ],
        ];

        foreach ($instansis as $instansi) {
            Instansi::create($instansi);
        }
    }
}
