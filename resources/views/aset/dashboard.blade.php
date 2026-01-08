@extends('aset.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Welcome Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Dashboard Aset</h1>
            <p class="text-slate-500 mt-1">Selamat datang kembali, {{ Auth::user()->name ?? 'Administrator' }}</p>
        </div>
        <div class="flex gap-3">
            <button class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-slate-600 font-medium hover:bg-slate-50 transition-colors shadow-sm">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Unduh Laporan
                </span>
            </button>
            <button class="px-4 py-2 bg-emerald-500 text-white rounded-xl font-bold shadow-lg shadow-emerald-200 hover:bg-emerald-600 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Aset
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Stat 1 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-emerald-50 rounded-xl group-hover:bg-emerald-100 transition-colors text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <span class="text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg text-xs font-bold">+12%</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium">Total Aset</h3>
            <p class="text-2xl font-bold text-slate-800 mt-1">1,248 <span class="text-xs font-normal text-slate-400">Unit</span></p>
        </div>

        <!-- Stat 2 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 rounded-xl group-hover:bg-blue-100 transition-colors text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-slate-400 text-xs font-medium">Nilai Buku</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium">Total Valuasi</h3>
            <p class="text-2xl font-bold text-slate-800 mt-1">Rp 4.2 <span class="text-xs font-normal text-slate-400">Miliar</span></p>
        </div>

        <!-- Stat 3 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-orange-50 rounded-xl group-hover:bg-orange-100 transition-colors text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span class="text-red-500 bg-red-50 px-2 py-1 rounded-lg text-xs font-bold">3 Unit</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium">Perlu Maintenance</h3>
            <p class="text-2xl font-bold text-slate-800 mt-1">Servis Segera</p>
        </div>

        <!-- Stat 4 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-purple-50 rounded-xl group-hover:bg-purple-100 transition-colors text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <span class="text-slate-400 text-xs font-medium">Bulan Ini</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium">Peminjaman Aktif</h3>
            <p class="text-2xl font-bold text-slate-800 mt-1">24 <span class="text-xs font-normal text-slate-400">Transaksi</span></p>
        </div>
    </div>

    <!-- Charts & Tables Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Main Chart -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-800">Pertumbuhan Aset</h3>
                <select class="bg-slate-50 border-none text-sm text-slate-600 rounded-lg px-3 py-1 focus:ring-emerald-500">
                    <option>Tahun Ini</option>
                    <option>Tahun Lalu</option>
                </select>
            </div>
            <div class="h-64 flex items-end justify-between gap-2 px-2">
                <!-- Mock Chart Bars (CSS Only for lightweight demo) -->
                @foreach([40, 65, 30, 80, 55, 90, 45, 70, 60, 75, 50, 85] as $h)
                <div class="w-full bg-emerald-100 rounded-t-lg relative group h-full flex items-end">
                    <div class="w-full bg-emerald-500 rounded-t-lg transition-all duration-500 hover:bg-emerald-600" style="height: {{ $h }}%"></div>
                    <!-- Tooltip -->
                    <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                        Rp {{ $h }} Jt
                    </div>
                </div>
                @endforeach
            </div>
            <div class="flex justify-between text-xs text-slate-400 mt-4 px-1">
                <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>Mei</span><span>Jun</span>
                <span>Jul</span><span>Agu</span><span>Sep</span><span>Okt</span><span>Nov</span><span>Des</span>
            </div>
        </div>

        <!-- Recent Activities / Notifications -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="font-bold text-slate-800 mb-6">Aktivitas Terbaru</h3>
            <div class="space-y-6">
                <!-- Item 1 -->
                <div class="flex gap-4">
                    <div class="w-2 h-2 mt-2 rounded-full bg-emerald-500 ring-4 ring-emerald-50"></div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Unit Laptop Baru</p>
                        <p class="text-xs text-slate-500 mt-0.5">Ditambahkan oleh Admin Logistik</p>
                        <p class="text-[10px] text-slate-400 mt-1">2 Jam yang lalu</p>
                    </div>
                </div>
                <!-- Item 2 -->
                <div class="flex gap-4">
                    <div class="w-2 h-2 mt-2 rounded-full bg-blue-500 ring-4 ring-blue-50"></div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Mutasi Kursi Kantor</p>
                        <p class="text-xs text-slate-500 mt-0.5">Dipindahkan ke Ruang Rapat B</p>
                        <p class="text-[10px] text-slate-400 mt-1">5 Jam yang lalu</p>
                    </div>
                </div>
                <!-- Item 3 -->
                <div class="flex gap-4">
                    <div class="w-2 h-2 mt-2 rounded-full bg-orange-500 ring-4 ring-orange-50"></div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Jadwal Service AC</p>
                        <p class="text-xs text-slate-500 mt-0.5">Ruang Server Lantai 2</p>
                        <p class="text-[10px] text-slate-400 mt-1">Besok, 09:00 WITA</p>
                    </div>
                </div>
            </div>
            
            <button class="w-full mt-8 py-2 text-sm text-emerald-600 font-medium hover:bg-emerald-50 rounded-xl transition-colors">
                Lihat Semua Aktivitas
            </button>
        </div>

    </div>
</div>
@endsection
