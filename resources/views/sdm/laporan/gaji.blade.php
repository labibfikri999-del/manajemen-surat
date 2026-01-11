@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 print:hidden">
        <div class="flex items-center gap-4">
            <a href="{{ route('sdm.laporan.index') }}" class="p-2.5 bg-white rounded-xl text-slate-500 hover:text-cyan-600 hover:bg-cyan-50 border border-slate-200 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Laporan Keuangan Gaji</h1>
                <p class="text-slate-500">Rekapitulasi beban gaji periode {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}</p>
            </div>
        </div>
        <button onclick="window.print()" class="group bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold transition-all flex items-center gap-2 shadow-lg shadow-slate-200">
            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Laporan
        </button>
    </div>

    <!-- Financial Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 print:hidden">
        <div class="relative overflow-hidden bg-slate-900 p-8 rounded-3xl shadow-xl text-white">
            <div class="relative z-10">
                <p class="text-slate-400 font-medium mb-1">Total Beban Gaji</p>
                <h3 class="text-3xl font-bold">Rp {{ number_format($summary['total_expenditure'], 0, ',', '.') }}</h3>
                <div class="mt-4 flex items-center text-sm text-emerald-400 bg-emerald-400/10 inline-block px-3 py-1 rounded-full w-max">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Finalized
                </div>
            </div>
            <div class="absolute top-0 right-0 -mt-8 -mr-8 h-32 w-32 rounded-full bg-white/5 blur-2xl"></div>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-center">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-cyan-50 rounded-xl text-cyan-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-slate-500 text-sm">Gaji Pokok</p>
                    <p class="text-xl font-bold text-slate-800">Rp {{ number_format($summary['total_basic'], 0, ',', '.') }}</p>
                </div>
            </div>
             <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-cyan-500 rounded-full" style="width: 70%"></div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-center">
            <form action="{{ route('sdm.laporan.gaji') }}" method="GET" class="flex flex-col gap-3">
                 <p class="text-slate-800 font-bold mb-1">Filter Laporan</p>
                <div class="flex gap-3">
                    <select name="month" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 bg-slate-50 text-sm">
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                        @endfor
                    </select>
                    <select name="year" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 bg-slate-50 text-sm">
                        @for($y=date('Y'); $y>=date('Y')-2; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-slate-900 transition-colors">Tampilkan</button>
            </form>
        </div>
    </div>

    <!-- Report Table -->
    <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100 print:shadow-none print:border-none print:rounded-none">
        
        <!-- Print Header -->
        <div class="hidden print:block p-8 border-b-2 border-slate-800">
            <div class="flex items-center gap-6">
                <img src="{{ asset('images/logo_rsi_ntb_new.png') }}" class="h-20 w-auto" alt="Logo">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 uppercase tracking-widest">Laporan Penggajian</h1>
                    <p class="text-slate-600 text-lg">Rumah Sakit Islam Siti Hajar Mataram</p>
                    <p class="text-slate-500">Periode: {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 print:bg-slate-200 print:border-slate-300">
                        <th class="px-6 py-4 font-bold text-slate-700 text-sm uppercase tracking-wider">Pegawai</th>
                        <th class="px-6 py-4 font-bold text-slate-700 text-sm uppercase tracking-wider">Jabatan</th>
                        <th class="px-6 py-4 font-bold text-right text-slate-700 text-sm uppercase tracking-wider">Gaji Pokok</th>
                        <th class="px-6 py-4 font-bold text-right text-slate-700 text-sm uppercase tracking-wider">Tunjangan</th>
                        <th class="px-6 py-4 font-bold text-right text-red-600 text-sm uppercase tracking-wider">Potongan</th>
                        <th class="px-6 py-4 font-bold text-right text-slate-900 text-sm uppercase tracking-wider">Net Salary</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payrolls as $index => $payroll)
                    <tr class="hover:bg-slate-50 {{ $index % 2 != 0 ? 'bg-slate-50/50' : '' }}">
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $payroll->pegawai->name }}</td>
                        <td class="px-6 py-4 text-slate-500 text-sm">{{ $payroll->pegawai->role }}</td>
                        <td class="px-6 py-4 text-right text-slate-600 font-mono text-sm">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-slate-600 font-mono text-sm">Rp {{ number_format($payroll->allowances, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-red-500 font-mono text-sm">Rp {{ number_format($payroll->deductions, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-bold text-slate-900 font-mono">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 italic">
                            Belum ada data penggajian yang tercatat untuk periode ini.
                        </td>
                    </tr>
                    @endforelse
                    
                    <!-- Footer Total -->
                    <tr class="bg-slate-900 text-white font-bold print:bg-slate-800 print:text-white">
                        <td colspan="2" class="px-6 py-4 text-right uppercase tracking-wider text-sm">Total Periode Ini</td>
                        <td class="px-6 py-4 text-right font-mono text-sm">Rp {{ number_format($summary['total_basic'], 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-mono text-sm">Rp {{ number_format($summary['total_allowances'], 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-mono text-sm opacity-70">-</td>
                        <td class="px-6 py-4 text-right font-mono text-lg">Rp {{ number_format($summary['total_expenditure'], 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="hidden print:flex justify-between items-end p-12 mt-8">
            <div class="text-sm text-slate-500">
                <p>Dicetak Otomatis oleh Sistem SDM</p>
                <p>{{ now()->translatedFormat('l, d F Y H:i') }}</p>
            </div>
            <div class="text-center">
                <p class="mb-20 font-bold text-slate-800">Mengetahui, Manager Keuangan</p>
                <div class="h-px w-64 bg-slate-800"></div>
                <p class="mt-2 font-bold text-slate-800">( ................................................. )</p>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
         .bg-white, .shadow-sm, .rounded-3xl, .p-8 {
            background: white !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
        }
        .print\:hidden {
            display: none !important;
        }
        .print\:block {
            display: block !important;
        }
        .print\:flex {
            display: flex !important;
        }
        .print\:shadow-none {
            box-shadow: none !important;
        }
        .print\:border-none {
            border: none !important;
        }
        .print\:rounded-none {
            border-radius: 0 !important;
        }
        .print\:bg-slate-200 {
            background-color: #e2e8f0 !important;
            -webkit-print-color-adjust: exact;
        }
        .print\:bg-slate-800 {
            background-color: #1e293b !important;
            -webkit-print-color-adjust: exact;
        }
        .print\:text-white {
             color: white !important;
        }
    }
</style>
@endsection
