<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SDM\SdmAttendance;
use App\Models\SDM\SdmPegawai;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? Carbon::today()->toDateString();

        $attendances = SdmAttendance::whereDate('date', $date)
            ->with('pegawai')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get pegawais who haven't attended today (only if viewing today)
        $pegawais = [];
        if ($date == Carbon::today()->toDateString()) {
            $pegawais = SdmPegawai::where('status', 'active')
                ->whereDoesntHave('attendances', function($q) use ($date) {
                    $q->whereDate('date', $date);
                })
                ->orderBy('name')
                ->get();
        }

        return view('sdm.absen.index', compact('attendances', 'pegawais', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required',
            'status' => 'required',
        ]);

        // Check if already attended
        $exists = SdmAttendance::where('sdm_pegawai_id', $request->pegawai_id)
            ->whereDate('date', Carbon::today())
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Pegawai ini sudah absen hari ini.');
        }

        SdmAttendance::create([
            'sdm_pegawai_id' => $request->pegawai_id,
            'date' => Carbon::today(),
            'clock_in' => $request->clock_in ?? Carbon::now()->format('H:i:s'),
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        return redirect()->back()->with('success', 'Berhasil mencatat absensi masuk.');
    }

    public function update(Request $request, $id)
    {
        $attendance = SdmAttendance::findOrFail($id);
        
        // Handle clock out
        if ($request->has('clock_out_action')) {
            $attendance->update([
                'clock_out' => Carbon::now()->format('H:i:s')
            ]);
            return redirect()->back()->with('success', 'Berhasil mencatat jam pulang.');
        }

        // Handle normal update
        $attendance->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
        ]);

        return redirect()->back()->with('success', 'Data absensi diperbarui.');
    }

    public function destroy($id)
    {
        $attendance = SdmAttendance::findOrFail($id);
        $attendance->delete();
        return redirect()->back()->with('success', 'Data absensi dihapus.');
    }
}
