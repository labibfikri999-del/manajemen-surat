@extends('pegawai.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-4xl mx-auto space-y-8">
    <div class="flex items-center gap-4 print:hidden">
        <a href="{{ route('pegawai.gaji.index') }}" class="p-3 bg-white rounded-xl shadow-sm border border-slate-100 text-slate-500 hover:text-purple-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Detail Slip Gaji</h1>
            <p class="text-slate-500">Periode {{ \Carbon\Carbon::create()->month($payroll->month)->translatedFormat('F') }} {{ $payroll->year }}</p>
        </div>
        <button onclick="window.print()" class="ml-auto bg-slate-800 text-white px-6 py-2.5 rounded-xl font-bold flex items-center gap-2 hover:bg-slate-900 transition-colors shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print PDF
        </button>
    </div>

    <!-- Slip Paper -->
    <div class="bg-white p-12 rounded-3xl shadow-xl shadow-slate-200 border border-slate-100 print:shadow-none print:border-none">
        
        <!-- Letterhead -->
        <div class="flex items-center gap-6 border-b-2 border-slate-800 pb-8 mb-8">
            <img src="{{ asset('images/logo_rsi_ntb_new.png') }}" class="h-20 w-auto" alt="Logo">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 uppercase tracking-widest">Slip Gaji Pegawai</h1>
                <p class="text-slate-600 text-lg">Rumah Sakit Islam Siti Hajar Mataram</p>
                <p class="text-slate-500">Jalan Cernawasih No. 23, Mataram</p>
            </div>
            <div class="ml-auto text-right">
                <h2 class="text-4xl font-bold text-slate-200">PAYSLIP</h2>
                <p class="font-mono font-bold text-slate-800 mt-2">{{ \Carbon\Carbon::create()->month($payroll->month)->translatedFormat('F') }} {{ $payroll->year }}</p>
            </div>
        </div>

        <!-- Employee Info -->
        <div class="grid grid-cols-2 gap-12 mb-12">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Penerima</p>
                <h3 class="text-xl font-bold text-slate-900">{{ $payroll->pegawai->name }}</h3>
                <p class="text-slate-500">{{ $payroll->pegawai->nip }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Jabatan</p>
                <h3 class="text-xl font-bold text-slate-900">{{ $payroll->pegawai->role }}</h3>
                <p class="text-emerald-600 font-bold bg-emerald-50 inline-block px-3 py-1 rounded-full text-sm mt-2">{{ $payroll->status }}</p>
            </div>
        </div>

        <!-- Earnings Table -->
        <div class="mb-8">
            <h4 class="font-bold text-slate-800 mb-4 border-l-4 border-purple-500 pl-3">RINCIAN PENDAPATAN</h4>
            <table class="w-full">
                <tbody class="divide-y divide-slate-100">
                    <tr class="group">
                        <td class="py-3 text-slate-600 group-hover:pl-2 transition-all">Gaji Pokok</td>
                        <td class="py-3 text-right font-mono font-bold text-slate-800">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="group">
                        <td class="py-3 text-slate-600 group-hover:pl-2 transition-all">Tunjangan Tetap</td>
                        <td class="py-3 text-right font-mono font-bold text-slate-800">Rp {{ number_format($payroll->allowances, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Deductions Table -->
        <div class="mb-8">
            <h4 class="font-bold text-slate-800 mb-4 border-l-4 border-red-500 pl-3">RINCIAN POTONGAN</h4>
            <table class="w-full">
                <tbody class="divide-y divide-slate-100">
                    <tr class="group">
                        <td class="py-3 text-slate-600 group-hover:pl-2 transition-all">Potongan Lain-lain</td>
                        <td class="py-3 text-right font-mono font-bold text-red-500">Rp {{ number_format($payroll->deductions, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="bg-slate-900 text-white p-6 rounded-2xl flex justify-between items-center mb-12 print:bg-slate-800 print:text-white">
            <div>
                <p class="text-slate-400 text-sm uppercase font-bold tracking-wider">Total Gaji Bersih</p>
                <p class="text-xs text-slate-500">Take Home Pay</p>
            </div>
            <h2 class="text-3xl font-bold font-mono">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</h2>
        </div>

        <!-- Signing -->
        <div class="flex justify-between items-end pt-12">
            <div class="text-sm text-slate-400">
                <p>Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
                <p>Dokumen ini sah dan diterbitkan secara komputerisasi.</p>
            </div>
            <div class="text-center">
                <p class="mb-20 font-bold text-slate-800">Manager Keuangan</p>
                <div class="h-px w-48 bg-slate-800"></div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .p-4, .md\:p-8, .max-w-4xl, .mx-auto, .space-y-8 {
            padding: 0 !important;
            margin: 0 !important;
            max-width: none !important;
        }
        .bg-white {
            background: white !important;
            box-shadow: none !important;
        }
        .print\:hidden {
            display: none !important;
        }
        .print\:shadow-none {
            box-shadow: none !important;
        }
        .print\:border-none {
            border: none !important;
        }
        .print\:bg-slate-800 {
            background-color: #1e293b !important;
            -webkit-print-color-adjust: exact;
        }
        .print\:text-white {
            color: white !important;
            -webkit-print-color-adjust: exact;
        }
        /* Reveal the slip content */
        .bg-white.p-12.rounded-3xl, 
        .bg-white.p-12.rounded-3xl * {
            visibility: visible;
        }
        .bg-white.p-12.rounded-3xl {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>
@endsection
