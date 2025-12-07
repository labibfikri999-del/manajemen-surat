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
            'email' => 'direktur@yarsi-ntb.ac.id',
            'password' => Hash::make('direktur@2025'),
            'role' => 'direktur',
            'jabatan' => 'Direktur Yayasan YARSI NTB',
            'telepon' => '08123456789',
            'is_active' => true,
        ]);

        // 2. Staff Direktur
        User::create([
            'name' => 'Staff Direktur',
            'email' => 'staff@yarsi-ntb.ac.id',
            'password' => Hash::make('staff@2025'),
            'role' => 'staff',
            'jabatan' => 'Staff Administrasi Direktur',
            'telepon' => '08234567890',
            'is_active' => true,
        ]);

        // 3. User untuk setiap instansi (7 user) dengan password mudah
        $instansiUsers = [
            ['instansi_kode' => 'RSI', 'name' => 'Admin RSI Siti Hajar', 'email' => 'instansi1@yarsi-ntb.ac.id', 'password' => 'mataram10'],
            ['instansi_kode' => 'IKYM', 'name' => 'Admin Institut Kesehatan', 'email' => 'instansi2@yarsi-ntb.ac.id', 'password' => 'mataram10'],
            ['instansi_kode' => 'SMK', 'name' => 'Admin SMK Yarsi', 'email' => 'instansi3@yarsi-ntb.ac.id', 'password' => 'mataram10'],
            ['instansi_kode' => 'SMAIT', 'name' => 'Admin SMA IT Yarsi', 'email' => 'instansi4@yarsi-ntb.ac.id', 'password' => 'mataram10'],
            ['instansi_kode' => 'SMPIT', 'name' => 'Admin SMP IT Yarsi', 'email' => 'instansi5@yarsi-ntb.ac.id', 'password' => 'mataram10'],
            ['instansi_kode' => 'SDIT', 'name' => 'Admin SD IT Fauziah', 'email' => 'instansi6@yarsi-ntb.ac.id', 'password' => 'mataram10'],
            ['instansi_kode' => 'TK', 'name' => 'Admin TK Yarsi', 'email' => 'instansi7@yarsi-ntb.ac.id', 'password' => 'mataram10'],
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
