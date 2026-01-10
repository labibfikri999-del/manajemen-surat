<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SDM\SdmPegawai;
use App\Models\SDM\SdmShift;
use App\Models\SDM\SdmAttendance;
use App\Models\SDM\SdmLeave;
use App\Models\SDM\SdmStr;
use Carbon\Carbon;

class SdmDashboardSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Employees
        $employees = [
            ['name' => 'Dr. Farhan', 'role' => 'Dokter Umum', 'nip' => '1001'],
            ['name' => 'Sr. Siti Aminah', 'role' => 'Perawat Senior', 'nip' => '2001'],
            ['name' => 'Drg. Budi', 'role' => 'Dokter Gigi', 'nip' => '1002'],
            ['name' => 'Sr. Rina', 'role' => 'Perawat', 'nip' => '2002'],
            ['name' => 'Dr. Andi', 'role' => 'Dokter Spesialis', 'nip' => '1003'],
            ['name' => 'Sr. Dewi', 'role' => 'Perawat', 'nip' => '2003'],
            ['name' => 'Budi Santoso', 'role' => 'Staff Admin', 'nip' => '3001'],
            ['name' => 'Ratna Sari', 'role' => 'Apoteker', 'nip' => '4001'],
        ];

        foreach ($employees as $emp) {
            SdmPegawai::create(array_merge($emp, [
                'status' => 'active',
                'join_date' => Carbon::now()->subYears(rand(1, 5)),
                'email' => strtolower(str_replace(['.', ' '], ['', ''], $emp['name'])) . '@hospital.com',
            ]));
        }

        // 2. Create Shifts for Today
        $pegawais = SdmPegawai::all();
        $shifts = ['Pagi', 'Siang', 'Malam'];
        
        foreach($pegawais as $index => $pegawai) {
            SdmShift::create([
                'sdm_pegawai_id' => $pegawai->id,
                'shift_name' => $shifts[$index % 3],
                'start_time' => ($index % 3 == 0) ? '07:00' : (($index % 3 == 1) ? '14:00' : '21:00'),
                'end_time' => ($index % 3 == 0) ? '14:00' : (($index % 3 == 1) ? '21:00' : '07:00'),
                'date' => Carbon::today(),
                'status' => ($index % 3 == 0) ? 'On Duty' : 'Scheduled',
            ]);
        }

        // 3. Create Attendance (Some Present, Some Late)
        foreach($pegawais->take(5) as $pegawai) {
            SdmAttendance::create([
                'sdm_pegawai_id' => $pegawai->id,
                'date' => Carbon::today(),
                'clock_in' => Carbon::now()->subHours(rand(1, 4))->format('H:i'),
                'status' => 'Hadir',
            ]);
        }

        // 4. Create Leaves
        SdmLeave::create([
            'sdm_pegawai_id' => $pegawais->last()->id,
            'type' => 'Cuti Tahunan',
            'start_date' => Carbon::tomorrow(),
            'end_date' => Carbon::tomorrow()->addDays(3),
            'reason' => 'Liburan Keluarga',
            'status' => 'Pending',
        ]);

        // 5. Create STR Alerts
        SdmStr::create([
            'sdm_pegawai_id' => $pegawais->where('role', 'Dokter Spesialis')->first()->id,
            'number' => 'STR-123456',
            'type' => 'STR',
            'expiry_date' => Carbon::now()->addDays(15),
        ]);
    }
}
