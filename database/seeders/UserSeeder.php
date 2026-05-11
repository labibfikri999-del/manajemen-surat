<?php

namespace Database\Seeders;

use App\Models\Instansi;
use App\Models\SDM\SdmPegawai;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Direktur Yayasan (Admin)
        User::updateOrCreate(
            ['email' => 'direktur@yarsi-ntb.ac.id'],
            [
                'name' => 'Direktur Yayasan',
                'username' => 'direktur',
                'password' => Hash::make('direktur@2025'),
                'plain_password' => 'direktur@2025',
                'role' => 'direktur',
                'jabatan' => 'Direktur Yayasan YARSI NTB',
                'telepon' => '08123456789',
                'is_active' => true,
                'module_access' => ['surat'],
            ]
        );

        // 2. Staff Direktur
        User::updateOrCreate(
            ['email' => 'staff@yarsi-ntb.ac.id'],
            [
                'name' => 'Staff Direktur',
                'username' => 'staff',
                'password' => Hash::make('staff@2025'),
                'plain_password' => 'staff@2025',
                'role' => 'staff',
                'jabatan' => 'Staff Administrasi Direktur',
                'telepon' => '08234567890',
                'is_active' => true,
                'module_access' => ['surat'],
            ]
        );

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
                User::updateOrCreate(
                    ['email' => $userData['email']],
                    [
                        'name' => $userData['name'],
                        'username' => 'instansi'.($index + 1),
                        'password' => Hash::make($userData['password']),
                        'plain_password' => $userData['password'],
                        'role' => 'instansi',
                        'instansi_id' => $instansi->id,
                        'jabatan' => $userData['name'],
                        'telepon' => '081234567'.str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                        'is_active' => true,
                        'module_access' => ['surat'],
                    ]
                );
            }
        }

        $kepegawaianUsers = [
            [
                'name' => 'Pegawai Kepegawaian 1',
                'username' => 'pegawai1',
                'email' => 'pegawai1@kepegawaian.local',
                'password' => 'Mataram',
                'role' => 'pegawai',
                'jabatan' => 'Pegawai',
                'module_access' => ['kepegawaian'],
            ],
            [
                'name' => 'Staff Kepegawaian',
                'username' => 'staff',
                'email' => 'staff@yarsi-ntb.ac.id',
                'password' => 'staff2026',
                'role' => 'staff',
                'jabatan' => 'Staff Kepegawaian',
                'module_access' => ['surat', 'kepegawaian'],
            ],
            [
                'name' => 'Sekjen Yayasan',
                'username' => 'sekjen',
                'email' => 'sekjen@yarsi-ntb.ac.id',
                'password' => 'sekjen2026',
                'role' => 'sekjen',
                'jabatan' => 'Sekjen Yayasan',
                'module_access' => ['kepegawaian'],
            ],
        ];

        foreach ($kepegawaianUsers as $userData) {
            $user = User::where('username', $userData['username'])
                ->orWhere('email', $userData['email'])
                ->first();

            $attributes = [
                'name' => $userData['name'],
                'username' => $userData['username'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'plain_password' => $userData['password'],
                'role' => $userData['role'],
                'jabatan' => $userData['jabatan'],
                'is_active' => true,
                'must_change_password' => false,
                'module_access' => $userData['module_access'],
            ];

            if ($user) {
                $user->update($attributes);
            } else {
                $user = User::create($attributes);
            }

            if ($userData['role'] === 'pegawai' && Schema::hasTable('sdm_pegawais')) {
                $pegawaiAttributes = [
                    'user_id' => $user->id,
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'role' => 'Pegawai',
                    'status' => 'active',
                ];

                if (Schema::hasColumn('sdm_pegawais', 'jabatan')) {
                    $pegawaiAttributes['jabatan'] = 'Pegawai';
                }

                if (Schema::hasColumn('sdm_pegawais', 'unit_kerja')) {
                    $pegawaiAttributes['unit_kerja'] = 'Kepegawaian';
                }

                SdmPegawai::updateOrCreate(
                    ['nip' => $userData['username']],
                    $pegawaiAttributes
                );
            }
        }
    }
}
