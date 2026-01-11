@extends('aset.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    <!-- Header & Stats -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Inventaris Aset</h1>
            <p class="text-slate-500">Kelola database aset dan inventaris perusahaan.</p>
        </div>
        <a href="{{ route('aset.inventory.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-emerald-200 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Aset Baru
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Aset</p>
            <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider">Kondisi Baik</p>
            <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['baik'] }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-rose-500 uppercase tracking-wider">Perlu Perbaikan</p>
            <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['rusak'] }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-blue-500 uppercase tracking-wider">Nilai Aset</p>
            <p class="text-xl font-bold text-slate-800 mt-1 truncate">Rp {{ number_format($stats['value'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <form action="{{ route('aset.inventory.index') }}" method="GET" class="flex gap-4">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 absolute left-3 top-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 placeholder-slate-400" placeholder="Cari aset berdasarkan nama, kode, atau kategori...">
                </div>
                <select name="condition" class="rounded-xl border-slate-200 focus:border-emerald-500 text-slate-600">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik" {{ request('condition') == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak Ringan" {{ request('condition') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="Rusak Berat" {{ request('condition') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
                <button type="submit" class="bg-slate-800 text-white px-6 rounded-xl font-bold hover:bg-slate-700 transition-colors">Filter</button>
            </form>
        </div>
    </div>

    <!-- Assets Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($asets as $aset)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 group hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col">
            <!-- Image Area -->
            <div class="h-48 bg-slate-100 relative overflow-hidden">
                @if($aset->photo)
                    <img src="{{ asset('storage/' . $aset->photo) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $aset->name }}">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
                
                <div class="absolute top-3 right-3">
                    <span class="px-3 py-1 bg-white/90 backdrop-blur text-xs font-bold rounded-full shadow-sm {{ $aset->condition === 'Baik' ? 'text-emerald-600' : 'text-rose-500' }}">
                        {{ $aset->condition }}
                    </span>
                </div>
            </div>

            <div class="p-5 flex-1 flex flex-col">
                <div class="mb-4">
                    <p class="text-xs text-slate-400 font-mono mb-1">{{ $aset->code }}</p>
                    <h3 class="text-lg font-bold text-slate-800 leading-tight mb-2 line-clamp-2 group-hover:text-emerald-600 transition-colors">
                        <a href="{{ route('aset.inventory.show', $aset->id) }}">{{ $aset->name }}</a>
                    </h3>
                    <p class="text-sm text-slate-500 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $aset->location }}
                    </p>
                </div>
                
                <div class="mt-auto pt-4 border-t border-slate-50 flex justify-between items-center">
                    <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded">{{ $aset->category }}</span>
                    <a href="{{ route('aset.inventory.show', $aset->id) }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700">Detail &rarr;</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-slate-400">
            <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            <p>Belum ada data aset yang ditemukan.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $asets->links() }}
    </div>
</div>
@endsection
