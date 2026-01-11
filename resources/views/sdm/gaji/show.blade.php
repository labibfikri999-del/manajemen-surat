@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-3xl mx-auto space-y-8">
    <div class="flex items-center justify-between gap-4 print:hidden">
        <a href="{{ route('sdm.gaji.index') }}" class="p-2 bg-white rounded-full text-slate-500 hover:text-cyan-600 shadow-sm border border-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <button onclick="window.print()" class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2 rounded-xl font-bold transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Slip
        </button>
    </div>

    <!-- Slip Gaji for Print -->
    <div class="bg-white p-8 md:p-12 shadow-sm border border-slate-100 print:shadow-none print:border-none print:w-full">
        <!-- Letterhead -->
        <div class="flex items-center gap-4 border-b-2 border-slate-800 pb-6 mb-8">
            <img src="{{ asset('images/logo_rsi_ntb_new.png') }}" class="h-16 w-auto" alt="Logo">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 uppercase tracking-widest">Slip Gaji Pegawai</h1>
                <p class="text-slate-600">RSI SITI HAJAR MATARAM</p>
                <p class="text-sm text-slate-500">Jl. Catur Warga, Mataram, Nusa Tenggara Barat</p>
            </div>
        </div>

        <!-- Info Pegawai -->
        <div class="grid grid-cols-2 gap-8 mb-8 text-sm">
            <div>
                <table class="w-full">
                    <tr>
                        <td class="text-slate-500 py-1 w-32">Nama</td>
                        <td class="font-bold text-slate-800">{{ $payroll->pegawai->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-slate-500 py-1">NIP</td>
                        <td class="font-bold text-slate-800">{{ $payroll->pegawai->nip }}</td>
                    </tr>
                    <tr>
                        <td class="text-slate-500 py-1">Jabatan</td>
                        <td class="font-bold text-slate-800">{{ $payroll->pegawai->role }}</td>
                    </tr>
                </table>
            </div>
            <div>
                 <table class="w-full">
                    <tr>
                        <td class="text-slate-500 py-1 w-32">Periode</td>
                        <td class="font-bold text-slate-800">{{ \Carbon\Carbon::create()->month($payroll->month)->translatedFormat('F') }} {{ $payroll->year }}</td>
                    </tr>
                    <tr>
                        <td class="text-slate-500 py-1">Tanggal Cetak</td>
                        <td class="font-bold text-slate-800">{{ date('d F Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Rincian -->
        <div class="mb-8">
            <h3 class="font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4">PENERIMAAN</h3>
            <table class="w-full text-sm mb-6">
                <tr>
                    <td class="py-2 text-slate-600">Gaji Pokok</td>
                    <td class="py-2 text-right font-bold text-slate-800">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="py-2 text-slate-600">Tunjangan</td>
                    <td class="py-2 text-right font-bold text-slate-800">Rp {{ number_format($payroll->allowances, 0, ',', '.') }}</td>
                </tr>
            </table>

            <h3 class="font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4">POTONGAN</h3>
             <table class="w-full text-sm mb-6">
                <tr>
                    <td class="py-2 text-slate-600">Potongan Lain-lain</td>
                    <td class="py-2 text-right font-bold text-red-600 text-slate-800">- Rp {{ number_format($payroll->deductions, 0, ',', '.') }}</td>
                </tr>
            </table>
            
            <div class="bg-cyan-50 p-4 rounded-xl flex justify-between items-center border border-cyan-100 print:bg-transparent print:border-y print:border-x-0 print:border-slate-800 print:rounded-none">
                <span class="font-bold text-lg text-slate-800">TOTAL DITERIMA</span>
                <span class="font-bold text-2xl text-slate-900">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Signature -->
        <div class="grid grid-cols-2 gap-8 mt-16 text-center text-sm">
            <div>
                <p class="mb-16">Penerima,</p>
                <p class="font-bold border-b border-slate-300 inline-block px-8 pb-1">{{ $payroll->pegawai->name }}</p>
            </div>
             <div>
                <p class="mb-16">Manager Keuangan,</p>
                <p class="font-bold border-b border-slate-300 inline-block px-8 pb-1">Admin Keuangan</p>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .print\:w-full {
             width: 100%;
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
    }
</style>
@endsection
