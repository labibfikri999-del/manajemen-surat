@extends('sdm.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6" x-data="{ viewMode: 'list' }">
    
    <!-- Hero / Header Section -->
    <div class="bg-indigo-600 rounded-2xl p-6 sm:p-10 relative overflow-hidden shadow-lg">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="text-white">
                <div class="flex items-center gap-3 mb-2">
                     <svg class="w-8 h-8 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <h1 class="text-3xl font-bold tracking-tight">Riwayat Jabatan</h1>
                </div>
                <p class="text-indigo-100 opacity-90">Ringkasan riwayat jabatan aktif dan berakhir. Total Aktif: <span class="font-bold text-white">{{ $totalAktif }}</span></p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('sdm.riwayat-jabatan.create') }}" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-5 py-2.5 rounded-xl font-semibold backdrop-blur-sm transition-all flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Jabatan
                </a>
                <a href="{{ route('sdm.riwayat-pangkat.index') }}" class="bg-emerald-500 hover:bg-emerald-400 text-white px-5 py-2.5 rounded-xl font-semibold transition-all flex items-center gap-2 shadow-md shadow-emerald-800/20">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                     Monitoring Kenaikan
                </a>
            </div>
        </div>
        
        <!-- Decorative bg -->
        <div class="absolute right-0 top-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
        <div class="absolute left-10 bottom-0 w-32 h-32 bg-purple-500 opacity-20 rounded-full blur-2xl"></div>
    </div>

    <!-- View Toggle & Filters -->
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
        <!-- View Toggle -->
        <div class="bg-slate-100 p-1 rounded-lg flex items-center shadow-inner">
            <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-md text-sm font-semibold transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                List View
            </button>
            <button @click="viewMode = 'timeline'" :class="viewMode === 'timeline' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-md text-sm font-semibold transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1 -18 0 9 9 0 0 1 18 0z"></path></svg>
                Timeline View
            </button>
        </div>

        <!-- Filters Form -->
        <form action="{{ route('sdm.riwayat-jabatan.index') }}" method="GET" class="flex-1 w-full md:w-auto">
            <div class="flex flex-col md:flex-row gap-3 justify-end">
                <input type="text" name="search" value="{{ request('search') }}" class="flex-1 md:w-48 px-4 py-2 rounded-lg border-slate-200 bg-white text-slate-800 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Cari karyawan/jabatan...">
                
                <select name="status" class="md:w-32 px-4 py-2 rounded-lg border-slate-200 bg-white text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Berakhir</option>
                </select>

                <select name="kategori" class="md:w-36 px-4 py-2 rounded-lg border-slate-200 bg-white text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    <option value="">Semua Kategori</option>
                    <option value="Fungsional" {{ request('kategori') == 'Fungsional' ? 'selected' : '' }}>Fungsional</option>
                    <option value="Struktural" {{ request('kategori') == 'Struktural' ? 'selected' : '' }}>Struktural</option>
                </select>

                <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg font-bold transition-colors flex items-center justify-center gap-2 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </div>
        </form>
    </div>

    <!-- List View (Table) -->
    <div x-show="viewMode === 'list'" x-transition:enter="transition opacity-0 duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                        <th class="px-6 py-4">Karyawan</th>
                        <th class="px-6 py-4">Nama Jabatan</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Homebase</th>
                        <th class="px-6 py-4">Tanggal Mulai</th>
                        <th class="px-6 py-4">Masa Jabatan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayats as $riwayat)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <!-- Karyawan -->
                        <td class="px-6 py-4 align-middle">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs ring-2 ring-white">
                                     {{ substr($riwayat->pegawai->name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-700 text-sm hover:text-indigo-600 transition-colors cursor-pointer">{{ $riwayat->pegawai->name }}</span>
                                    <span class="text-[10px] text-slate-400">NIK: {{ $riwayat->pegawai->nip ?? '-' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Nama Jabatan -->
                        <td class="px-6 py-4 align-middle">
                            <span class="text-sm font-semibold text-slate-700">{{ $riwayat->masterJabatan->nama_jabatan }}</span>
                        </td>

                        <!-- Kategori -->
                        <td class="px-6 py-4 align-middle">
                            @if($riwayat->kategori == 'Fungsional')
                                <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase border border-emerald-200 tracking-wide">Fungsional</span>
                            @else
                                <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase border border-amber-200 tracking-wide">None Fungsional</span>
                            @endif
                        </td>

                        <!-- Homebase -->
                        <td class="px-6 py-4 align-middle">
                            @if($riwayat->homebase)
                                <span class="bg-sky-50 text-sky-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase border border-sky-100 tracking-wide text-xs">{{ $riwayat->homebase }}</span>
                            @else
                                <span class="text-slate-300 text-xs">-</span>
                            @endif
                        </td>

                        <!-- Tanggal Mulai -->
                        <td class="px-6 py-4 align-middle">
                             <div class="flex items-center gap-1.5 text-slate-600 text-sm">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>{{ \Carbon\Carbon::parse($riwayat->tgl_mulai)->format('d/m/Y') }}</span>
                            </div>
                        </td>

                        <!-- Masa Jabatan -->
                        <td class="px-6 py-4 align-middle">
                            @php
                                $start = \Carbon\Carbon::parse($riwayat->tgl_mulai);
                                $end = $riwayat->is_active ? \Carbon\Carbon::now() : ($riwayat->tgl_selesai ? \Carbon\Carbon::parse($riwayat->tgl_selesai) : \Carbon\Carbon::now());
                                $diff = $start->diffInMonths($end);
                            @endphp
                            <span class="font-bold text-slate-700">{{ $diff }} bulan</span>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 align-middle text-center">
                            @if($riwayat->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold uppercase tracking-wide bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold uppercase tracking-wide bg-rose-100 text-rose-700 border border-rose-200">
                                    Berakhir
                                </span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="px-6 py-4 align-middle text-center">
                             <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('sdm.riwayat-jabatan.edit', $riwayat->id) }}" class="p-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors border border-amber-100" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('sdm.riwayat-jabatan.destroy', $riwayat->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat jabatan ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors border border-rose-100" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                            Belum ada riwayat jabatan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($riwayats->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $riwayats->links() }}
        </div>
        @endif
    </div>

    <!-- Timeline View -->
    <div x-show="viewMode === 'timeline'" x-cloak x-transition:enter="transition opacity-0 duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-8">
        @php
            // Group by year or category? Let's group by start year for history context
            $groupedByYear = $riwayats->groupBy(function($date) {
                return \Carbon\Carbon::parse($date->tgl_mulai)->format('Y');
            });
        @endphp

        @forelse($groupedByYear as $year => $items)
        <div class="relative">
             <!-- Year Label -->
             <div class="absolute -left-3 top-0 bg-indigo-600 text-white font-bold text-sm px-3 py-1 rounded-full shadow-lg z-10 border-2 border-white">
                {{ $year }}
             </div>
             
             <!-- Timeline Line -->
             <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-indigo-100 z-0"></div>

             <div class="pl-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-2">
                 @foreach($items as $riwayat)
                 <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 hover:shadow-md transition-shadow relative overflow-hidden group">
                     <!-- Accent Bar -->
                     <div class="absolute left-0 top-0 bottom-0 w-1 {{ $riwayat->is_active ? 'bg-emerald-500' : 'bg-slate-300' }} rounded-l"></div>

                     <!-- Status Badge -->
                     <div class="absolute right-3 top-3">
                        @if($riwayat->is_active)
                             <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-emerald-100 text-emerald-700 border border-emerald-200">Aktif</span>
                        @else
                             <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-slate-100 text-slate-500 border border-slate-200">Selesai</span>
                        @endif
                     </div>
                     
                     <div class="flex items-center gap-3 mb-4 mt-1">
                         <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold border border-slate-200 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                              {{ substr($riwayat->pegawai->name, 0, 1) }}
                         </div>
                         <div>
                             <h3 class="font-bold text-slate-800 text-sm line-clamp-1">{{ $riwayat->pegawai->name }}</h3>
                             <p class="text-[11px] text-slate-500 uppercase tracking-wide">{{ $riwayat->masterJabatan->nama_jabatan }}</p>
                         </div>
                     </div>
                     
                     <div class="space-y-2 mb-4">
                         <div class="flex items-center gap-2 text-slate-600">
                             <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                             <span class="text-xs font-medium">{{ \Carbon\Carbon::parse($riwayat->tgl_mulai)->format('d/m/Y') }} - {{ $riwayat->is_active ? 'Sekarang' : \Carbon\Carbon::parse($riwayat->tgl_selesai)->format('d/m/Y') }}</span>
                         </div>
                         <div class="flex items-center gap-2 text-slate-600">
                             <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                             <span class="text-xs">{{ $riwayat->kategori }}</span>
                         </div>
                     </div>

                     <div class="flex justify-between items-center pt-3 border-t border-slate-100">
                         @if($riwayat->dokumen_path)
                         <a href="{{ asset('storage/' . $riwayat->dokumen_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-xs font-bold transition-colors">
                             Lihat SK
                         </a>
                         @else
                         <span class="text-slate-300 text-xs">-</span>
                         @endif

                         <div class="flex gap-2">
                             <a href="{{ route('sdm.riwayat-jabatan.edit', $riwayat->id) }}" class="text-slate-400 hover:text-amber-500 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                         </div>
                     </div>
                 </div>
                 @endforeach
             </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center py-12 text-slate-400">
            <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1 -18 0 9 9 0 0 1 18 0z"></path></svg>
            <p>Tidak ada riwayat jabatan.</p>
        </div>
        @endforelse
        
        <!-- Pagination for Timeline (reuse same links) -->
         @if($riwayats->hasPages())
        <div class="pt-6">
            {{ $riwayats->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
