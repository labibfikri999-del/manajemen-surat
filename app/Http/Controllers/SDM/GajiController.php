<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SDM\SdmPayroll;
use App\Models\SDM\SdmPegawai;

class GajiController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) ($request->month ?? date('m'));
        $year = (int) ($request->year ?? date('Y'));

        $payrolls = SdmPayroll::where('month', $month)
            ->where('year', $year)
            ->with('pegawai')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('sdm.gaji.index', compact('payrolls', 'month', 'year'));
    }

    public function create()
    {
        $pegawais = SdmPegawai::where('status', 'active')->orderBy('name')->get();
        return view('sdm.gaji.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sdm_pegawai_id' => 'required',
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric',
            'basic_salary' => 'required|numeric',
            'allowances' => 'required|numeric',
            'deductions' => 'required|numeric',
        ]);

        // Calculate Net
        $net = $request->basic_salary + $request->allowances - $request->deductions;

        // Check duplicate
        $exists = SdmPayroll::where('sdm_pegawai_id', $request->sdm_pegawai_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Slip gaji untuk pegawai ini pada periode tersebut sudah ada.');
        }

        SdmPayroll::create([
            'sdm_pegawai_id' => $request->sdm_pegawai_id,
            'month' => $request->month,
            'year' => $request->year,
            'basic_salary' => $request->basic_salary,
            'allowances' => $request->allowances,
            'deductions' => $request->deductions,
            'net_salary' => $net,
            'status' => 'Pending',
            'payment_date' => now(), // Default just set to created date
        ]);

        return redirect()->route('sdm.gaji.index')->with('success', 'Slip gaji berhasil dibuat.');
    }

    public function show($id)
    {
        $payroll = SdmPayroll::with('pegawai')->findOrFail($id);
        return view('sdm.gaji.show', compact('payroll'));
    }
    
    public function destroy($id)
    {
        $payroll = SdmPayroll::findOrFail($id);
        $payroll->delete();
        return redirect()->route('sdm.gaji.index')->with('success', 'Data penggajian dihapus.');
    }

    // API to get pegawai salary data for AJAX
    public function getPegawaiData($id)
    {
        $pegawai = SdmPegawai::findOrFail($id);
        return response()->json([
            'gaji_pokok' => $pegawai->gaji_pokok,
            'tunjangan' => $pegawai->tunjangan
        ]);
    }
}
