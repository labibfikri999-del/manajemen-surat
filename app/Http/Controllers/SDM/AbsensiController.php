<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SDM\SdmAttendance;
use App\Models\SDM\SdmPegawai;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        $attendances = SdmAttendance::whereDate('date', Carbon::today())
            ->with('pegawai')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $pegawais = SdmPegawai::where('status', 'active')
            ->whereDoesntHave('attendances', function($q) {
                $q->whereDate('date', Carbon::today());
            })
            ->get();

        return view('sdm.absen.index', compact('attendances', 'pegawais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required',
            'clock_in' => 'required',
            'status' => 'required',
        ]);

        SdmAttendance::create([
            'sdm_pegawai_id' => $request->pegawai_id,
            'date' => Carbon::today(),
            'clock_in' => $request->clock_in,
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        return redirect()->back()->with('success', 'Berhasil melakukan absensi.');
    }
}
