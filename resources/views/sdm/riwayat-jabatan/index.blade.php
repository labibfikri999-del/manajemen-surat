@extends('sdm.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
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

    <!-- Filters Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <form action="{{ route('sdm.riwayat-jabatan.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <!-- Search -->
                <div class="md:col-span-5">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Cari Karyawan/Jabatan</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2.5 rounded-lg border-slate-200 bg-slate-50 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-all placeholder:text-slate-400" placeholder="Nama karyawan atau jabatan...">
                        <div class="absolute left-3 top-3 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Status</label>
                    <select name="status" class="w-full rounded-lg border-slate-200 bg-slate-50 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Berakhir</option>
                    </select>
                </div>

                <!-- Kategori Filter -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Kategori</label>
                    <select name="kategori" class="w-full rounded-lg border-slate-200 bg-slate-50 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        <option value="">Semua Kategori</option>
                        <option value="Fungsional" {{ request('kategori') == 'Fungsional' ? 'selected' : '' }}>Fungsional</option>
                        <option value="Struktural" {{ request('kategori') == 'Struktural' ? 'selected' : '' }}>Struktural</option>
                    </select>
                </div>

                <!-- Reset Button -->
                <div class="md:col-span-1">
                     <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 text-white py-2.5 rounded-lg font-bold transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
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
                            <div>
                                <h3 class="font-bold text-slate-800 text-sm group-hover:text-indigo-600 transition-colors">{{ $riwayat->pegawai->name }}</h3>
                                <div class="text-xs text-slate-500 mt-0.5">NIK: {{ $riwayat->pegawai->nip ?? '-' }}</div>
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
                                <a href="{{ route('sdm.riwayat-jabatan.edit', $riwayat->id) }}" class="p-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('sdm.riwayat-jabatan.destroy', $riwayat->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat jabatan ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors" title="Hapus">
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
</div>
@endsection
