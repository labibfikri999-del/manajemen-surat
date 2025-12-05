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
            'name' => 'Direktur Yayasan',
            'email' => 'direktur@yarsi.ac.id',
            'password' => Hash::make('direktur123'),
            'role' => 'direktur',
            'jabatan' => 'Direktur Yayasan YARSI NTB',
            'telepon' => '08123456789',
            'is_active' => true,
        ]);

        // 2. Staff Direktur
        User::create([
            'name' => 'Staff Direktur',
            'email' => 'staff@yarsi.ac.id',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
            'jabatan' => 'Staff Administrasi Direktur',
            'telepon' => '08234567890',
            'is_active' => true,
        ]);

        // 3. User untuk setiap instansi (7 user) dengan password mudah
        $instansiUsers = [
            ['instansi_kode' => 'RSI', 'name' => 'Admin RSI Siti Hajar', 'email' => 'rsi@yarsi.ac.id', 'password' => 'rsi123'],
            ['instansi_kode' => 'IKYM', 'name' => 'Admin Institut Kesehatan', 'email' => 'ikym@yarsi.ac.id', 'password' => 'ikym123'],
            ['instansi_kode' => 'SMK', 'name' => 'Admin SMK Yarsi', 'email' => 'smk@yarsi.ac.id', 'password' => 'smk123'],
            ['instansi_kode' => 'SMAIT', 'name' => 'Admin SMA IT Yarsi', 'email' => 'smait@yarsi.ac.id', 'password' => 'smait123'],
            ['instansi_kode' => 'SMPIT', 'name' => 'Admin SMP IT Yarsi', 'email' => 'smpit@yarsi.ac.id', 'password' => 'smpit123'],
            ['instansi_kode' => 'SDIT', 'name' => 'Admin SD IT Fauziah', 'email' => 'sdit@yarsi.ac.id', 'password' => 'sdit123'],
            ['instansi_kode' => 'TK', 'name' => 'Admin TK Yarsi', 'email' => 'tk@yarsi.ac.id', 'password' => 'tk123'],
        ];

        foreach ($instansiUsers as $index => $userData) {
            $instansi = Instansi::where('kode', $userData['instansi_kode'])->first();
            
            if ($instansi) {
                User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'role' => 'instansi',
                    'instansi_id' => $instansi->id,
                    'jabatan' => $userData['name'],
                    'telepon' => '081234567' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                    'is_active' => true,
                ]);
            }
        }
    }
}
