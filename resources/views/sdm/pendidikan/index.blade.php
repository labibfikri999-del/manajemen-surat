@extends('sdm.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Hero / Header Section -->
    <div class="bg-indigo-600 rounded-2xl p-6 sm:p-10 relative overflow-hidden shadow-lg">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="text-white">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-8 h-8 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    <h1 class="text-3xl font-bold tracking-tight">Riwayat Pendidikan</h1>
                </div>
                <p class="text-indigo-100 opacity-90">Manajemen data pendidikan formal karyawan. Total: <span class="font-bold text-white">{{ $pendidikans->total() }}</span> data.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('sdm.pendidikan.create') }}" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-5 py-2.5 rounded-xl font-semibold backdrop-blur-sm transition-all flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Pendidikan
                </a>
                <button class="bg-indigo-500 hover:bg-indigo-400 text-white px-5 py-2.5 rounded-xl font-semibold transition-all flex items-center gap-2 shadow-md shadow-indigo-800/20">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                     Export Excel
                </button>
            </div>
        </div>
        
        <!-- Decorative bg -->
        <div class="absolute right-0 top-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
        <div class="absolute left-10 bottom-0 w-32 h-32 bg-purple-500 opacity-20 rounded-full blur-2xl"></div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <form action="{{ route('sdm.pendidikan.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <!-- Search -->
                <div class="md:col-span-8">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Pencarian</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2.5 rounded-lg border-slate-200 bg-slate-50 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-all placeholder:text-slate-400" placeholder="Cari nama karyawan, Institusi, atau Jurusan...">
                        <div class="absolute left-3 top-3 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Jenjang Filter -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Jenjang</label>
                    <select name="jenjang" class="w-full rounded-lg border-slate-200 bg-slate-50 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        <option value="">Semua Jenjang</option>
                        @foreach(['SD', 'SMP', 'SMA', 'D3', 'D4', 'S1', 'S2', 'S3'] as $j)
                            <option value="{{ $j }}" {{ request('jenjang') == $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Reset Button -->
                <div class="md:col-span-1">
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
                        <th class="px-6 py-4 text-center">Jenjang</th>
                        <th class="px-6 py-4">Institusi</th>
                        <th class="px-6 py-4">Jurusan</th>
                        <th class="px-6 py-4 text-center">Tahun Lulus</th>
                        <th class="px-6 py-4 text-center">Dokumen</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendidikans as $p)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <!-- Karyawan -->
                        <td class="px-6 py-4 align-middle">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                     {{ substr($p->pegawai->name, 0, 1) }}
                                </div>
                                <span class="font-bold text-slate-700 text-sm link-underline">{{ $p->pegawai->name }}</span>
                            </div>
                        </td>

                        <!-- Jenjang -->
                        <td class="px-6 py-4 align-middle text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 font-bold text-xs border border-indigo-100">
                                {{ $p->jenjang }}
                            </span>
                        </td>

                        <!-- Institusi -->
                        <td class="px-6 py-4 align-middle">
                            <span class="text-sm font-medium text-slate-600">{{ $p->institusi }}</span>
                        </td>

                        <!-- Jurusan -->
                        <td class="px-6 py-4 align-middle">
                            <span class="text-sm text-slate-500">{{ $p->jurusan ?? '-' }}</span>
                        </td>

                        <!-- Tahun Lulus -->
                        <td class="px-6 py-4 align-middle text-center">
                            <span class="font-mono text-sm text-slate-600 bg-slate-100 px-2 py-1 rounded">{{ $p->tahun_lulus }}</span>
                        </td>

                        <!-- Dokumen -->
                        <td class="px-6 py-4 align-middle text-center">
                            @if($p->dokumen_path)
                                <a href="{{ asset('storage/' . $p->dokumen_path) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 bg-sky-50 text-sky-600 rounded-lg text-xs font-medium hover:bg-sky-100 transition-colors border border-sky-100">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Ijazah
                                </a>
                            @else
                                <span class="text-slate-300 text-xs">-</span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="px-6 py-4 align-middle text-center">
                             <div class="flex items-center justify-center gap-2">
                                <button class="p-1.5 bg-sky-50 text-sky-600 rounded-lg hover:bg-sky-100 transition-colors" title="Lihat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                                <a href="{{ route('sdm.pendidikan.edit', $p->id) }}" class="p-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('sdm.pendidikan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data pendidikan ini?');" class="inline">
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
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                                </div>
                                <h3 class="text-slate-800 font-medium">Data Pendidikan Tidak Ditemukan</h3>
                                <p class="text-sm mt-1">Belum ada data riwayat pendidikan yang dicatat.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($pendidikans->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $pendidikans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
