<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SDM\SdmPegawai;

class PegawaiController extends Controller
{
    public function create()
    {
        return view('sdm.pegawai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'nip' => 'required|unique:sdm_pegawais',
            'role' => 'required',
            'join_date' => 'required|date',
        ]);

        SdmPegawai::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'role' => $request->role,
            'join_date' => $request->join_date,
            'status' => 'active',
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return redirect()->route('sdm.dashboard')->with('success', 'Pegawai baru berhasil ditambahkan.');
    }
}
