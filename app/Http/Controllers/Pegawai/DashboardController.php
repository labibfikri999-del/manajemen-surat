<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\SDM\SdmPegawai;
use App\Models\SDM\SdmAttendance;
use App\Models\SDM\SdmPayroll;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pegawai = SdmPegawai::where('email', $user->email)->first();

        // 1. Attendance Status
        $attendanceData = [
            'status' => 'Absen',
            'check_in' => '-',
            'check_out' => '-',
            'working_hours' => '-',
            'date' => now()->translatedFormat('d F Y'),
            'can_checkout' => false
        ];

        if ($pegawai) {
            $todayAttendance = SdmAttendance::where('sdm_pegawai_id', $pegawai->id)
                ->whereDate('date', Carbon::today())
                ->first();

            if ($todayAttendance) {
                $attendanceData['status'] = $todayAttendance->status;
                $attendanceData['check_in'] = $todayAttendance->clock_in ? Carbon::parse($todayAttendance->clock_in)->format('H:i') : '-';
                $attendanceData['check_out'] = $todayAttendance->clock_out ? Carbon::parse($todayAttendance->clock_out)->format('H:i') : '-';
                $attendanceData['can_checkout'] = $todayAttendance->clock_in && !$todayAttendance->clock_out;
                
                if ($todayAttendance->clock_in && $todayAttendance->clock_out) {
                     $start = Carbon::parse($todayAttendance->clock_in);
                     $end = Carbon::parse($todayAttendance->clock_out);
                     $diff = $start->diff($end);
                     $attendanceData['working_hours'] = $diff->h . 'J ' . $diff->i . 'M';
                } elseif ($todayAttendance->clock_in) {
                     $start = Carbon::parse($todayAttendance->clock_in);
                     $diff = $start->diff(now());
                     $attendanceData['working_hours'] = $diff->h . 'J ' . $diff->i . 'M';
                }
            }
        }

        // 2. Leave Quota (Cuti) - Mock/Calculated
        // In a real app, we would have a LeaveBalance model. For now, we mock based on usage.
        $totalLeave = 12;
        $usedLeave = 0; // Fetch from sdm_leaves if implemented
        $leave = [
            'annual' => ['total' => $totalLeave, 'used' => $usedLeave, 'remaining' => $totalLeave - $usedLeave],
            'sick' => ['used' => 0],
        ];

        // 3. Recent Payslips
        $payslips = [];
        if ($pegawai) {
            $payslips = SdmPayroll::where('sdm_pegawai_id', $pegawai->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($p) {
                    return (object)[
                        'month' => Carbon::create()->month($p->month)->translatedFormat('F') . ' ' . $p->year,
                        'amount' => 'Rp ' . number_format($p->net_salary, 0, ',', '.'),
                        'date' => $p->created_at->translatedFormat('d M Y'),
                        'status' => $p->status
                    ];
                });
        }

        // 4. Announcements (Static for now)
        $announcements = [
            (object)['title' => 'Jadwal Akreditasi RS', 'date' => '2 Hari yang lalu', 'content' => 'Persiapan audit visitasi surveyor pada tanggal...'],
            (object)['title' => 'Update Protokol Kesehatan', 'date' => '1 Minggu yang lalu', 'content' => 'Pembaruan SOP penggunaan APD di ruang isolasi...'],
        ];

        return view('pegawai.dashboard', compact('attendanceData', 'leave', 'payslips', 'announcements', 'pegawai'));
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $pegawai = SdmPegawai::where('email', $user->email)->first();

        if (!$pegawai) {
             return redirect()->back()->with('error', 'Data pegawai tidak ditemukan.');
        }

        $attendance = SdmAttendance::where('sdm_pegawai_id', $pegawai->id)
            ->whereDate('date', Carbon::today())
            ->whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->first();

        if ($attendance) {
            $attendance->update([
                'clock_out' => Carbon::now()->format('H:i:s'),
                'status' => 'Hadir' // Confirm status
            ]);
            return redirect()->back()->with('success', 'Berhasil Check Out.');
        }

        return redirect()->back()->with('error', 'Anda belum Check In atau sudah Check Out hari ini.');
    }
}
