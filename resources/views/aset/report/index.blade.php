@extends('aset.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Laporan & Statistik</h1>
        <p class="text-slate-500">Ringkasan performa dan aktivitas manajemen aset.</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-indigo-600 text-white p-6 rounded-3xl shadow-lg shadow-indigo-100 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-indigo-100 font-medium mb-1">Total Nilai Aset</p>
                <h3 class="text-2xl font-bold">Rp {{ number_format($stats['total_value'], 0, ',', '.') }}</h3>
            </div>
            <div class="absolute -right-4 -bottom-4 bg-white/10 w-24 h-24 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <div class="flex items-center gap-4 mb-2">
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <span class="text-slate-400 font-bold text-xs uppercase">Jumlah Aset</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $stats['total_asset'] }} <span class="text-sm font-normal text-slate-400">unit</span></p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <div class="flex items-center gap-4 mb-2">
                <div class="p-2 bg-rose-50 text-rose-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                </div>
                <span class="text-slate-400 font-bold text-xs uppercase">Total Biaya Servis</span>
            </div>
            <p class="text-xl font-bold text-slate-800 truncate">Rp {{ number_format($stats['total_maintenance_cost'], 0, ',', '.') }}</p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
             <div class="flex items-center gap-4 mb-2">
                <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                   <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <span class="text-slate-400 font-bold text-xs uppercase">Frekuensi Servis</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $stats['maintenance_count'] }} <span class="text-sm font-normal text-slate-400">kali</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Mutations -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-6">Mutasi Terakhir</h3>
            <div class="space-y-4">
                @forelse($recentMutations as $rm)
                <div class="flex items-start gap-4 pb-4 border-b last:border-0 border-slate-50">
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0 text-slate-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ $rm->aset->name }}</p>
                        <p class="text-xs text-slate-500">{{ $rm->type }} ke <span class="font-bold">{{ $rm->destination_location }}</span></p>
                        <p class="text-[10px] text-slate-400 mt-1">{{ $rm->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-slate-400 text-sm text-center">Belum ada data.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Maintenance -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-6">Maintenance Terakhir</h3>
             <div class="space-y-4">
                @forelse($recentMaintenances as $rm)
                 <div class="flex items-start gap-4 pb-4 border-b last:border-0 border-slate-50">
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0 text-slate-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ $rm->aset->name }}</p>
                        <p class="text-xs text-slate-500">{{ $rm->description }}</p>
                         <p class="text-[10px] text-slate-400 mt-1">{{ $rm->scheduled_date->translatedFormat('d M Y') }}</p>
                    </div>
                </div>
                @empty
                 <p class="text-slate-400 text-sm text-center">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
