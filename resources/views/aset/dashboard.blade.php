@extends('aset.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Welcome Section -->
    <div class="animate-fade-in-up bg-gradient-to-r from-emerald-500 to-teal-400 p-6 rounded-2xl shadow-lg mb-8 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border border-white/20">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Dashboard Sistem Manajemen Aset</h1>
            <p class="text-emerald-50 mt-1 opacity-90">Selamat datang kembali, {{ Auth::user()->name ?? 'Administrator' }}</p>
        </div>
        <div class="flex gap-3 relative overflow-hidden group">
            <div class="absolute inset-0 bg-white/20 blur group-hover:bg-white/30 transition-colors"></div>
            <div class="relative px-4 py-2 bg-white/10 backdrop-blur-md border border-white/30 rounded-xl text-white font-medium flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5 animate-pulse text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Dashboard Tersinkronisasi
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Stat 1: Total Aset -->
        <div class="relative group animate-fade-in-up delay-100">
            <div class="relative bg-white/90 backdrop-blur-lg p-6 rounded-2xl shadow-sm border border-slate-100 border-t-blue-400 border-t-4 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-slate-500 text-sm font-semibold tracking-wide uppercase">Total Aset</h3>
                    <div class="p-2.5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl text-blue-600 shadow-inner">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-end justify-between">
                    <p class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600">{{ number_format($totalAssets ?? 8, 0, ',', '.') }}</p>
                    <div class="flex flex-col items-end">
                        <span class="flex items-center gap-1 text-xs font-bold text-emerald-500 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100 mb-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                            +12%
                        </span>
                        <span class="text-[10px] text-slate-400 font-medium">Bulan lalu</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stat 2: Dalam Perbaikan -->
        <div class="relative group animate-fade-in-up delay-100" style="animation-delay: 150ms;">
            <div class="relative bg-white/90 backdrop-blur-lg p-6 rounded-2xl shadow-sm border border-slate-100 border-t-amber-400 border-t-4 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-slate-500 text-sm font-semibold tracking-wide uppercase">Dlm Perbaikan</h3>
                    <div class="p-2.5 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl text-amber-600 shadow-inner">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-end justify-between">
                    <p class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600">{{ $maintenanceNeeded ?? 2 }}</p>
                    <div class="flex flex-col items-end">
                        <span class="flex items-center gap-1 text-xs font-bold text-amber-500 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-100 mb-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                            +5%
                        </span>
                        <span class="text-[10px] text-slate-400 font-medium">Bulan lalu</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stat 3: Usia > 5 Tahun -->
        <div class="relative group animate-fade-in-up delay-200">
            <div class="relative bg-white/90 backdrop-blur-lg p-6 rounded-2xl shadow-sm border border-slate-100 border-t-purple-400 border-t-4 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-slate-500 text-sm font-semibold tracking-wide uppercase">Usia > 5 Thn</h3>
                    <div class="p-2.5 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl text-purple-600 shadow-inner">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-end justify-between">
                    <p class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600">0</p>
                    <div class="flex flex-col items-end">
                        <span class="flex items-center gap-1 text-xs font-bold text-slate-500 bg-slate-50 px-2.5 py-1 rounded-full border border-slate-200 mb-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"></path></svg>
                            0%
                        </span>
                        <span class="text-[10px] text-slate-400 font-medium">Bulan lalu</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stat 4: Bulan Ini -->
        <div class="relative group animate-fade-in-up delay-200" style="animation-delay: 250ms;">
            <div class="relative bg-white/90 backdrop-blur-lg p-6 rounded-2xl shadow-sm border border-slate-100 border-t-emerald-400 border-t-4 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                <div class="flex justify-between items-start mb-2">
                     <h3 class="text-slate-500 text-sm font-semibold tracking-wide uppercase">Bulan Ini</h3>
                    <div class="p-2.5 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl text-emerald-600 shadow-inner">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-end justify-between">
                    <p class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600">{{ $activeLoans ?? 0 }}</p>
                     <div class="flex flex-col items-end">
                        <span class="flex items-center gap-1 text-xs font-bold text-emerald-500 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100 mb-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                            +23%
                        </span>
                        <span class="text-[10px] text-slate-400 font-medium">Bulan lalu</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Distribusi Kategori (Pie Chart) -->
        <div class="bg-white/90 backdrop-blur-lg p-6 rounded-3xl shadow-sm border border-slate-100 border-t-blue-400 border-t-4 hover:shadow-md transition-all duration-300 animate-fade-in-up delay-300 relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-800 text-lg">Distribusi Kategori</h3>
                <div class="bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                    <select class="text-xs bg-transparent border-none focus:ring-0 text-slate-600 font-medium cursor-pointer">
                        <option>Bulan Ini</option>
                        <option>Tahun Ini</option>
                    </select>
                </div>
            </div>
            <div class="relative h-64 flex justify-center items-center">
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="mt-6 flex flex-wrap justify-center gap-4 text-xs font-semibold text-slate-600">
                <span class="flex items-center gap-2 px-3 py-1 bg-slate-50 rounded-full border border-slate-100"><div class="w-2.5 h-2.5 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500"></div> Elektronik</span>
                <span class="flex items-center gap-2 px-3 py-1 bg-slate-50 rounded-full border border-slate-100"><div class="w-2.5 h-2.5 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500"></div> Furniture</span>
                <span class="flex items-center gap-2 px-3 py-1 bg-slate-50 rounded-full border border-slate-100"><div class="w-2.5 h-2.5 rounded-full bg-gradient-to-br from-amber-400 to-orange-500"></div> Aksesoris</span>
            </div>
        </div>

        <!-- Status Kondisi (Bar Chart) -->
        <div class="bg-white/90 backdrop-blur-lg p-6 rounded-3xl shadow-sm border border-slate-100 border-t-emerald-400 border-t-4 hover:shadow-md transition-all duration-300 animate-fade-in-up delay-300 group relative overflow-hidden" style="animation-delay: 350ms;">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-800 text-lg">Status Kondisi</h3>
                <div class="bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                    <select class="text-xs bg-transparent border-none focus:ring-0 text-slate-600 font-medium cursor-pointer">
                        <option>Semua Unit</option>
                    </select>
                </div>
            </div>
            <div class="relative h-[280px]">
                <canvas id="conditionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Charts Section 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Detail Kondisi (1 Column) -->
        <div class="lg:col-span-1 bg-white/90 backdrop-blur-lg p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 flex flex-col animate-fade-in-up delay-300" style="animation-delay: 400ms;">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-800 text-lg">Kesehatan Aset</h3>
                <a href="#" class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg hover:bg-emerald-100 transition-colors">Detail</a>
            </div>
            <div class="space-y-4 flex-1">
                <div class="relative overflow-hidden p-5 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl text-white shadow-lg shadow-emerald-200">
                    <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-white/10 blur-xl"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <p class="font-bold text-lg leading-tight">Sangat Baik</p>
                                <p class="text-emerald-50 text-sm opacity-90">8 aset berfungsi penuh</p>
                            </div>
                        </div>
                        <div class="font-extrabold text-2xl tracking-tighter">100<span class="text-lg opacity-75">%</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tren Perolehan Aset (Line Chart - 2 Columns) -->
        <div class="lg:col-span-2 bg-white/90 backdrop-blur-lg p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 animate-fade-in-up delay-300" style="animation-delay: 450ms;">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-800 text-lg">Tren Akuisisi Aset</h3>
                <span class="text-xs font-medium text-slate-500 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">5 Tahun Terakhir</span>
            </div>
            <div class="relative h-48 w-full">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js Initialization -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pie Chart (Distribusi Kategori)
    const ctxCategory = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCategory, {
        type: 'doughnut',
        data: {
            labels: ['Elektronik', 'Furniture', 'Aksesoris', 'Alat Kesehatan'],
            datasets: [{
                data: [40, 30, 20, 10],
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#f87171'],
                borderWidth: 0,
                cutout: '50%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Bar Chart (Status Kondisi)
    const ctxCondition = document.getElementById('conditionChart').getContext('2d');
    new Chart(ctxCondition, {
        type: 'bar',
        data: {
            labels: ['Baik'],
            datasets: [{
                label: 'Jumlah Aset',
                data: [8],
                backgroundColor: '#10b981',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: '#f1f5f9' },
                    border: { display: false }
                },
                x: {
                    grid: { display: false },
                    border: { display: false }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Line Chart (Tren Perolehan)
    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: ['2020', '2021', '2022', '2023', '2024'],
            datasets: [{
                label: 'Aset Baru',
                data: [2, 3, 5, 4, 8],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 2 },
                    grid: { color: '#f1f5f9' },
                    border: { display: false }
                },
                x: {
                    grid: { display: false },
                    border: { display: false }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
@endsection
