@extends('sdm.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Payroll</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola penggajian karyawan, tunjangan, dan potongan.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('sdm.payroll.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Buat Payroll Baru
            </a>
        </div>
    </div>

    <!-- Period Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <form action="{{ route('sdm.payroll.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="month" class="block text-xs font-medium text-slate-700 mb-1">Bulan</label>
                <select name="month" id="month" class="form-select rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label for="year" class="block text-xs font-medium text-slate-700 mb-1">Tahun</label>
                <select name="year" id="year" class="form-select rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @for($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-100 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-200 hover:text-slate-800 transition text-sm font-medium">Filter</button>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <div class="text-xs font-medium text-slate-500 uppercase">Total Pengeluaran</div>
            <div class="mt-2 text-2xl font-bold text-slate-800">Rp {{ number_format($stats['total_payroll'], 0, ',', '.') }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <div class="text-xs font-medium text-slate-500 uppercase">Total Karyawan</div>
            <div class="mt-2 text-2xl font-bold text-slate-800">{{ $stats['total_pegawai'] }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <div class="text-xs font-medium text-slate-500 uppercase">Sudah Diproses</div>
            <div class="mt-2 text-2xl font-bold text-emerald-600">{{ $stats['processed'] }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <div class="text-xs font-medium text-slate-500 uppercase">Belum Diproses</div>
            <div class="mt-2 text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</div>
        </div>
    </div>

    <!-- Payroll Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-600 font-bold">
                        <th class="px-6 py-3">Karyawan</th>
                        <th class="px-6 py-3 text-right">Gaji Pokok</th>
                        <th class="px-6 py-3 text-right">Tunjangan</th>
                        <th class="px-6 py-3 text-right">Potongan</th>
                        <th class="px-6 py-3 text-right">Total (Net)</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($payrolls as $payroll)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ $payroll->pegawai->name }}</div>
                            <div class="text-xs text-slate-500 font-mono">{{ $payroll->pegawai->nip }}</div>
                        </td>
                        <td class="px-6 py-4 text-right tabular-nums text-slate-600">
                            Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right tabular-nums text-emerald-600">
                            + Rp {{ number_format($payroll->allowances, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right tabular-nums text-red-600">
                            - Rp {{ number_format($payroll->deductions, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right tabular-nums font-bold text-slate-800">
                            Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-bold uppercase bg-emerald-100 text-emerald-700 border border-emerald-200">
                                {{ $payroll->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                             <a href="{{ route('sdm.payroll.show', $payroll->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs">Detail</a>
                             <form action="{{ route('sdm.payroll.destroy', $payroll->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-xs">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500 italic">
                            Belum ada data payroll untuk periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
