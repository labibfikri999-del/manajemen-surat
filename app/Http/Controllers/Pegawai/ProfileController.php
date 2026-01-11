<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SDM\SdmPegawai;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pegawai = SdmPegawai::where('email', $user->email)->first();

        if (!$pegawai) {
            return redirect()->route('pegawai.dashboard')->with('error', 'Akun Anda tidak terhubung dengan Data Pegawai. Hubungi HR.');
        }

        return view('pegawai.profile.index', compact('pegawai'));
    }
}
