@extends('aset.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-4 mb-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Laporan Sistem</h1>
            <p class="text-slate-500 text-sm mt-1">Analisis dan statistik aset terintegrasi</p>
        </div>
        <div class="flex gap-3">
             <button type="button" class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-lg font-semibold hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2-4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak
            </button>
            <button type="button" class="bg-blue-600 border border-transparent text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition-colors shadow-md flex items-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"></path></svg>
                Export
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-slate-800">Filter Laporan</h3>
            <button type="button" class="text-sm font-medium text-slate-400 hover:text-slate-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Reset
            </button>
        </div>
        
        <form class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Jenis Laporan</label>
                    <select class="w-full h-10 rounded-lg border-slate-200 text-sm focus:border-blue-500 text-slate-600">
                        <option>Ringkasan Aset</option>
                        <option>Laporan Kerusakan</option>
                        <option>Laporan Mutasi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Periode Waktu</label>
                    <select class="w-full h-10 rounded-lg border-slate-200 text-sm focus:border-blue-500 text-slate-600">
                        <option>Minggu Ini</option>
                        <option>Bulan Ini</option>
                        <option>Tahun Ini</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Kategori</label>
                    <select class="w-full h-10 rounded-lg border-slate-200 text-sm focus:border-blue-500 text-slate-600">
                        <option>Semua Kategori</option>
                        <option>Elektronik</option>
                        <option>Furniture</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Unit Kerja</label>
                    <select class="w-full h-10 rounded-lg border-slate-200 text-sm focus:border-blue-500 text-slate-600">
                        <option>Semua Unit</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mt-4">
                <div class="flex gap-4 mb-4 md:mb-0">
                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                        <input type="checkbox" checked class="rounded border-slate-300 text-red-500 focus:ring-red-500">
                        <span>Tampilkan Chart</span>
                    </label>
                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                        <input type="checkbox" class="rounded border-slate-300 text-slate-500 focus:ring-slate-500">
                        <span>Tampilkan Detail</span>
                    </label>
                </div>
                <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold shadow-md transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Generate Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Generated Content Plaeholder -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Stat 1 -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-blue-500 flex justify-between items-center group cursor-pointer hover:shadow-md transition-all">
            <div>
                <p class="text-xs font-semibold text-slate-400 mb-1">Total Aset</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $stats['total_asset'] ?? 8 }}</h3>
                <p class="text-[10px] text-emerald-500 font-medium mt-1">↑ 0% dari periode lalu</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
        </div>

        <!-- Stat 2 -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-emerald-500 flex justify-between items-center group cursor-pointer hover:shadow-md transition-all">
            <div>
                <p class="text-xs font-semibold text-slate-400 mb-1">Total Nilai</p>
                <h3 class="text-2xl font-bold text-slate-800">Rp {{ number_format($stats['total_value'] ?? 130535435, 0, ',', '.') }}</h3>
                <p class="text-[10px] text-emerald-500 font-medium mt-1">↑ 0% dari periode lalu</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Stat 3 -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-amber-500 flex justify-between items-center group cursor-pointer hover:shadow-md transition-all">
            <div class="w-full">
                <p class="text-xs font-semibold text-slate-400 mb-1">Kondisi Baik</p>
                <div class="flex justify-between items-end">
                    <h3 class="text-2xl font-bold text-slate-800">8</h3>
                    <div class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center text-amber-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                </div>
                <div class="w-full bg-slate-100 h-1.5 rounded-full mt-2">
                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 100%"></div>
                </div>
                <p class="text-[10px] text-right font-bold text-slate-500 mt-1">100%</p>
            </div>
        </div>

        <!-- Stat 4 -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-purple-500 flex justify-between items-center group cursor-pointer hover:shadow-md transition-all">
            <div>
                <p class="text-xs font-semibold text-slate-400 mb-1">Rata-rata Usia</p>
                <h3 class="text-2xl font-bold text-slate-800">0.0000 tahun</h3>
                <p class="text-[10px] text-slate-400 font-medium mt-1">0 aset > 5 tahun</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-500 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-800">Distribusi per Kategori</h3>
                <select class="text-xs border-slate-200 rounded text-slate-500 py-1 px-2">
                    <option>Pie Chart</option>
                </select>
            </div>
            <div class="relative h-64 flex justify-center items-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-800">Status Kondisi</h3>
                <select class="text-xs border-slate-200 rounded text-slate-500 py-1 px-2">
                    <option>Bar Chart</option>
                </select>
            </div>
            <div class="relative h-64">
                <canvas id="conditionChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pie Chart
    const ctxCategory = document.getElementById('categoryChart')?.getContext('2d');
    if (ctxCategory) {
        new Chart(ctxCategory, {
            type: 'pie',
            data: {
                labels: ['Elektronik', 'Furniture', 'Aksesoris', 'Alat Kesehatan'],
                datasets: [{
                    data: [40, 30, 20, 10],
                    backgroundColor: ['#facc15', '#f87171', '#3b82f6', '#10b981'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    // Bar Chart
    const ctxCondition = document.getElementById('conditionChart')?.getContext('2d');
    if (ctxCondition) {
        new Chart(ctxCondition, {
            type: 'bar',
            data: {
                labels: ['Baik'],
                datasets: [{
                    label: 'Jumlah Aset',
                    data: [8],
                    backgroundColor: '#10b981',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    y: { beginAtZero: true, max: 8 },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endsection
