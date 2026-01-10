<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SDM\SdmShift;

class JadwalController extends Controller
{
    public function index()
    {
        $shifts = SdmShift::with('pegawai')
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(10);
            
        return view('sdm.jadwal.index', compact('shifts'));
    }
}
