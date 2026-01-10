@extends('keuangan.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Laporan Arus Kas</h1>
            <p class="text-slate-500">Periode: {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
        </div>
        
        <form action="{{ route('keuangan.arus-kas') }}" method="GET" class="flex flex-wrap items-center gap-3">
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

    <!-- Main Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        
        <!-- Section 1: Operating Activities -->
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                Aktivitas Operasional
            </h2>
            <div class="space-y-4 pl-4 md:pl-14">
                @forelse($arusKas['operasional'] as $item)
                <div class="flex justify-between items-center group">
                    <span class="text-slate-600 font-medium group-hover:text-blue-600 transition-colors">{{ $item['desc'] }}</span>
                    <span class="font-bold {{ $item['type'] == 'in' ? 'text-emerald-600' : 'text-slate-800' }}">
                        {{ $item['type'] == 'in' ? '+ ' : '' }}Rp {{ number_format($item['amount'], 0, ',', '.') }}
                    </span>
                </div>
                @empty
                <p class="text-slate-400 text-sm italic">Tidak ada transaksi operasional periode ini.</p>
                @endforelse
                <div class="border-t border-slate-100 pt-3 mt-2 flex justify-between items-center">
                    <span class="font-bold text-slate-800">Arus Kas Bersih dari Operasional</span>
                    <span class="font-bold text-blue-600 text-lg">Rp {{ number_format(array_sum(array_column($arusKas['operasional'], 'amount')), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Section 2: Investing Activities -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/30">
            <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-3">
                 <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                Aktivitas Investasi
            </h2>
             <div class="space-y-4 pl-4 md:pl-14">
                @forelse($arusKas['investasi'] as $item)
                 <div class="flex justify-between items-center">
                    <span class="text-slate-600 font-medium">{{ $item['desc'] }}</span>
                     <span class="font-bold {{ $item['type'] == 'in' ? 'text-emerald-600' : 'text-slate-800' }}">
                         {{ $item['type'] == 'in' ? '+ ' : '' }}Rp {{ number_format($item['amount'], 0, ',', '.') }}
                    </span>
                </div>
                @empty
                <p class="text-slate-400 text-sm italic">Tidak ada transaksi investasi periode ini.</p>
                @endforelse
                <div class="border-t border-slate-200 pt-3 mt-2 flex justify-between items-center">
                    <span class="font-bold text-slate-800">Arus Kas Bersih dari Investasi</span>
                    <span class="font-bold text-purple-600 text-lg">Rp {{ number_format(array_sum(array_column($arusKas['investasi'], 'amount')), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Section 3: Financing Activities -->
        <div class="p-6 border-b border-slate-100">
             <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-3">
                 <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                Aktivitas Pendanaan
            </h2>
             <div class="space-y-4 pl-4 md:pl-14">
                @forelse($arusKas['pendanaan'] as $item)
                 <div class="flex justify-between items-center">
                    <span class="text-slate-600 font-medium">{{ $item['desc'] }}</span>
                     <span class="font-bold {{ $item['type'] == 'in' ? 'text-emerald-600' : 'text-slate-800' }}">
                         {{ $item['type'] == 'in' ? '+ ' : '' }}Rp {{ number_format($item['amount'], 0, ',', '.') }}
                    </span>
                </div>
                @empty
                <p class="text-slate-400 text-sm italic">Tidak ada transaksi pendanaan periode ini.</p>
                @endforelse
                 <div class="border-t border-slate-100 pt-3 mt-2 flex justify-between items-center">
                    <span class="font-bold text-slate-800">Arus Kas Bersih dari Pendanaan</span>
                    <span class="font-bold text-orange-600 text-lg">Rp {{ number_format(array_sum(array_column($arusKas['pendanaan'], 'amount')), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="p-8 bg-slate-900 text-white flex justify-between items-center">
            <div>
                <p class="text-slate-400 font-medium">Kenaikan (Penurunan) Bersih Kas</p>
                @php
                    $total = array_sum(array_column($arusKas['operasional'], 'amount')) + 
                             array_sum(array_column($arusKas['investasi'], 'amount')) + 
                             array_sum(array_column($arusKas['pendanaan'], 'amount'));
                @endphp
                 <h2 class="text-3xl font-bold mt-1">Rp {{ number_format($total, 0, ',', '.') }}</h2>
            </div>
             <div class="text-right hidden md:block">
                <p class="text-slate-400 text-sm">Mutasi Kas Periode Ini</p>
                <p class="text-emerald-400 font-bold text-lg mt-1 block">
                    {{ $total >= 0 ? 'Surplus' : 'Defisit' }}
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
