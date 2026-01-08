<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mock Data for "Employee Portal" Dashboard
        
        // 1. Attendance Status
        $attendance = [
            'status' => 'Hadir',
            'check_in' => '07:15',
            'check_out' => '-',
            'working_hours' => '4J 30M',
            'date' => now()->translatedFormat('d F Y'),
        ];

        // 2. Leave Quota (Cuti)
        $leave = [
            'annual' => ['total' => 12, 'used' => 3, 'remaining' => 9],
            'sick' => ['used' => 1],
        ];

        // 3. Recent Payslips
        $payslips = [
            (object)['month' => 'Januari 2026', 'amount' => 'Rp 5.450.000', 'date' => '25 Jan 2026', 'status' => 'Lunas'],
            (object)['month' => 'Desember 2025', 'amount' => 'Rp 5.400.000', 'date' => '24 Des 2025', 'status' => 'Lunas'],
        ];

        // 4. Announcements
        $announcements = [
            (object)['title' => 'Jadwal Akreditasi RS', 'date' => '2 Hari yang lalu', 'content' => 'Persiapan audit visitasi surveyor pada tanggal...'],
            (object)['title' => 'Update Protokol Kesehatan', 'date' => '1 Minggu yang lalu', 'content' => 'Pembaruan SOP penggunaan APD di ruang isolasi...'],
        ];

        return view('pegawai.dashboard', compact('attendance', 'leave', 'payslips', 'announcements'));
    }
}
