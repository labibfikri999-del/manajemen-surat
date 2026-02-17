@extends('sdm.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard SDM</h1>
            <p class="text-sm text-slate-500 mt-1">Ringkasan statistik dan manajemen kepegawaian.</p>
        </div>
        <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="text-sm font-medium text-slate-600">{{ \Carbon\Carbon::now()->format('d F Y') }}</span>
        </div>
    </div>

    <!-- Stats Grid (Top Row) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Karyawan -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Karyawan</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['total_pegawai'] }}</h3>
                </div>
                <div class="p-3 bg-indigo-50 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Laki-laki -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Laki-laki</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['laki_laki'] }}</h3>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Perempuan -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
             <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Perempuan</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['perempuan'] }}</h3>
                </div>
                <div class="p-3 bg-pink-50 rounded-lg">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- NIDN -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Memiliki NIDN</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['nidn'] }}</h3>
                </div>
                <div class="p-3 bg-emerald-50 rounded-lg">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column (Stats & Charts) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Statistik Pendidikan -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="font-bold text-slate-800">Tingkat Pendidikan</h3>
                    <span class="text-xs font-semibold px-2 py-1 bg-slate-200 text-slate-600 rounded">updated</span>
                </div>
                
                <div class="p-6">
                    <div class="space-y-5">
                       <!-- S3 & S2 -->
                       <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-sm font-medium text-slate-700">Doktor (S3)</span>
                                    <span class="text-sm font-bold text-slate-900">{{ $stats['pendidikan']['S3'] ?? 0 }}</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $stats['total_pegawai'] > 0 ? (($stats['pendidikan']['S3'] ?? 0) / $stats['total_pegawai']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                             <div>
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-sm font-medium text-slate-700">Magister (S2)</span>
                                    <span class="text-sm font-bold text-slate-900">{{ $stats['pendidikan']['S2'] ?? 0 }}</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $stats['total_pegawai'] > 0 ? (($stats['pendidikan']['S2'] ?? 0) / $stats['total_pegawai']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                       </div>

                       <!-- S1 -->
                       <div>
                            <div class="flex justify-between items-end mb-1">
                                <span class="text-sm font-medium text-slate-700">Sarjana (S1)</span>
                                <span class="text-sm font-bold text-slate-900">{{ $stats['pendidikan']['S1'] ?? 0 }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-indigo-400 h-2 rounded-full" style="width: {{ $stats['total_pegawai'] > 0 ? (($stats['pendidikan']['S1'] ?? 0) / $stats['total_pegawai']) * 100 : 0 }}%"></div>
                            </div>
                       </div>

                       <!-- Diploma -->
                        <div>
                            <div class="flex justify-between items-end mb-1">
                                <span class="text-sm font-medium text-slate-700">Diploma (D1-D4)</span>
                                <span class="text-sm font-bold text-slate-900">{{ ($stats['pendidikan']['D3'] ?? 0) + ($stats['pendidikan']['D4'] ?? 0) }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-indigo-300 h-2 rounded-full" style="width: {{ $stats['total_pegawai'] > 0 ? ((($stats['pendidikan']['D3'] ?? 0) + ($stats['pendidikan']['D4'] ?? 0)) / $stats['total_pegawai']) * 100 : 0 }}%"></div>
                            </div>
                       </div>
                       
                        <!-- SMA/Lainnya -->
                       <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-sm font-medium text-slate-700">SMA/SMK</span>
                                    <span class="text-sm font-bold text-slate-900">{{ $stats['pendidikan']['SMA/SMK'] ?? 0 }}</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-slate-400 h-2 rounded-full" style="width: {{ $stats['total_pegawai'] > 0 ? (($stats['pendidikan']['SMA/SMK'] ?? 0) / $stats['total_pegawai']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                             <div>
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-sm font-medium text-slate-700">Lainnya</span>
                                    <span class="text-sm font-bold text-slate-900">{{ $stats['pendidikan']['Lainnya'] ?? 0 }}</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-slate-300 h-2 rounded-full" style="width: {{ $stats['total_pegawai'] > 0 ? (($stats['pendidikan']['Lainnya'] ?? 0) / $stats['total_pegawai']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                       </div>
                    </div>
                </div>
            </div>

            <!-- Status Kepegawaian -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                     <div class="p-3 bg-emerald-50 rounded-full order-last ml-auto">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                     </div>
                     <div>
                        <p class="text-sm font-medium text-slate-500">Pegawai Tetap</p>
                        <h4 class="text-3xl font-bold text-slate-900">{{ $stats['status_kepegawaian']['Tetap'] ?? 0 }}</h4>
                        <span class="text-xs text-emerald-600 font-medium bg-emerald-50 px-2 py-0.5 rounded mt-2 inline-block">Aktif</span>
                     </div>
                </div>
                 <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                     <div class="p-3 bg-amber-50 rounded-full order-last ml-auto">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                     </div>
                     <div>
                        <p class="text-sm font-medium text-slate-500">Pegawai Kontrak</p>
                        <h4 class="text-3xl font-bold text-slate-900">{{ $stats['status_kepegawaian']['Kontrak'] ?? 0 }}</h4>
                         <span class="text-xs text-amber-600 font-medium bg-amber-50 px-2 py-0.5 rounded mt-2 inline-block">Berjangka</span>
                     </div>
                </div>
            </div>

        </div>

        <!-- Right Column (Actions & Lists) -->
        <div class="space-y-6">
            
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-2 gap-3">
                     <a href="{{ route('sdm.pegawai.create') }}" class="flex flex-col items-center justify-center p-4 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition-colors group">
                        <svg class="w-6 h-6 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        <span class="text-xs font-bold">Pegawai Baru</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center p-4 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200 transition-colors group">
                        <svg class="w-6 h-6 mb-2 text-slate-500 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                        <span class="text-xs font-medium">Pendidikan</span>
                    </a>
                     <a href="#" class="flex flex-col items-center justify-center p-4 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200 transition-colors group">
                        <svg class="w-6 h-6 mb-2 text-slate-500 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="text-xs font-medium">Keluarga</span>
                    </a>
                     <a href="#" class="flex flex-col items-center justify-center p-4 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200 transition-colors group">
                        <svg class="w-6 h-6 mb-2 text-slate-500 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span class="text-xs font-medium">Jabatan</span>
                    </a>
                </div>
            </div>

            <!-- Jabatan List -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800">Jabatan Terisi</h3>
                    <a href="#" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">Lihat Semua</a>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($stats['jabatan']->take(5) as $jab)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $jab->jabatan }}</p>
                            <span class="text-[10px] text-slate-500 uppercase tracking-wider">{{ $jab->role }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                             <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ ($jab->total / $stats['total_pegawai']) * 100 }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-indigo-600 w-4 text-right">{{ $jab->total }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-slate-400 text-sm">Belum ada data jabatan</div>
                    @endforelse
                </div>
            </div>

             <!-- Summary Card -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl p-6 text-white shadow-lg">
                <h3 class="font-bold text-lg mb-1">Ringkasan</h3>
                <p class="text-slate-400 text-xs mb-4">Statistik cepat performa SDM.</p>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm border-b border-white/10 pb-2">
                        <span class="text-slate-300">Total Jabatan</span>
                        <span class="font-bold">{{ count($stats['jabatan']) }} posisi</span>
                    </div>
                    <div class="flex justify-between items-center text-sm border-b border-white/10 pb-2">
                        <span class="text-slate-300">Rasio Pria:Wanita</span>
                        <span class="font-bold">{{ $stats['total_pegawai'] > 0 ? round(($stats['laki_laki']/$stats['total_pegawai'])*100) : 0 }}:{{ $stats['total_pegawai'] > 0 ? round(($stats['perempuan']/$stats['total_pegawai'])*100) : 0 }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
