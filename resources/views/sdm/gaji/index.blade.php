@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Penggajian (Payroll)</h1>
            <p class="text-slate-500">Kelola dan pantau penggajian pegawai.</p>
        </div>
        <a href="{{ route('sdm.gaji.create') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-cyan-200 transition-all active:scale-95 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Buat Slip Gaji
        </a>
    </div>

    <!-- Filter Bulan -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <form action="{{ route('sdm.gaji.index') }}" method="GET" class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="font-bold text-slate-700">Periode:</span>
                <select name="month" class="rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500">
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                    @endfor
                </select>
                <select name="year" class="rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500">
                    @for($y=date('Y'); $y>=date('Y')-2; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-xl font-bold transition-all">Lihat</button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-bold text-slate-700">Pegawai</th>
                        <th class="px-6 py-4 font-bold text-slate-700">Tanggal</th>
                        <th class="px-6 py-4 font-bold text-slate-700">Gaji Bersih</th>
                        <th class="px-6 py-4 font-bold text-slate-700">Status</th>
                        <th class="px-6 py-4 font-bold text-slate-700 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($payrolls as $payroll)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-600 font-bold text-lg">
                                    {{ substr($payroll->pegawai->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800">{{ $payroll->pegawai->name }}</h3>
                                    <p class="text-xs text-slate-500">{{ $payroll->pegawai->role }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ \Carbon\Carbon::parse($payroll->created_at)->translatedFormat('d F Y') }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800">
                            Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-600 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                {{ $payroll->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('sdm.gaji.show', $payroll->id) }}" class="p-2 bg-white border border-slate-200 rounded-lg text-cyan-600 hover:text-cyan-800 hover:border-cyan-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <form action="{{ route('sdm.gaji.destroy', $payroll->id) }}" method="POST" onsubmit="return confirm('Hapus data penggajian ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-red-600 hover:border-red-200 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            Belum ada data penggajian untuk periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($payrolls->hasPages())
        <div class="p-6 border-t border-slate-100">
            {{ $payrolls->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
