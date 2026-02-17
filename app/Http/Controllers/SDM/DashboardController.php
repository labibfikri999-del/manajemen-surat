<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Shift Hari Ini (Existing Logic)
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

        // 2. Statistik SDM (Updated for New Design)
        $totalPegawai = \App\Models\SDM\SdmPegawai::where('status', 'active')->count();
        $lakiLaki = \App\Models\SDM\SdmPegawai::where('status', 'active')->where('jenis_kelamin', 'L')->count();
        $perempuan = \App\Models\SDM\SdmPegawai::where('status', 'active')->where('jenis_kelamin', 'P')->count();
        
        // Statistik Pendidikan
        $pendidikan = \App\Models\SDM\SdmPegawai::where('status', 'active')
            ->select('pendidikan_terakhir', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('pendidikan_terakhir')
            ->pluck('total', 'pendidikan_terakhir')->toArray();
        
        // Jabatan Aktif (Limit to top 5 for simplicity or specific logic)
        $jabatan = \App\Models\SDM\SdmPegawai::where('status', 'active')
            ->whereNotNull('jabatan')
            ->select('jabatan', 'role', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('jabatan', 'role')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // Status Kepegawaian (Tetap, Kontrak, etc)
        $statusKepegawaian = \App\Models\SDM\SdmPegawai::where('status', 'active')
            ->select('status_kepegawaian', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('status_kepegawaian')
            ->pluck('total', 'status_kepegawaian')->toArray();

        $stats = [
            'total_pegawai' => $totalPegawai,
            'laki_laki' => $lakiLaki,
            'perempuan' => $perempuan,
            'pendidikan' => $pendidikan,
            'jabatan' => $jabatan,
            'status_kepegawaian' => $statusKepegawaian,
        ];
        
        // 3. Action Items (alerts) - Keep existing logic
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
