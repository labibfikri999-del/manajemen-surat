@extends('sdm.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="flex items-center justify-between no-print">
        <h1 class="text-2xl font-bold text-slate-800">Detail Payslip</h1>
        <div class="flex gap-3">
             <a href="{{ route('sdm.payroll.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                Kembali
            </a>
            <button onclick="window.print()" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Slip Gaji
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden printable">
        <!-- Payslip Header -->
        <div class="bg-indigo-600 p-8 text-white flex justify-between items-start print:bg-white print:text-black print:border-b-2 print:border-black">
            <div>
                <h2 class="text-2xl font-bold uppercase tracking-wider">Slip Gaji</h2>
                <p class="text-indigo-100 mt-1 print:text-black">Periode: {{ date('F Y', mktime(0, 0, 0, $payroll->month, 1, $payroll->year)) }}</p>
            </div>
            <div class="text-right">
                <h3 class="text-lg font-bold">{{ config('app.name', 'YARSI NTB') }}</h3>
                <p class="text-indigo-100 text-sm opacity-80 print:text-black">Jl. Raya ... No. ...<br>Mataram, NTB</p>
            </div>
        </div>

        <div class="p-8">
            <!-- Employee Info -->
            <div class="grid grid-cols-2 gap-8 mb-8 pb-8 border-b border-slate-100">
                <div>
                     <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Karyawan</label>
                    <div class="text-lg font-bold text-slate-800">{{ $payroll->pegawai->name }}</div>
                </div>
                <div>
                     <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">NIP</label>
                    <div class="text-lg font-mono text-slate-600">{{ $payroll->pegawai->nip }}</div>
                </div>
                <div>
                     <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Jabatan</label>
                    <div class="text-slate-700">{{ $payroll->pegawai->position ?? '-' }}</div>
                </div>
                <div>
                     <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Tanggal Pembayaran</label>
                    <div class="text-slate-700">{{ \Carbon\Carbon::parse($payroll->payment_date)->format('d F Y') }}</div>
                </div>
            </div>

            <!-- Earnings & Deductions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Earnings -->
                <div>
                    <h4 class="font-bold text-emerald-700 uppercase tracking-wider mb-4 border-b border-emerald-100 pb-2">Penerimaan (Earnings)</h4>
                    <div class="space-y-3 text-sm">
                        @foreach($payroll->details->where('type', 'earning') as $earning)
                        <div class="flex justify-between items-center text-slate-700">
                            <span>{{ $earning->component_name }}</span>
                            <span class="font-mono font-medium">Rp {{ number_format($earning->amount, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Deductions -->
                <div>
                    <h4 class="font-bold text-red-700 uppercase tracking-wider mb-4 border-b border-red-100 pb-2">Potongan (Deductions)</h4>
                    <div class="space-y-3 text-sm">
                        @foreach($payroll->details->where('type', 'deduction') as $deduction)
                        <div class="flex justify-between items-center text-slate-700">
                            <span>{{ $deduction->component_name }}</span>
                            <span class="font-mono font-medium text-red-600">- Rp {{ number_format($deduction->amount, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        @if($payroll->details->where('type', 'deduction')->isEmpty())
                            <div class="text-slate-400 italic">Tidak ada potongan.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Net Salary -->
            <div class="mt-12 pt-6 border-t-2 border-slate-100 flex justify-end">
                <div class="w-full md:w-1/2 bg-slate-50 rounded-lg p-6 border border-slate-200">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-slate-600 font-medium">Total Penerimaan</span>
                        <span class="font-mono font-bold text-emerald-600">Rp {{ number_format($payroll->basic_salary + $payroll->allowances, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-slate-600 font-medium">Total Potongan</span>
                        <span class="font-mono font-bold text-red-600">- Rp {{ number_format($payroll->deductions, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-slate-300 pt-4 flex justify-between items-center">
                        <span class="text-lg font-bold text-slate-800 uppercase">Gaji Bersih (Net)</span>
                        <span class="text-2xl font-extrabold text-indigo-700 font-mono">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-12 grid grid-cols-2 gap-8 text-center print:mt-24">
                <div>
                    <p class="text-sm text-slate-500 mb-16">Penerima,</p>
                    <p class="font-bold text-slate-800 underline">{{ $payroll->pegawai->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500 mb-16">Mengetahui, HRD / Keuangan</p>
                    <p class="font-bold text-slate-800 underline">( ....................................... )</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        .printable { box-shadow: none !important; border: none !important; margin: 0 !important; width: 100% !important; max-width: none !important; }
        body { background: white !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endsection
