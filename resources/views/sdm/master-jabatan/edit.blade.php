@extends('sdm.layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">Edit Jabatan</h1>
        <a href="{{ route('sdm.master-jabatan.index') }}" class="text-slate-500 hover:text-indigo-600 font-medium text-sm flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('sdm.master-jabatan.update', $jabatan->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Nama Jabatan -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Jabatan <span class="text-red-500">*</span></label>
                <input type="text" name="nama_jabatan" value="{{ old('nama_jabatan', $jabatan->nama_jabatan) }}" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Kepala Bagian Umum" required>
                @error('nama_jabatan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Urutan -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Urutan Tampil</label>
                <input type="number" name="urutan" value="{{ old('urutan', $jabatan->urutan) }}" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="0">
                <p class="text-xs text-slate-500 mt-1">Semakin kecil angkanya, semakin di atas posisinya.</p>
                @error('urutan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Aktif -->
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $jabatan->is_active) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_active" class="text-sm font-medium text-slate-700">Status Aktif</label>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('sdm.master-jabatan.index') }}" class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-semibold shadow-lg shadow-indigo-200 transition-colors">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
