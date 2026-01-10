@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Dashboard SDM</h1>
            <p class="text-slate-500">Overview performa sumber daya manusia hari ini.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 bg-white rounded-full text-sm font-medium text-slate-600 shadow-sm border border-slate-100 flex items-center gap-2">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                Live Update
            </span>
            <a href="{{ route('sdm.pegawai.create') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-cyan-200 transition-all active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Pegawai Baru
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Pegawai -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute right-0 top-0 w-32 h-32 bg-cyan-50 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-cyan-100 flex items-center justify-center text-cyan-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="text-slate-500 text-sm font-medium">Total Pegawai</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-800">{{ $stats['total_pegawai'] }} <span class="text-sm font-medium text-slate-400">Orang</span></h2>
                <div class="mt-2 flex items-center text-xs text-emerald-600 font-bold bg-emerald-50 inline-block px-2 py-1 rounded-lg">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    +2 bulan ini
                </div>
            </div>
        </div>

        <!-- Kehadiran -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-50 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-slate-500 text-sm font-medium">Hadir Hari Ini</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-800">{{ $stats['hadir_hari_ini'] }} <span class="text-sm font-medium text-slate-400">Orang</span></h2>
                <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3 overflow-hidden">
                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $stats['total_pegawai'] > 0 ? ($stats['hadir_hari_ini'] / $stats['total_pegawai']) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Cuti & Ijin -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute right-0 top-0 w-32 h-32 bg-amber-50 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-slate-500 text-sm font-medium">Sedang Cuti</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-800">{{ $stats['cuti'] }} <span class="text-sm font-medium text-slate-400">Orang</span></h2>
                <p class="text-xs text-slate-400 mt-2">{{ $stats['sakit'] }} Sakit • {{ $stats['cuti'] }} Cuti Tahunan</p>
            </div>
        </div>

        <!-- Status Kontrak -->
         <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute right-0 top-0 w-32 h-32 bg-red-50 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-slate-500 text-sm font-medium">Action Needed</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-800">{{ count($alerts) }} <span class="text-sm font-medium text-slate-400">Alerts</span></h2>
                <p class="text-xs text-red-500 mt-2 font-semibold">Perlu penanganan segera</p>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Areas (Shift & Chart) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Shift Monitor & Alerts (2/3 width) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- 1. Jadwal Shift Hari Ini -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Tim Jaga Hari Ini</h3>
                        <p class="text-slate-500 text-sm">Monitoring staff medis real-time</p>
                    </div>
                    <a href="{{ route('sdm.jadwal.index') }}" class="text-cyan-600 text-sm font-bold hover:underline">Lihat Semua Jadwal</a>
                </div>

                <div class="space-y-4">
                    @foreach($shifts as $shift)
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-cyan-200 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-white border-2 border-slate-100 flex items-center justify-center text-lg font-bold text-slate-600 shadow-sm">
                                {{ $shift->img }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">{{ $shift->name }}</h4>
                                <p class="text-xs text-slate-500">{{ $shift->role }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $shift->status == 'On Duty' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                @if($shift->status == 'On Duty') <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span> @endif
                                {{ $shift->shift }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- 2. Action Center / Alerts -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Pemberitahuan Penting</h3>
                <div class="space-y-3">
                    @foreach($alerts as $alert)
                        <div class="flex items-start gap-4 p-4 rounded-xl {{ $alert->type == 'critical' ? 'bg-red-50 border-red-100 text-red-700' : ($alert->type == 'warning' ? 'bg-amber-50 border-amber-100 text-amber-700' : 'bg-blue-50 border-blue-100 text-blue-700') }} border">
                            <div class="mt-0.5">
                                @if($alert->type == 'critical')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-sm">{{ $alert->message }}</p>
                                <p class="text-xs opacity-80 mt-1">Harap ditindaklanjuti segera.</p>
                            </div>
                            <button class="text-xs font-bold underline opacity-80 hover:opacity-100">Cek Detail</button>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Right Column: Analytics & Quick Actions (1/3 width) -->
        <div class="space-y-8">
            
            <!-- Employee Composition Chart -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold text-slate-800 mb-2">Komposisi Pegawai</h3>
                <p class="text-xs text-slate-500 mb-6">Distribusi status kepegawaian</p>
                
                <div class="relative h-48">
                    <canvas id="employeeChart"></canvas>
                </div>
            </div>

            <!-- Quick Actions Grid -->
            <div class="bg-gradient-to-br from-cyan-500 to-sky-600 rounded-3xl p-6 text-white shadow-xl shadow-cyan-200">
                <h3 class="text-lg font-bold mb-6">Akses Cepat</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('sdm.absen.index') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm p-4 rounded-2xl flex flex-col items-center justify-center gap-2 transition-all cursor-pointer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span class="text-xs font-bold">Input Absen</span>
                    </a>
                    <a href="{{ route('sdm.gaji.index') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm p-4 rounded-2xl flex flex-col items-center justify-center gap-2 transition-all cursor-pointer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="text-xs font-bold">Slip Gaji</span>
                    </a>
                    <a href="{{ route('sdm.jadwal.index') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm p-4 rounded-2xl flex flex-col items-center justify-center gap-2 transition-all cursor-pointer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-xs font-bold">Jadwal</span>
                    </a>
                    <a href="{{ route('sdm.settings') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm p-4 rounded-2xl flex flex-col items-center justify-center gap-2 transition-all cursor-pointer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="text-xs font-bold">Setting</span>
                    </a>
</div>

<script>
    // Initialize Chart.js
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('employeeChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pegawai Tetap', 'Kontrak', 'Magang'],
                datasets: [{
                    data: [85, 45, 12],
                    backgroundColor: [
                        '#06b6d4', // Cyan 500
                        '#fbbf24', // Amber 400
                        '#cbd5e1'  // Slate 300
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                cutout: '70%',
            }
        });
    });
</script>
@endsection
