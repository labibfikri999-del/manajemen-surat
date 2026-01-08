<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mock Data for "Pro" Dashboard Demonstration
        // 1. Shift Hari Ini
        $shifts = [
            (object)['name' => 'Dr. Farhan', 'role' => 'Dokter Umum', 'shift' => 'Pagi (07:00 - 14:00)', 'status' => 'On Duty', 'img' => 'F'],
            (object)['name' => 'Sr. Siti Aminah', 'role' => 'Perawat Senior', 'shift' => 'Pagi (07:00 - 14:00)', 'status' => 'On Duty', 'img' => 'S'],
            (object)['name' => 'Drg. Budi', 'role' => 'Dokter Gigi', 'shift' => 'Siang (14:00 - 21:00)', 'status' => 'Scheduled', 'img' => 'B'],
            (object)['name' => 'Sr. Rina', 'role' => 'Perawat', 'shift' => 'Siang (14:00 - 21:00)', 'status' => 'Scheduled', 'img' => 'R'],
        ];

        // 2. Statistik Cepat
        $stats = [
            'total_pegawai' => 142,
            'hadir_hari_ini' => 98, // prosentase 98/142
            'cuti' => 4,
            'sakit' => 2,
        ];
        
        // 3. Action Items (STR Expiring)
        $alerts = [
            (object)['message' => 'STR Dr. Andi expired dalam 15 hari', 'type' => 'critical'],
            (object)['message' => 'Kontrak Sr. Dewi berakhir bulan depan', 'type' => 'warning'],
            (object)['message' => '3 Pengajuan Cuti menunggu persetujuan', 'type' => 'info'],
        ];

        return view('sdm.dashboard', compact('shifts', 'stats', 'alerts'));
    }
}
