<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Instansi;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Direktur Yayasan (Admin)
        User::create([
            'name' => 'Dr. H. Ahmad Direktur',
            'email' => 'direktur@yarsi-ntb.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'direktur',
            'jabatan' => 'Direktur Yayasan YARSI NTB',
            'telepon' => '08123456789',
            'is_active' => true,
        ]);

        // 2. Staff Direktur
        User::create([
            'name' => 'Siti Aminah',
            'email' => 'staff@yarsi-ntb.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'jabatan' => 'Staff Administrasi Direktur',
            'telepon' => '08234567890',
            'is_active' => true,
        ]);

        // 3. User untuk setiap instansi (7 user)
        $instansiUsers = [
            ['instansi_kode' => 'RS', 'name' => 'dr. Budi Santoso', 'jabatan' => 'Admin RS YARSI'],
            ['instansi_kode' => 'AKBID', 'name' => 'Dewi Lestari, S.Keb', 'jabatan' => 'Admin Akademi Kebidanan'],
            ['instansi_kode' => 'AKPER', 'name' => 'Eko Prasetyo, S.Kep', 'jabatan' => 'Admin Akademi Keperawatan'],
            ['instansi_kode' => 'STIKES', 'name' => 'Fitri Handayani, M.Kes', 'jabatan' => 'Admin STIKES'],
            ['instansi_kode' => 'KLINIK', 'name' => 'dr. Gunawan', 'jabatan' => 'Admin Klinik Kesehatan'],
            ['instansi_kode' => 'LAB', 'name' => 'Hendra Wijaya, S.Si', 'jabatan' => 'Admin Laboratorium'],
            ['instansi_kode' => 'UPT', 'name' => 'Indah Permata', 'jabatan' => 'Admin UPT'],
        ];

        foreach ($instansiUsers as $index => $userData) {
            $instansi = Instansi::where('kode', $userData['instansi_kode'])->first();
            
            if ($instansi) {
                User::create([
                    'name' => $userData['name'],
                    'email' => strtolower($userData['instansi_kode']) . '@yarsi-ntb.ac.id',
                    'password' => Hash::make('password123'),
                    'role' => 'instansi',
                    'instansi_id' => $instansi->id,
                    'jabatan' => $userData['jabatan'],
                    'telepon' => '08' . str_pad($index + 3, 9, rand(100000000, 999999999)),
                    'is_active' => true,
                ]);
            }
        }
    }
}
