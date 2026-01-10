@extends('keuangan.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Neraca Keuangan</h1>
            <p class="text-slate-500">Laporan Posisi Keuangan per {{ date('d F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
        </div>
        
        <form action="{{ route('keuangan.neraca') }}" method="GET" class="flex flex-wrap items-center gap-3">
             <div class="relative group">
                <select name="month" class="pl-4 pr-8 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:border-amber-500 transition-colors cursor-pointer appearance-none">
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
                <select name="year" class="pl-4 pr-8 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:border-amber-500 transition-colors cursor-pointer appearance-none">
                    @foreach(range(date('Y'), date('Y')-5) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-medium transition-colors">
                Filter
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Left Column: ASSETS -->
        <div class="space-y-6">
            <!-- Aktiva Lancar -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5">
                    <svg class="w-32 h-32 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.15-1.46-3.27-3.4h1.96c.1 1.05 1.18 1.91 2.53 1.91 1.29 0 2.13-.59 2.13-1.66 0-.85-.31-1.34-1.97-1.75-2.07-.49-4.04-1.35-4.04-3.52 0-1.85 1.41-3.13 3.38-3.51V4h2.67v1.93c1.39.29 2.54 1.19 2.95 2.83h-1.99c-.16-.92-.93-1.54-2.12-1.54-1.22 0-1.87.56-1.87 1.45 0 .86.58 1.3 2.18 1.69 2.15.52 3.83 1.48 3.83 3.42 0 1.96-1.58 3.23-3.69 3.51z"/></svg>
                </div>
                <div class="relative z-10">
                    <h3 class="text-lg font-bold text-emerald-700 mb-4 pb-2 border-b border-emerald-100 flex items-center gap-2">
                        <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
                        Aset Lancar
                    </h3>
                    <div class="space-y-3">
                        @foreach($assets['lancar'] as $name => $val)
                        <div class="flex justify-between items-center group">
                            <span class="text-slate-600 font-medium group-hover:text-emerald-600 transition-colors">{{ $name }}</span>
                            <span class="font-bold text-slate-800">Rp {{ number_format($val, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Aktiva Tetap -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-lg font-bold text-emerald-700 mb-4 pb-2 border-b border-emerald-100 flex items-center gap-2">
                        <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
                        Aset Tidak Lancar
                    </h3>
                    <div class="space-y-3">
                        @foreach($assets['tetap'] as $name => $val)
                        <div class="flex justify-between items-center group">
                            <span class="text-slate-600 font-medium group-hover:text-emerald-600 transition-colors">{{ $name }}</span>
                            <span class="font-bold {{ $val < 0 ? 'text-red-500' : 'text-slate-800' }}">
                                {{ $val < 0 ? '(' : '' }}Rp {{ number_format(abs($val), 0, ',', '.') }}{{ $val < 0 ? ')' : '' }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Total Assets Summary -->
            <div class="bg-emerald-600 rounded-3xl p-6 text-white shadow-lg shadow-emerald-200 flex justify-between items-center">
                <div>
                    <p class="text-emerald-100 text-sm font-medium">Total Aset</p>
                    <h2 class="text-3xl font-bold">Rp {{ number_format($totalAssets, 0, ',', '.') }}</h2>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Right Column: LIABILITIES & EQUITY -->
        <div class="space-y-6">
            
            <!-- Kewajiban -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 relative overflow-hidden">
                 <div class="absolute top-0 right-0 p-4 opacity-5">
                    <svg class="w-32 h-32 text-amber-600" fill="currentColor" viewBox="0 0 24 24"><path d="M5.12 14 1.62 5.5h20.76l-3.5 8.5H5.12zm13.76-7H5.12l2.47 6h8.82l2.47-6zM3 3h18v1.5H3V3zm3 14h12v2H6v-2zm2 3h8v2H8v-2z"/></svg>
                </div>
                <div class="relative z-10">
                    <h3 class="text-lg font-bold text-amber-700 mb-4 pb-2 border-b border-amber-100 flex items-center gap-2">
                        <span class="w-2 h-8 bg-amber-500 rounded-full"></span>
                        Liabilitas (Kewajiban)
                    </h3>
                    
                    <div class="mb-4">
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Jangka Pendek</h4>
                        <div class="space-y-3">
                            @foreach($liabilities['pendek'] as $name => $val)
                            <div class="flex justify-between items-center border-l-2 border-transparent hover:border-amber-400 pl-2 transition-all">
                                <span class="text-slate-600 font-medium">{{ $name }}</span>
                                <span class="font-bold text-slate-800">Rp {{ number_format($val, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Jangka Panjang</h4>
                        <div class="space-y-3">
                            @foreach($liabilities['panjang'] as $name => $val)
                            <div class="flex justify-between items-center border-l-2 border-transparent hover:border-amber-400 pl-2 transition-all">
                                <span class="text-slate-600 font-medium">{{ $name }}</span>
                                <span class="font-bold text-slate-800">Rp {{ number_format($val, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ekuitas -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-lg font-bold text-slate-700 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                        <span class="w-2 h-8 bg-slate-500 rounded-full"></span>
                        Ekuitas
                    </h3>
                    <div class="space-y-3">
                        @foreach($equity as $name => $val)
                        <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl">
                            <span class="text-slate-600 font-medium">{{ $name }}</span>
                            <span class="font-bold text-slate-800">Rp {{ number_format($val, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Total Lia + Eq Summary -->
             <div class="bg-slate-800 rounded-3xl p-6 text-white shadow-lg shadow-slate-300 flex justify-between items-center">
                <div>
                    <p class="text-slate-400 text-sm font-medium">Total Liabilitas + Ekuitas</p>
                    <h2 class="text-3xl font-bold">Rp {{ number_format($totalLiabilities + $totalEquity, 0, ',', '.') }}</h2>
                </div>
                <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm">
                   <svg class="w-6 h-6 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
