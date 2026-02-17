<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SDM\SdmPegawai;
use Carbon\Carbon;

class SdmDummySeeder extends Seeder
{
    public function run()
    {
        // Clear existing data (optional, but good for clean slate testing if acceptable)
        // SdmPegawai::truncate(); // Better not truncate if user has data. 
        // I will just add new data.

        $faker = \Faker\Factory::create('id_ID');

        $pendidikanlist = ['SMA/SMK', 'D3', 'D4', 'S1', 'S2', 'S3', 'Lainnya'];
        $jabatanlist = ['Perawat Pelaksana', 'Dokter Umum', 'Staff Administrasi', 'Apoteker', 'Bidan', 'Security', 'Cleaning Service', 'Ahli Gizi'];
        $unitkerjalist = ['IGD', 'Poli Umum', 'Rawat Inap', 'Farmasi', 'Tata Usaha', 'Keamanan', 'Gizi'];
        $statuslist = ['Tetap', 'Kontrak', 'Magang'];

        for ($i = 0; $i < 50; $i++) {
            $gender = $faker->randomElement(['L', 'P']);
            $pendidikan = $faker->randomElement($pendidikanlist);
            
            // Logic NIDN: usually for academic/lecturer, but let's say 20% have it
            $nidn = ($faker->boolean(20)) ? $faker->numerify('##########') : null;

            SdmPegawai::create([
                'name' => $faker->name($gender == 'L' ? 'male' : 'female'),
                'nip' => $faker->unique()->numerify('19##########'),
                'role' => 'staff', // Default role in system
                'status' => 'active',
                'join_date' => $faker->date(),
                'phone' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                
                // New Fields
                'nidn' => $nidn,
                'jenis_kelamin' => $gender,
                'pendidikan_terakhir' => $pendidikan,
                'status_kepegawaian' => $faker->randomElement($statuslist),
                'jabatan' => $faker->randomElement($jabatanlist),
                'unit_kerja' => $faker->randomElement($unitkerjalist),
            ]);
        }
    }
}
