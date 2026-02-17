@extends('sdm.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">Tambah Anggota Keluarga</h1>
        <a href="{{ route('sdm.keluarga.index') }}" class="text-slate-500 hover:text-indigo-600 font-medium text-sm flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('sdm.keluarga.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            
            <!-- Employee Selection -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Karyawan <span class="text-red-500">*</span></label>
                <select name="sdm_pegawai_id" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 select2">
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach($pegawais as $pegawai)
                        <option value="{{ $pegawai->id }}" {{ old('sdm_pegawai_id') == $pegawai->id ? 'selected' : '' }}>{{ $pegawai->name }} - {{ $pegawai->nip }}</option>
                    @endforeach
                </select>
                @error('sdm_pegawai_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Keluarga -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Nama anggota keluarga" required>
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hubungan -->
                <div>
                     <label class="block text-sm font-bold text-slate-700 mb-2">Hubungan Keluarga <span class="text-red-500">*</span></label>
                    <select name="hubungan" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">-- Pilih Hubungan --</option>
                        @foreach(['Istri', 'Suami', 'Anak', 'Orang Tua'] as $h)
                            <option value="{{ $h }}" {{ old('hubungan') == $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                    @error('hubungan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tgl Lahir -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir') }}" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                    @error('tgl_lahir')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pekerjaan -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Pekerjaan / Sekolah</label>
                    <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Mahasiswa, PNS, Pelajar">
                    @error('pekerjaan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Upload Dokumen -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Upload Dokumen Pendukung (KK/Akta/Buku Nikah)</label>
                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-lg cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-slate-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-slate-500"><span class="font-semibold">Klik untuk upload</span></p>
                            <p class="text-xs text-slate-500">PDF, JPG, PNG (Max 2MB)</p>
                        </div>
                        <input id="dropzone-file" name="dokumen" type="file" class="hidden" accept=".pdf,.jpg,.jpeg,.png" />
                    </label>
                </div> 
                @error('dokumen')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Keterangan -->
            <div>
                 <label class="block text-sm font-bold text-slate-700 mb-2">Keterangan Tambahan</label>
                 <textarea name="keterangan" rows="3" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Keterangan lain jika diperlukan...">{{ old('keterangan') }}</textarea>
            </div>


            <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('sdm.keluarga.index') }}" class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-semibold shadow-lg shadow-indigo-200 transition-colors">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
