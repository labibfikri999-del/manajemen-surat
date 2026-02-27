@extends('aset.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-slate-800 to-slate-600 tracking-tight">Data Aset</h1>
            <p class="text-slate-500 text-sm mt-1 font-medium">Kelola data aset instansi secara komprehensif</p>
        </div>
        <div class="flex gap-3">
             <button type="button" class="bg-white border border-slate-200 text-emerald-600 px-4 py-2 rounded-lg font-semibold hover:bg-emerald-50 transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="8" y1="13" x2="16" y2="13"></line><line x1="8" y1="17" x2="16" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Excel
            </button>
            <button type="button" class="bg-white border border-slate-200 text-red-500 px-4 py-2 rounded-lg font-semibold hover:bg-red-50 transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                PDF
            </button>
            <a href="{{ route('aset.inventory.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-bold shadow-md transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Aset
            </a>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden animate-fade-in-up delay-100">
        
        <!-- Filters & Search -->
        <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row justify-between gap-4">
            <form action="{{ route('aset.inventory.index') }}" method="GET" class="flex-1 flex flex-col md:flex-row gap-4">
                <div class="relative w-full md:w-80">
                    <svg class="w-5 h-5 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 h-10 rounded-lg border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400" placeholder="Cari aset...">
                </div>
                
                <div class="flex gap-4 w-full md:w-auto">
                    <select name="category" class="h-10 rounded-lg border-slate-200 text-sm focus:border-blue-500 text-slate-600 flex-1 md:w-40">
                        <option value="">Semua Kategori</option>
                        @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>

                    <select name="unit" class="h-10 rounded-lg border-slate-200 text-sm focus:border-blue-500 text-slate-600 flex-1 md:w-40">
                        <option value="">Semua Unit Kerja</option>
                        <!-- Unit options placeholder -->
                    </select>

                    <select name="condition" class="h-10 rounded-lg border-slate-200 text-sm focus:border-blue-500 text-slate-600 flex-1 md:w-40">
                        <option value="">Semua Status</option>
                        <option value="Baik" {{ request('condition') == 'Baik' ? 'selected' : '' }}>Aktif</option>
                        <option value="Rusak Ringan" {{ request('condition') == 'Rusak Ringan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                        <option value="Rusak Berat" {{ request('condition') == 'Rusak Berat' ? 'selected' : '' }}>Rusak</option>
                    </select>

                    <button type="submit" class="hidden md:block h-10 px-6 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-bold shadow-md shadow-blue-500/20 transition-all hover:-translate-y-0.5">
                        Filter
                    </button>
                    
                    @if(request()->hasAny(['search', 'category', 'condition', 'unit']))
                    <a href="{{ route('aset.inventory.index') }}" class="h-10 px-4 bg-red-50 hover:bg-red-100 text-red-600 text-sm rounded-lg font-medium transition-colors flex items-center justify-center">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Assets Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-xs uppercase text-slate-500 font-semibold">
                        <th class="p-4 w-12 text-center">
                            <input type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        </th>
                        <th class="p-4 whitespace-nowrap">
                            <a href="{{ route('aset.inventory.index', array_merge(request()->all(), ['sort' => 'code', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-blue-600">
                                KODE
                            </a>
                        </th>
                        <th class="p-4 whitespace-nowrap">
                            <a href="{{ route('aset.inventory.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-blue-600">
                                NAMA ASET
                            </a>
                        </th>
                        <th class="p-4">KATEGORI</th>
                        <th class="p-4">LOKASI</th>
                        <th class="p-4">NILAI</th>
                        <th class="p-4">STATUS</th>
                        <th class="p-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-slate-600">
                    @forelse($asets as $aset)
                    <tr class="group hover:bg-white hover:shadow-[0_4px_20px_rgb(0,0,0,0.03)] hover:-translate-y-0.5 transition-all duration-300 relative border-l-2 border-transparent hover:border-blue-500">
                        <td class="p-4 text-center">
                            <input type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        </td>
                        <td class="p-4">
                            <span class="font-medium text-blue-600">{{ $aset->code }}</span>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 shrink-0">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="font-medium text-slate-800">{{ $aset->name }}</span>
                            </div>
                        </td>
                        <td class="p-4 text-slate-500">
                            {{ $aset->category }}
                        </td>
                        <td class="p-4 text-slate-500">
                            {{ $aset->location }}
                        </td>
                        <td class="p-4 text-slate-800">
                            Rp {{ number_format($aset->price, 0, ',', '.') }}
                        </td>
                        <td class="p-4">
                            @php
                                $statusLabel = match($aset->condition) {
                                    'Baik' => ['text' => 'Aktif', 'class' => 'text-slate-600'],
                                    'Rusak Ringan' => ['text' => 'Dalam Perbaikan', 'class' => 'text-blue-600'],
                                    'Rusak Berat' => ['text' => 'Rusak', 'class' => 'text-red-600'],
                                    default => ['text' => $aset->condition, 'class' => 'text-slate-600']
                                };
                            @endphp
                            <span class="text-xs font-semibold {{ $statusLabel['class'] }}">
                                {{ $statusLabel['text'] }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('aset.inventory.edit', $aset->id) }}" class="text-blue-500 hover:text-blue-700 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button type="button" onclick="confirm('Apakah Anda yakin ingin menghapus aset ini?') || event.stopImmediatePropagation()" class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                                    <form action="{{ route('aset.inventory.destroy', $aset->id) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                <a href="{{ route('aset.inventory.show', $aset->id) }}" class="text-emerald-500 hover:text-emerald-700 transition-colors" title="Lihat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-slate-400">
                            <p class="text-sm">Belum ada data aset ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination & Display Info -->
        <div class="p-4 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500">
            <div>
                Menampilkan {{ $asets->firstItem() ?? 0 }}-{{ $asets->lastItem() ?? 0 }} dari {{ $asets->total() ?? 0 }}
            </div>
            <div>
                {{ $asets->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
