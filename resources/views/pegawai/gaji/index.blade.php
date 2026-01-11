@extends('pegawai.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Slip Gaji</h1>
            <p class="text-slate-500">Arsip pendapatan dan potongan bulanan Anda.</p>
        </div>
    </div>

    <!-- Payroll History List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($payrolls as $payroll)
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-colors">
                         <h3 class="font-bold text-lg">{{ \Carbon\Carbon::create()->month($payroll->month)->translatedFormat('M') }}</h3>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg text-slate-800">{{ \Carbon\Carbon::create()->month($payroll->month)->translatedFormat('F') }} {{ $payroll->year }}</h4>
                        <p class="text-xs text-slate-400">Diterbitkan: {{ $payroll->created_at->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 uppercase tracking-widest">{{ $payroll->status }}</span>
            </div>
            
            <div class="space-y-3 mb-6">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Gaji Pokok & Tunjangan</span>
                    <span class="font-medium text-slate-700">Rp {{ number_format($payroll->basic_salary + $payroll->allowances, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Potongan</span>
                    <span class="font-medium text-red-500">- Rp {{ number_format($payroll->deductions, 0, ',', '.') }}</span>
                </div>
                <div class="border-t border-slate-100 pt-3 flex justify-between items-center">
                    <span class="font-bold text-slate-700">Total Diterima</span>
                    <span class="font-bold text-lg text-purple-700">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</span>
                </div>
            </div>

            <a href="{{ route('pegawai.gaji.show', $payroll->id) }}" class="block w-full text-center py-3 bg-slate-50 hover:bg-purple-600 hover:text-white text-slate-600 font-bold rounded-xl transition-all">
                Lihat Detail Slip
            </a>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-slate-400">
            <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p>Belum ada data penggajian yang tersedia.</p>
        </div>
        @endforelse
    </div>
    
    <div class="mt-6">
        {{ $payrolls->links() }}
    </div>
</div>
@endsection
