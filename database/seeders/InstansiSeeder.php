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
                'nama' => 'Rumah Sakit YARSI NTB',
                'kode' => 'RS',
                'alamat' => 'Jl. Kesehatan No. 1, Mataram',
                'telepon' => '0370-123456',
                'email' => 'rs@yarsi-ntb.ac.id',
            ],
            [
                'nama' => 'Akademi Kebidanan YARSI NTB',
                'kode' => 'AKBID',
                'alamat' => 'Jl. Pendidikan No. 2, Mataram',
                'telepon' => '0370-234567',
                'email' => 'akbid@yarsi-ntb.ac.id',
            ],
            [
                'nama' => 'Akademi Keperawatan YARSI NTB',
                'kode' => 'AKPER',
                'alamat' => 'Jl. Pendidikan No. 3, Mataram',
                'telepon' => '0370-345678',
                'email' => 'akper@yarsi-ntb.ac.id',
            ],
            [
                'nama' => 'Sekolah Tinggi Ilmu Kesehatan YARSI NTB',
                'kode' => 'STIKES',
                'alamat' => 'Jl. Pendidikan No. 4, Mataram',
                'telepon' => '0370-456789',
                'email' => 'stikes@yarsi-ntb.ac.id',
            ],
            [
                'nama' => 'Klinik Kesehatan YARSI NTB',
                'kode' => 'KLINIK',
                'alamat' => 'Jl. Kesehatan No. 5, Mataram',
                'telepon' => '0370-567890',
                'email' => 'klinik@yarsi-ntb.ac.id',
            ],
            [
                'nama' => 'Laboratorium YARSI NTB',
                'kode' => 'LAB',
                'alamat' => 'Jl. Penelitian No. 6, Mataram',
                'telepon' => '0370-678901',
                'email' => 'lab@yarsi-ntb.ac.id',
            ],
            [
                'nama' => 'Unit Pelaksana Teknis YARSI NTB',
                'kode' => 'UPT',
                'alamat' => 'Jl. Administrasi No. 7, Mataram',
                'telepon' => '0370-789012',
                'email' => 'upt@yarsi-ntb.ac.id',
            ],
        ];

        foreach ($instansis as $instansi) {
            Instansi::create($instansi);
        }
    }
}
