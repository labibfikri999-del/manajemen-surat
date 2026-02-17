@extends('sdm.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Hero / Header Section -->
    <div class="bg-indigo-600 rounded-2xl p-6 sm:p-10 relative overflow-hidden shadow-lg">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="text-white">
                <div class="flex items-center gap-3 mb-2">
                     <svg class="w-8 h-8 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <h1 class="text-3xl font-bold tracking-tight">Data Keluarga</h1>
                </div>
                <p class="text-indigo-100 opacity-90">Manajemen data keluarga karyawan (Istri/Suami/Anak) untuk keperluan tunjangan dan asuransi. Total Data: <span class="font-bold text-white">{{ $total }}</span></p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('sdm.keluarga.create') }}" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-5 py-2.5 rounded-xl font-semibold backdrop-blur-sm transition-all flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Keluarga
                </a>
            </div>
        </div>
        
        <!-- Decorative bg -->
        <div class="absolute right-0 top-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
        <div class="absolute left-10 bottom-0 w-32 h-32 bg-purple-500 opacity-20 rounded-full blur-2xl"></div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <form action="{{ route('sdm.keluarga.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <!-- Search -->
                <div class="md:col-span-6">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Cari Nama / Karyawan</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2.5 rounded-lg border-slate-200 bg-slate-50 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-all placeholder:text-slate-400" placeholder="Nama keluarga atau nama karyawan...">
                        <div class="absolute left-3 top-3 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Hubungan Filter -->
                <div class="md:col-span-4">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Hubungan Keluarga</label>
                    <select name="hubungan" class="w-full rounded-lg border-slate-200 bg-slate-50 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        <option value="">Semua Hubungan</option>
                        @foreach(['Istri', 'Suami', 'Anak', 'Orang Tua'] as $h)
                            <option value="{{ $h }}" {{ request('hubungan') == $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Reset Button -->
                <div class="md:col-span-2">
                     <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 text-white py-2.5 rounded-lg font-bold transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter
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
                        <th class="px-6 py-4">Nama Keluarga</th>
                        <th class="px-6 py-4 text-center">Hubungan</th>
                        <th class="px-6 py-4">Tgl Lahir / Usia</th>
                        <th class="px-6 py-4">Pekerjaan</th>
                        <th class="px-6 py-4 text-center">Dokumen</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($keluargas as $keluarga)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <!-- Karyawan -->
                        <td class="px-6 py-4 align-middle">
                            <div>
                                <h3 class="font-bold text-slate-800 text-sm group-hover:text-indigo-600 transition-colors">{{ $keluarga->pegawai->name }}</h3>
                                <div class="text-xs text-slate-500 mt-0.5">NIK: {{ $keluarga->pegawai->nip ?? '-' }}</div>
                            </div>
                        </td>

                        <!-- Nama Keluarga -->
                        <td class="px-6 py-4 align-middle">
                            <span class="text-sm font-semibold text-slate-700">{{ $keluarga->nama }}</span>
                        </td>

                        <!-- Hubungan -->
                        <td class="px-6 py-4 align-middle text-center">
                            @php
                                $colors = [
                                    'Istri' => 'bg-pink-100 text-pink-700 border-pink-200',
                                    'Suami' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'Anak' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'Orang Tua' => 'bg-purple-100 text-purple-700 border-purple-200',
                                ];
                                $color = $colors[$keluarga->hubungan] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold uppercase tracking-wide border {{ $color }}">
                                {{ $keluarga->hubungan }}
                            </span>
                        </td>

                        <!-- Tgl Lahir / Usia -->
                        <td class="px-6 py-4 align-middle">
                            <div class="flex flex-col">
                                <span class="text-sm text-slate-700 font-medium">{{ \Carbon\Carbon::parse($keluarga->tgl_lahir)->format('d/m/Y') }}</span>
                                <span class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($keluarga->tgl_lahir)->age }} Tahun</span>
                            </div>
                        </td>

                        <!-- Pekerjaan -->
                        <td class="px-6 py-4 align-middle">
                            <span class="text-sm text-slate-600">{{ $keluarga->pekerjaan ?? '-' }}</span>
                        </td>
                        
                        <!-- Dokumen -->
                         <td class="px-6 py-4 align-middle text-center">
                            @if($keluarga->dokumen_path)
                                <a href="{{ asset('storage/' . $keluarga->dokumen_path) }}" target="_blank" class="inline-flex items-center justify-center p-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors" title="Lihat Dokumen">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="px-6 py-4 align-middle text-center">
                             <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('sdm.keluarga.edit', $keluarga->id) }}" class="p-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('sdm.keluarga.destroy', $keluarga->id) }}" method="POST" onsubmit="return confirm('Hapus data keluarga ini?');" class="inline">
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
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                            Belum ada data keluarga.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($keluargas->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $keluargas->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
