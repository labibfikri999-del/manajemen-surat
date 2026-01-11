<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SDM\SdmPegawai;
use App\Models\SDM\SdmAttendance;
use App\Models\SDM\SdmPayroll;
use DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        return view('sdm.laporan.index');
    }

    public function absensi(Request $request)
    {
        $month = (int) ($request->month ?? date('m'));
        $year = (int) ($request->year ?? date('Y'));

        // Aggregate attendance status per employee for the selected month
        $attendanceData = SdmPegawai::where('status', 'active')
            ->with(['attendances' => function($q) use ($month, $year) {
                $q->whereMonth('date', $month)->whereYear('date', $year);
            }])
            ->get()
            ->map(function($pegawai) {
                return [
                    'name' => $pegawai->name,
                    'role' => $pegawai->role,
                    'hadir' => $pegawai->attendances->where('status', 'Hadir')->count(),
                    'telat' => $pegawai->attendances->where('status', 'Telat')->count(),
                    'ijin' => $pegawai->attendances->where('status', 'Ijin')->count(),
                    'sakit' => $pegawai->attendances->where('status', 'Sakit')->count(),
                    'total' => $pegawai->attendances->count(),
                ];
            });

        return view('sdm.laporan.absensi', compact('attendanceData', 'month', 'year'));
    }

    public function gaji(Request $request)
    {
        $month = (int) ($request->month ?? date('m'));
        $year = (int) ($request->year ?? date('Y'));

        $payrolls = SdmPayroll::where('month', $month)
            ->where('year', $year)
            ->with('pegawai')
            ->get();

        $summary = [
            'total_expenditure' => $payrolls->sum('net_salary'),
            'total_basic' => $payrolls->sum('basic_salary'),
            'total_allowances' => $payrolls->sum('allowances'),
            'count' => $payrolls->count()
        ];

        return view('sdm.laporan.gaji', compact('payrolls', 'summary', 'month', 'year'));
    }
}
