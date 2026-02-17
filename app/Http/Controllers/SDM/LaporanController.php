<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SDM\SdmPegawai;
use DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        return view('sdm.laporan.index');
    }


    public function dataKaryawan(Request $request)
    {
        $query = SdmPegawai::query();
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'active');
        }

        $pegawais = $query->orderBy('name')->get();
        return view('sdm.laporan.data-karyawan', compact('pegawais'));
    }

    public function rekapJabatan()
    {
        $jabatans = \App\Models\SDM\RiwayatJabatan::with(['pegawai', 'masterJabatan'])
            ->where('is_active', 1)
            ->get()
            ->groupBy('masterJabatan.nama_jabatan');
            
        return view('sdm.laporan.rekap-jabatan', compact('jabatans'));
    }

    public function rekapGolongan()
    {
        $golongans = \App\Models\SDM\RiwayatPangkat::with('pegawai')
            ->where('is_active', 1)
            ->get()
            ->groupBy(function($item) {
                return $item->golongan . '/' . $item->ruang;
            });
            
        return view('sdm.laporan.rekap-golongan', compact('golongans'));
    }

    public function masaKerja()
    {
        $pegawais = SdmPegawai::where('status', 'active')
            ->get()
            ->map(function($pegawai) {
                $joinDate = \Carbon\Carbon::parse($pegawai->join_date);
                $pegawai->masa_kerja_tahun = $joinDate->diffInYears(now());
                $pegawai->masa_kerja_bulan = $joinDate->diffInMonths(now()) % 12;
                return $pegawai;
            })
            ->sortByDesc('masa_kerja_tahun');
            
        return view('sdm.laporan.masa-kerja', compact('pegawais'));
    }

    public function pendidikan()
    {
        $pendidikans = \App\Models\SDM\SdmPendidikan::with('pegawai')
            ->orderBy('jenjang')
            ->get()
            ->groupBy('jenjang');
            
        return view('sdm.laporan.pendidikan', compact('pendidikans'));
    }

    public function keluarga()
    {
        $keluargas = \App\Models\SDM\SdmKeluarga::with('pegawai')
            ->orderBy('sdm_pegawai_id')
            ->get();
            
        return view('sdm.laporan.keluarga', compact('keluargas'));
    }
}
