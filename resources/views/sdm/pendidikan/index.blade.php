@extends('sdm.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6" x-data="{ viewMode: 'list' }">
    
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

    <!-- View Toggle & Filters -->
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
        <!-- View Toggle -->
        <div class="bg-slate-100 p-1 rounded-lg flex items-center shadow-inner">
            <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-md text-sm font-semibold transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                List View
            </button>
            <button @click="viewMode = 'timeline'" :class="viewMode === 'timeline' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-md text-sm font-semibold transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Timeline View
            </button>
        </div>

        <!-- Filters Form -->
        <form action="{{ route('sdm.pendidikan.index') }}" method="GET" class="flex-1 w-full md:w-auto">
            <div class="flex flex-col md:flex-row gap-3 justify-end">
                <input type="text" name="search" value="{{ request('search') }}" class="flex-1 md:w-64 px-4 py-2 rounded-lg border-slate-200 bg-white text-slate-800 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Cari nama, institusi, jurusan...">
                
                <select name="jenjang" class="md:w-40 px-4 py-2 rounded-lg border-slate-200 bg-white text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    <option value="">Semua Jenjang</option>
                    @foreach(['SD', 'SMP', 'SMA', 'D3', 'D4', 'S1', 'S2', 'S3'] as $j)
                        <option value="{{ $j }}" {{ request('jenjang') == $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
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
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs ring-2 ring-white">
                                     {{ substr($p->pegawai->name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-700 text-sm hover:text-indigo-600 transition-colors cursor-pointer">{{ $p->pegawai->name }}</span>
                                    <span class="text-[10px] text-slate-400">NIK: {{ $p->pegawai->nip ?? '-' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Jenjang -->
                        <td class="px-6 py-4 align-middle text-center">
                            @php
                                $colors = [
                                    'SD' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    'SMP' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    'SMA' => 'bg-blue-50 text-blue-600 border-blue-200',
                                    'D3' => 'bg-cyan-50 text-cyan-600 border-cyan-200',
                                    'D4' => 'bg-teal-50 text-teal-600 border-teal-200',
                                    'S1' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                    'S2' => 'bg-purple-50 text-purple-600 border-purple-200',
                                    'S3' => 'bg-rose-50 text-rose-600 border-rose-200',
                                ];
                                $color = $colors[$p->jenjang] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                            @endphp
                            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $color }}">
                                {{ $p->jenjang }}
                            </span>
                        </td>

                        <!-- Institusi -->
                        <td class="px-6 py-4 align-middle">
                            <span class="text-sm font-medium text-slate-700">{{ $p->institusi }}</span>
                        </td>

                        <!-- Jurusan -->
                        <td class="px-6 py-4 align-middle">
                            <span class="text-sm text-slate-500">{{ $p->jurusan ?? '-' }}</span>
                        </td>

                        <!-- Tahun Lulus -->
                        <td class="px-6 py-4 align-middle text-center">
                            <span class="font-mono text-sm text-slate-600 bg-slate-100 px-2 py-1 rounded border border-slate-200">{{ $p->tahun_lulus }}</span>
                        </td>

                        <!-- Dokumen -->
                        <td class="px-6 py-4 align-middle text-center">
                            @if($p->dokumen_path)
                                <a href="{{ asset('storage/' . $p->dokumen_path) }}" target="_blank" class="group/doc relative inline-flex items-center justify-center">
                                    <div class="w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100 transition-colors border border-red-100">
                                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover/doc:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Lihat Ijazah</span>
                                </a>
                            @else
                                <span class="text-slate-300 text-xs">-</span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="px-6 py-4 align-middle text-center">
                             <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('sdm.pendidikan.edit', $p->id) }}" class="p-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors border border-amber-100" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('sdm.pendidikan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data pendidikan ini?');" class="inline">
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

    <!-- Timeline View -->
    <div x-show="viewMode === 'timeline'" x-cloak x-transition:enter="transition opacity-0 duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-8">
        @php
            $groupedByYear = $pendidikans->groupBy('tahun_lulus');
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
                 @foreach($items as $p)
                 <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 hover:shadow-md transition-shadow relative overflow-hidden group">
                     <!-- Accent Bar -->
                     <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 rounded-l"></div>
                     
                     <div class="flex justify-between items-start mb-4">
                         <div class="flex items-center gap-3">
                             <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold border border-slate-200 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                  {{ substr($p->pegawai->name, 0, 1) }}
                             </div>
                             <div>
                                 <h3 class="font-bold text-slate-800 text-sm line-clamp-1">{{ $p->pegawai->name }}</h3>
                                 <p class="text-[11px] text-slate-500 uppercase tracking-wide">{{ $p->jenjang }} - {{ $p->jurusan ?? '-' }}</p>
                             </div>
                         </div>
                         <div class="flex gap-1">
                             <a href="{{ route('sdm.pendidikan.edit', $p->id) }}" class="text-slate-400 hover:text-amber-500 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                         </div>
                     </div>
                     
                     <div class="mb-4">
                         <div class="flex items-center gap-2 text-slate-600 mb-1">
                             <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                             <span class="font-medium text-sm">{{ $p->institusi }}</span>
                         </div>
                     </div>

                     @if($p->dokumen_path)
                     <a href="{{ asset('storage/' . $p->dokumen_path) }}" target="_blank" class="block w-full text-center py-2 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-bold hover:bg-indigo-100 transition-colors border border-indigo-100">
                         Lihat Ijazah
                     </a>
                     @endif
                 </div>
                 @endforeach
             </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center py-12 text-slate-400">
            <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p>Tidak ada data untuk timeline.</p>
        </div>
        @endforelse
        
        <!-- Pagination for Timeline (reuse same links) -->
         @if($pendidikans->hasPages())
        <div class="pt-6">
            {{ $pendidikans->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
