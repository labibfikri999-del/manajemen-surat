@extends('aset.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- Header & Stats -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Inventaris Aset</h1>
            <p class="text-slate-500">Kelola database aset dan inventaris perusahaan.</p>
        </div>
        <div class="flex gap-3">
             <button onclick="window.print()" class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-xl font-medium hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2-4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print
            </button>
            <a href="{{ route('aset.inventory.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-xl font-bold shadow-lg shadow-emerald-200 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Aset
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Aset</p>
            <p class="text-3xl font-bold text-slate-800 mt-1">{{ number_format($stats['total'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider">Kondisi Baik</p>
            <p class="text-3xl font-bold text-slate-800 mt-1">{{ number_format($stats['baik'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-rose-500 uppercase tracking-wider">Perlu Perbaikan</p>
            <p class="text-3xl font-bold text-slate-800 mt-1">{{ number_format($stats['rusak'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-blue-500 uppercase tracking-wider">Nilai Aset</p>
            <p class="text-xl font-bold text-slate-800 mt-1 truncate">Rp {{ number_format($stats['value'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
        <form action="{{ route('aset.inventory.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <svg class="w-5 h-5 absolute left-3 top-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 placeholder-slate-400" placeholder="Cari nama, kode, brand...">
            </div>
            
            <select name="category" class="rounded-xl border-slate-200 focus:border-emerald-500 text-slate-600 w-full md:w-auto">
                <option value="">Semua Kategori</option>
                @foreach($categories ?? [] as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>

            <select name="condition" class="rounded-xl border-slate-200 focus:border-emerald-500 text-slate-600 w-full md:w-auto">
                <option value="">Semua Kondisi</option>
                <option value="Baik" {{ request('condition') == 'Baik' ? 'selected' : '' }}>Baik</option>
                <option value="Rusak Ringan" {{ request('condition') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="Rusak Berat" {{ request('condition') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
            </select>
            
            <button type="submit" class="bg-slate-800 text-white px-6 py-2 rounded-xl font-bold hover:bg-slate-700 transition-colors">
                Filter
            </button>
            
            @if(request()->hasAny(['search', 'category', 'condition']))
            <a href="{{ route('aset.inventory.index') }}" class="bg-slate-100 text-slate-600 px-4 py-2 rounded-xl font-bold hover:bg-slate-200 transition-colors flex items-center justify-center">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Assets Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase text-slate-500 font-semibold tracking-wider">
                        <th class="p-4 w-16">Aset</th>
                        <th class="p-4">
                            <a href="{{ route('aset.inventory.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-emerald-600">
                                Nama & Kode
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                            </a>
                        </th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Lokasi</th>
                        <th class="p-4">Kondisi</th>
                        <th class="p-4 text-right">
                             <a href="{{ route('aset.inventory.index', array_merge(request()->all(), ['sort' => 'price', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center justify-end gap-1 hover:text-emerald-600">
                                Nilai (Rp)
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                            </a>
                        </th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($asets as $aset)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="p-4">
                            <div class="w-12 h-12 rounded-lg bg-slate-100 overflow-hidden relative">
                                @if($aset->photo)
                                    <img src="{{ asset('storage/' . $aset->photo) }}" class="w-full h-full object-cover" alt="Foto">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-slate-800">{{ $aset->name }}</div>
                            <div class="text-xs text-slate-400 font-mono mt-0.5">{{ $aset->code }}</div>
                            @if($aset->brand)
                            <div class="text-xs text-slate-500 mt-1">{{ $aset->brand }} {{ $aset->model }}</div>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                {{ $aset->category }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-1.5 text-sm text-slate-600">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $aset->location }}
                            </div>
                        </td>
                        <td class="p-4">
                            @php
                                $conditionColor = match($aset->condition) {
                                    'Baik' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'Rusak Ringan' => 'bg-orange-50 text-orange-600 border-orange-100',
                                    'Rusak Berat' => 'bg-red-50 text-red-600 border-red-100',
                                    default => 'bg-slate-50 text-slate-600 border-slate-100'
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $conditionColor }}">
                                {{ $aset->condition }}
                            </span>
                        </td>
                        <td class="p-4 text-right font-mono text-sm text-slate-600">
                            Rp {{ number_format($aset->price, 0, ',', '.') }}
                        </td>
                        <td class="p-4">
                            <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('aset.inventory.show', $aset->id) }}" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50 transition-all shadow-sm" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('aset.inventory.edit', $aset->id) }}" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-sm" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button type="button" onclick="confirm('Apakah Anda yakin ingin menghapus aset ini?') || event.stopImmediatePropagation()" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-all shadow-sm" title="Hapus">
                                    <form action="{{ route('aset.inventory.destroy', $aset->id) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-12 text-center text-slate-400">
                            <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <p class="text-lg font-medium text-slate-500">Belum ada data aset</p>
                            <p class="text-sm mt-1">Silakan tambahkan aset baru untuk memulai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 border-t border-slate-100 bg-slate-50">
            {{ $asets->links() }}
        </div>
    </div>
</div>
@endsection
