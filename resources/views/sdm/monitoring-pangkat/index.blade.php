@extends('sdm.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Hero / Header Section -->
    <div class="bg-indigo-600 rounded-2xl p-6 sm:p-10 relative overflow-hidden shadow-lg">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="text-white">
                <div class="flex items-center gap-3 mb-2">
                     <svg class="w-8 h-8 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <h1 class="text-3xl font-bold tracking-tight">Monitoring Kenaikan Pangkat</h1>
                </div>
                <p class="text-indigo-100 opacity-90">Pantau jadwal kenaikan pangkat berkala karyawan.</p>
            </div>
            <div class="flex gap-3">
                 <a href="{{ route('sdm.riwayat-pangkat.index') }}" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-5 py-2.5 rounded-xl font-semibold backdrop-blur-sm transition-all flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>
        </div>
        
        <!-- Decorative bg -->
        <div class="absolute right-0 top-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
        <div class="absolute left-10 bottom-0 w-32 h-32 bg-purple-500 opacity-20 rounded-full blur-2xl"></div>
    </div>

    <!-- Stats Review -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Pegawai -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Total Karyawan Aktif</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalPegawai }}</h3>
            </div>
            <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Siap Naik Pangkat -->
        <div class="bg-emerald-50 p-5 rounded-xl border border-emerald-100 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-emerald-600 text-xs font-bold uppercase tracking-wider mb-1">Siap Naik Pangkat</p>
                <h3 class="text-2xl font-bold text-emerald-800">{{ $siapNaik }}</h3>
                <p class="text-xs text-emerald-600 mt-1">Sudah Waktunya</p>
            </div>
            <div class="p-2 bg-white rounded-lg text-emerald-600 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>

        <!-- Segera (3 Bulan) -->
        <div class="bg-amber-50 p-5 rounded-xl border border-amber-100 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-amber-600 text-xs font-bold uppercase tracking-wider mb-1">Segera (&lt; 3 Bulan)</p>
                <h3 class="text-2xl font-bold text-amber-800">{{ $segeraNaik }}</h3>
                <p class="text-xs text-amber-600 mt-1">Persiapkan Berkas</p>
            </div>
            <div class="p-2 bg-white rounded-lg text-amber-600 shadow-sm">
               <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1 -18 0 9 9 0 0 1 18 0z"></path></svg>
            </div>
        </div>

        <!-- 6 Bulan ke Depan -->
        <div class="bg-sky-50 p-5 rounded-xl border border-sky-100 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-sky-600 text-xs font-bold uppercase tracking-wider mb-1">Dalam 6 Bulan</p>
                <h3 class="text-2xl font-bold text-sky-800">{{ $dalamEnamBulan }}</h3>
                <p class="text-xs text-sky-600 mt-1">Monitoring Berkala</p>
            </div>
            <div class="p-2 bg-white rounded-lg text-sky-600 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <form action="{{ route('sdm.monitoring-pangkat.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <!-- Search -->
                <div class="md:col-span-6">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Cari Karyawan</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2.5 rounded-lg border-slate-200 bg-slate-50 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-all placeholder:text-slate-400" placeholder="Nama karyawan...">
                        <div class="absolute left-3 top-3 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Tahun -->
                <div class="md:col-span-4">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Target Tahun</label>
                    <select name="tahun" class="w-full rounded-lg border-slate-200 bg-slate-50 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        @for($i = date('Y') - 1; $i <= date('Y') + 5; $i++)
                            <option value="{{ $i }}" {{ $targetYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="md:col-span-2">
                     <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 text-white py-2.5 rounded-lg font-bold transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                        <th class="px-6 py-4">Karyawan</th>
                        <th class="px-6 py-4 text-center">Pangkat Saat Ini</th>
                        <th class="px-6 py-4">TMT Terakhir</th>
                        <th class="px-6 py-4">Target Kenaikan (+4 Thn)</th>
                        <th class="px-6 py-4 text-center">Sisa Waktu</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pangkats as $pangkat)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <!-- Karyawan -->
                        <td class="px-6 py-4 align-middle">
                            <div>
                                <h3 class="font-bold text-slate-800 text-sm group-hover:text-indigo-600 transition-colors">{{ $pangkat->pegawai->name }}</h3>
                                <div class="text-xs text-slate-500 mt-0.5">NIK: {{ $pangkat->pegawai->nip ?? '-' }}</div>
                            </div>
                        </td>

                        <!-- Pangkat Saat Ini -->
                        <td class="px-6 py-4 align-middle text-center">
                            <span class="inline-flex items-center justify-center px-2 py-1 rounded bg-slate-100 text-slate-700 font-bold text-xs border border-slate-200">
                                {{ $pangkat->golongan }} / {{ $pangkat->ruang }}
                            </span>
                        </td>

                        <!-- TMT Terakhir -->
                        <td class="px-6 py-4 align-middle">
                            <span class="text-slate-600 text-sm">{{ \Carbon\Carbon::parse($pangkat->tmt)->format('d F Y') }}</span>
                        </td>

                        <!-- Target Kenaikan -->
                        <td class="px-6 py-4 align-middle">
                             <div class="flex items-center gap-1.5 text-indigo-700 text-sm font-bold">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>{{ $pangkat->next_promotion_date->format('d F Y') }}</span>
                            </div>
                        </td>

                        <!-- Sisa Waktu -->
                        <td class="px-6 py-4 align-middle text-center">
                            @if($pangkat->days_remaining <= 0)
                                <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-1 rounded-full animate-pulse">
                                    Sudah Waktunya!
                                </span>
                            @elseif($pangkat->days_remaining <= 90)
                                <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2 py-1 rounded-full">
                                    {{ $pangkat->days_remaining }} hari lagi
                                </span>
                            @else
                                <span class="bg-slate-100 text-slate-600 text-xs font-bold px-2 py-1 rounded-full">
                                    {{ $pangkat->days_remaining }} hari lagi
                                </span>
                            @endif
                        </td>
                        
                        <!-- Status -->
                        <td class="px-6 py-4 align-middle text-center">
                             @if($pangkat->days_remaining <= 90)
                                <a href="{{ route('sdm.riwayat-pangkat.create') }}?pegawai_id={{ $pangkat->sdm_pegawai_id }}" class="inline-flex items-center justify-center p-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors shadow-sm" title="Proses Kenaikan Pangkat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                </a>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            Tidak ada data kenaikan pangkat untuk tahun {{ $targetYear }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
