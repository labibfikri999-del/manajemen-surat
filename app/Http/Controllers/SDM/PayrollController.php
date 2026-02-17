<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SDM\SdmPegawai;
use App\Models\SDM\SdmPayroll;
use App\Models\SDM\SdmPayrollDetail;
use PDF;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');

        $payrolls = SdmPayroll::with('pegawai')
            ->where('month', $month)
            ->where('year', $year)
            ->latest()
            ->get();

        $stats = [
            'total_pegawai' => SdmPegawai::where('status', 'active')->count(),
            'total_payroll' => $payrolls->sum('net_salary'),
            'processed' => $payrolls->count(),
            'pending' => SdmPegawai::where('status', 'active')->count() - $payrolls->count()
        ];

        return view('sdm.payroll.index', compact('payrolls', 'stats', 'year', 'month'));
    }

    public function create()
    {
        $pegawais = SdmPegawai::where('status', 'active')->orderBy('name')->get();
        return view('sdm.payroll.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
            'pegawai_ids' => 'required|array',
            'payment_date' => 'required|date',
        ]);

        foreach ($request->pegawai_ids as $pegawaiId) {
            $pegawai = SdmPegawai::find($pegawaiId);
            
            // Check if payroll already exists for this period
            $exists = SdmPayroll::where('sdm_pegawai_id', $pegawaiId)
                ->where('month', $request->month)
                ->where('year', $request->year)
                ->exists();
                
            if ($exists) continue;

            // Calculate Base Salary & Allowances (Example Logic)
            $basicSalary = $pegawai->gaji_pokok ?? 0;
            $allowances = $pegawai->tunjangan ?? 0;
            
            // Additional calculations can be done here (e.g., overtime, bonuses)
            // For now, we use the base values from employee record

            $deductions = 0; // Logic for deductions (BPJS, etc.) can be added here
            
            $netSalary = $basicSalary + $allowances - $deductions;

            $payroll = SdmPayroll::create([
                'sdm_pegawai_id' => $pegawai->id,
                'month' => $request->month,
                'year' => $request->year,
                'basic_salary' => $basicSalary,
                'allowances' => $allowances,
                'deductions' => $deductions,
                'net_salary' => $netSalary,
                'status' => 'Paid',
                'payment_date' => $request->payment_date,
            ]);

            // Save Details
            SdmPayrollDetail::create([
                'sdm_payroll_id' => $payroll->id,
                'component_name' => 'Gaji Pokok',
                'type' => 'earning',
                'amount' => $basicSalary
            ]);

            SdmPayrollDetail::create([
                'sdm_payroll_id' => $payroll->id,
                'component_name' => 'Tunjangan Jabatan',
                'type' => 'earning',
                'amount' => $allowances
            ]);
            
            // Add more details as needed
        }

        return redirect()->route('sdm.payroll.index')
            ->with('success', 'Payroll berhasil digenerate.');
    }

    public function show($id)
    {
        $payroll = SdmPayroll::with(['pegawai', 'details'])->findOrFail($id);
        return view('sdm.payroll.show', compact('payroll'));
    }

    public function destroy($id)
    {
        $payroll = SdmPayroll::findOrFail($id);
        $payroll->delete();
        return redirect()->route('sdm.payroll.index')
            ->with('success', 'Data payroll berhasil dihapus.');
    }
}
