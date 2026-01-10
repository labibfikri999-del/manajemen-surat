@extends('keuangan.layouts.app')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <!-- Header & Filter -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-neutral-100/50 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-32 h-32 bg-gradient-to-br from-amber-50 to-orange-50 rounded-bl-full opacity-50 pointer-events-none"></div>
        
        <div class="relative z-10">
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Laporan Laba Rugi</h1>
            <p class="text-slate-500 mt-1 text-sm">Ringkasan kinerja keuangan periode ini</p>
        </div>

        <form action="{{ route('keuangan.laporan.laba-rugi') }}" method="GET" class="flex flex-wrap items-center gap-3 relative z-10">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-hover:text-amber-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <select name="month" class="pl-10 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all hover:bg-white cursor-pointer appearance-none">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-hover:text-amber-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <select name="year" class="pl-10 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all hover:bg-white cursor-pointer appearance-none">
                    @foreach(range(date('Y'), date('Y')-5) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                 <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <button type="submit" class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-amber-600/20 transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter
            </button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Pemasukan -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-emerald-100 hover:shadow-md transition-shadow relative overflow-hidden group">
             <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
            </div>
            <div class="flex items-center gap-4 mb-4 relative z-10">
                <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Pemasukan</p>
                    <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($pemasukan, 0, ',', '.') }}</p>
                </div>
            </div>
             <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-emerald-500 h-full rounded-full" style="width: 100%"></div>
            </div>
        </div>

        <!-- Pengeluaran -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-red-100 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
            </div>
            <div class="flex items-center gap-4 mb-4 relative z-10">
                 <div class="p-3 bg-red-50 rounded-xl text-red-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Pengeluaran</p>
                    <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</p>
                </div>
            </div>
             <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-red-500 h-full rounded-full" style="width: {{ ($pemasukan + $pengeluaran) > 0 ? ($pengeluaran / ($pemasukan + $pengeluaran)) * 100 : 0 }}%"></div>
            </div>
        </div>

        <!-- Laba Bersih -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-blue-100 hover:shadow-md transition-shadow relative overflow-hidden group">
             <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                 <svg class="w-24 h-24 {{ $labaBersih >= 0 ? 'text-blue-600' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="flex items-center gap-4 mb-4 relative z-10">
                <div class="p-3 {{ $labaBersih >= 0 ? 'bg-blue-50 text-blue-600' : 'bg-red-50 text-red-600' }} rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Laba/Rugi Bersih</p>
                    <p class="text-2xl font-bold {{ $labaBersih >= 0 ? 'text-blue-600' : 'text-red-500' }}">
                        {{ $labaBersih < 0 ? '-' : '' }}Rp {{ number_format(abs($labaBersih), 0, ',', '.') }}
                    </p>
                </div>
            </div>
             <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="{{ $labaBersih >= 0 ? 'bg-blue-500' : 'bg-red-500' }} h-full rounded-full" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <!-- Detailed Breakdown (Optional / Future Enhancement) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Rincian Laporan</h3>
            <p class="text-sm text-slate-500">Detail pemasukan dan pengeluaran</p>
        </div>
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Pemasukan Summary -->
                <div class="flex-1">
                    <h4 class="font-medium text-emerald-600 mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Pemasukan
                    </h4>
                    <div class="space-y-3">
                         <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                            <span class="text-slate-600 text-sm">Total Periode Ini</span>
                            <span class="font-bold text-slate-800">Rp {{ number_format($pemasukan, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Pengeluaran Summary -->
                <div class="flex-1">
                     <h4 class="font-medium text-red-600 mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span> Pengeluaran
                    </h4>
                    <div class="space-y-3">
                         <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                            <span class="text-slate-600 text-sm">Total Periode Ini</span>
                            <span class="font-bold text-slate-800">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
