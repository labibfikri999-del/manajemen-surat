<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Shift Hari Ini
        $shifts = \App\Models\SDM\SdmShift::whereDate('date', \Carbon\Carbon::today())
            ->with('pegawai')
            ->get()
            ->map(function($shift) {
                return (object)[
                    'name' => $shift->pegawai->name,
                    'role' => $shift->pegawai->role,
                    'shift' => $shift->shift_name . ' (' . \Carbon\Carbon::parse($shift->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($shift->end_time)->format('H:i') . ')',
                    'status' => $shift->status,
                    'img' => substr($shift->pegawai->name, 0, 1),
                ];
            });

        // 2. Statistik Cepat
        $stats = [
            'total_pegawai' => \App\Models\SDM\SdmPegawai::where('status', 'active')->count(),
            'hadir_hari_ini' => \App\Models\SDM\SdmAttendance::whereDate('date', \Carbon\Carbon::today())->where('status', 'Hadir')->count(),
            'cuti' => \App\Models\SDM\SdmLeave::whereDate('start_date', '<=', \Carbon\Carbon::today())
                        ->whereDate('end_date', '>=', \Carbon\Carbon::today())
                        ->where('type', 'Cuti Tahunan')
                        ->count(),
            'sakit' => \App\Models\SDM\SdmLeave::whereDate('start_date', '<=', \Carbon\Carbon::today())
                        ->whereDate('end_date', '>=', \Carbon\Carbon::today())
                        ->where('type', 'Sakit')
                        ->count(),
        ];
        
        // 3. Action Items (alerts)
        $alerts = collect();

        // Expiring STRs
        $expiringStrs = \App\Models\SDM\SdmStr::where('expiry_date', '<', \Carbon\Carbon::now()->addDays(30))
                        ->with('pegawai')
                        ->get();
        foreach($expiringStrs as $str) {
            $alerts->push((object)[
                'message' => $str->type . ' ' . $str->pegawai->name . ' expired dalam ' . $str->expiry_date->diffInDays(\Carbon\Carbon::now()) . ' hari',
                'type' => 'critical'
            ]);
        }

        // Pending Leaves
        $pendingLeaves = \App\Models\SDM\SdmLeave::where('status', 'Pending')->count();
        if($pendingLeaves > 0) {
            $alerts->push((object)[
                'message' => $pendingLeaves . ' Pengajuan Cuti menunggu persetujuan',
                'type' => 'info'
            ]);
        }

        return view('sdm.dashboard', compact('shifts', 'stats', 'alerts'));
    }
}
