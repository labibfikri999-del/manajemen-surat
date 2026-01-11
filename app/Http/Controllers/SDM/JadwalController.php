<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SDM\SdmShift;
use App\Models\SDM\SdmPegawai;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $query = SdmShift::with('pegawai');

        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', '>=', now()->toDateString());
        }

        if ($request->has('pegawai_id') && $request->pegawai_id != '') {
            $query->where('sdm_pegawai_id', $request->pegawai_id);
        }

        $shifts = $query->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->paginate(10);
            
        $pegawais = SdmPegawai::where('status', 'active')->orderBy('name')->get();

        return view('sdm.jadwal.index', compact('shifts', 'pegawais'));
    }

    public function create()
    {
        $pegawais = SdmPegawai::where('status', 'active')->orderBy('name')->get();
        return view('sdm.jadwal.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sdm_pegawai_id' => 'required|exists:sdm_pegawais,id',
            'shift_name' => 'required',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        SdmShift::create([
            'sdm_pegawai_id' => $request->sdm_pegawai_id,
            'shift_name' => $request->shift_name,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'Scheduled'
        ]);

        return redirect()->route('sdm.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $shift = SdmShift::findOrFail($id);
        $pegawais = SdmPegawai::where('status', 'active')->orderBy('name')->get();
        return view('sdm.jadwal.edit', compact('shift', 'pegawais'));
    }

    public function update(Request $request, $id)
    {
        $shift = SdmShift::findOrFail($id);

        $request->validate([
            'sdm_pegawai_id' => 'required|exists:sdm_pegawais,id',
            'shift_name' => 'required',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'status' => 'required'
        ]);

        $shift->update([
            'sdm_pegawai_id' => $request->sdm_pegawai_id,
            'shift_name' => $request->shift_name,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $request->status
        ]);

        return redirect()->route('sdm.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $shift = SdmShift::findOrFail($id);
        $shift->delete();

        return redirect()->route('sdm.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
