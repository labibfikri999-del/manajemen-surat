@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-4xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('sdm.pegawai.index') }}" class="p-2 bg-white rounded-full text-slate-500 hover:text-cyan-600 shadow-sm border border-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Edit Data Pegawai</h1>
            <p class="text-slate-500">Perbarui informasi pegawai.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
        <form action="{{ route('sdm.pegawai.update', $pegawai->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $pegawai->name) }}" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" placeholder="Contoh: Dr. Budi Santoso" required>
                </div>

                <!-- NIP -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Induk Pegawai (NIP)</label>
                    <input type="text" name="nip" value="{{ old('nip', $pegawai->nip) }}" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" placeholder="Contoh: 2023001" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Role/Jabatan -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Jabatan / Role</label>
                    <select name="role" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                        <option value="" disabled>-- Pilih Jabatan --</option>
                        @foreach(['Dokter Umum', 'Dokter Spesialis', 'Dokter Gigi', 'Perawat Senior', 'Perawat', 'Apoteker', 'Staff Admin', 'Cleaning Service'] as $role)
                            <option value="{{ $role }}" {{ old('role', $pegawai->role) == $role ? 'selected' : '' }}>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Bergabung -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Bergabung</label>
                    <input type="date" name="join_date" value="{{ old('join_date', $pegawai->join_date) }}" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email (Opsional)</label>
                    <input type="email" name="email" value="{{ old('email', $pegawai->email) }}" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" placeholder="email@hospital.com">
                </div>

                <!-- No. Telepon -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">No. Telepon (Opsional)</label>
                    <input type="text" name="phone" value="{{ old('phone', $pegawai->phone) }}" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" placeholder="0812...">
                </div>
            </div>

            <div class="border-t border-slate-50 pt-6">
                <h3 class="font-bold text-lg text-slate-800 mb-4">Informasi Gaji</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Gaji Pokok -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Gaji Pokok (Rp)</label>
                        <input type="number" name="gaji_pokok" value="{{ old('gaji_pokok', $pegawai->gaji_pokok) }}" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500">
                    </div>

                    <!-- Tunjangan -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tunjangan Tetap (Rp)</label>
                        <input type="number" name="tunjangan" value="{{ old('tunjangan', $pegawai->tunjangan) }}" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500">
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="pt-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Status Pegawai</label>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="active" {{ old('status', $pegawai->status) == 'active' ? 'checked' : '' }} class="text-cyan-600 focus:ring-cyan-500">
                        <span class="text-slate-700">Aktif</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="inactive" {{ old('status', $pegawai->status) == 'inactive' ? 'checked' : '' }} class="text-slate-500 focus:ring-slate-500">
                        <span class="text-slate-500">Non-Aktif</span>
                    </label>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-cyan-200 transition-all active:scale-95">
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
