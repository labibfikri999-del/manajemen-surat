<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SDM\SdmPegawai;
use App\Models\SDM\SdmPayroll;

class GajiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pegawai = SdmPegawai::where('email', $user->email)->first();

        if (!$pegawai) {
            return redirect()->route('pegawai.dashboard')->with('error', 'Akun Anda tidak terhubung dengan Data Pegawai. Hubungi HR.');
        }

        $payrolls = SdmPayroll::where('sdm_pegawai_id', $pegawai->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('pegawai.gaji.index', compact('payrolls'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $pegawai = SdmPegawai::where('email', $user->email)->first();

        if (!$pegawai) {
             return redirect()->route('pegawai.dashboard')->with('error', 'Data Pegawai tidak ditemukan.');
        }

        $payroll = SdmPayroll::where('sdm_pegawai_id', $pegawai->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('pegawai.gaji.show', compact('payroll'));
    }
}
