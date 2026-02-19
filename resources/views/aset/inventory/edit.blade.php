@extends('aset.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-4xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('aset.inventory.index') }}" class="p-2 bg-white rounded-full text-slate-500 hover:text-emerald-600 shadow-sm border border-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Edit Data Aset</h1>
            <p class="text-slate-500">Perbarui informasi aset perusahaan.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
        <form action="{{ route('aset.inventory.update', $aset->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Aset</label>
                    <input type="text" name="name" value="{{ old('name', $aset->name) }}" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kode Aset</label>
                    <input type="text" value="{{ $aset->code }}" class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-500" disabled>
                </div>
            </div>

            <!-- Category & Location -->
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                    <select name="category" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" required>
                        <option value="Elektronik" {{ $aset->category == 'Elektronik' ? 'selected' : '' }}>Elektronik & IT</option>
                        <option value="Furniture" {{ $aset->category == 'Furniture' ? 'selected' : '' }}>Furniture & Mebel</option>
                        <option value="Kendaraan" {{ $aset->category == 'Kendaraan' ? 'selected' : '' }}>Kendaraan</option>
                        <option value="Alat Medis" {{ $aset->category == 'Alat Medis' ? 'selected' : '' }}>Alat Medis</option>
                        <option value="Lainnya" {{ $aset->category == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Lokasi Penempatan</label>
                    <input type="text" name="location" value="{{ old('location', $aset->location) }}" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" required>
                </div>
            </div>

            <!-- Detail Specs -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Merek (Brand)</label>
                    <input type="text" name="brand" value="{{ old('brand', $aset->brand) }}" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Model / Tipe</label>
                    <input type="text" name="model" value="{{ old('model', $aset->model) }}" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kondisi Saat Ini</label>
                    <select name="condition" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="Baik" {{ $aset->condition == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ $aset->condition == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ $aset->condition == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>
            </div>

            <!-- Financials -->
            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Informasi Pembelian
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Beli</label>
                        <input type="date" name="purchase_date" value="{{ old('purchase_date', $aset->purchase_date ? $aset->purchase_date->format('Y-m-d') : '') }}" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Harga Beli (Rp)</label>
                        <input type="number" name="price" value="{{ old('price', $aset->price) }}" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                </div>
            </div>

            <!-- Photo & Notes -->
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Foto Aset</label>
                    <input type="file" name="photo" class="w-full rounded-xl border border-slate-200 p-2 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-colors">
                    <p class="text-xs text-slate-400 mt-1">Upload foto baru untuk mengganti.</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Tambahan</label>
                    <textarea name="notes" rows="3" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">{{ old('notes', $aset->notes) }}</textarea>
                </div>
            </div>
            
            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-200 transition-all active:scale-95">
                    Update Aset
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
