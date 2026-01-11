<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SDM\SdmPegawai;
use App\Models\SDM\SdmLeave;
use Carbon\Carbon;

class CutiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pegawai = SdmPegawai::where('email', $user->email)->first();

        if (!$pegawai) {
            return redirect()->route('pegawai.dashboard')->with('error', 'Akun Anda tidak terhubung dengan Data Pegawai. Hubungi HR.');
        }

        $leaves = SdmLeave::where('sdm_pegawai_id', $pegawai->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pegawai.cuti.index', compact('leaves'));
    }

    public function create()
    {
        return view('pegawai.cuti.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $user = Auth::user();
        $pegawai = SdmPegawai::where('email', $user->email)->first();

        if (!$pegawai) {
             return redirect()->back()->with('error', 'Data Pegawai tidak ditemukan.');
        }

        SdmLeave::create([
            'sdm_pegawai_id' => $pegawai->id,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'Pending',
        ]);

        return redirect()->route('pegawai.cuti.index')->with('success', 'Pengajuan cuti berhasil dikirim.');
    }
}
