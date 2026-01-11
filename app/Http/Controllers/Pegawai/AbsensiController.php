<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SDM\SdmPegawai;
use App\Models\SDM\SdmAttendance;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pegawai = SdmPegawai::where('email', $user->email)->first();

        if (!$pegawai) {
            return redirect()->route('pegawai.dashboard')->with('error', 'Akun Anda tidak terhubung dengan Data Pegawai. Hubungi HR.');
        }

        $attendances = SdmAttendance::where('sdm_pegawai_id', $pegawai->id)
            ->orderBy('date', 'desc')
            ->paginate(15);

        // Stats for current month
        $currentMonth = Carbon::now()->month;
        $stats = [
            'hadir' => SdmAttendance::where('sdm_pegawai_id', $pegawai->id)->whereMonth('date', $currentMonth)->where('status', 'Hadir')->count(),
            'telat' => SdmAttendance::where('sdm_pegawai_id', $pegawai->id)->whereMonth('date', $currentMonth)->where('status', 'Telat')->count(),
            'sakit' => SdmAttendance::where('sdm_pegawai_id', $pegawai->id)->whereMonth('date', $currentMonth)->where('status', 'Sakit')->count(),
            'total' => SdmAttendance::where('sdm_pegawai_id', $pegawai->id)->whereMonth('date', $currentMonth)->count(),
        ];

        return view('pegawai.absensi.index', compact('attendances', 'stats'));
    }
}
