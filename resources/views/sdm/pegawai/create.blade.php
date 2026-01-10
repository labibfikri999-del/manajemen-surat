@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-4xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('sdm.dashboard') }}" class="p-2 bg-white rounded-full text-slate-500 hover:text-cyan-600 shadow-sm border border-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Tambah Pegawai Baru</h1>
            <p class="text-slate-500">Formulir pendaftaran data pegawai.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
        <form action="{{ route('sdm.pegawai.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" placeholder="Contoh: Dr. Budi Santoso" required>
                </div>

                <!-- NIP -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Induk Pegawai (NIP)</label>
                    <input type="text" name="nip" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" placeholder="Contoh: 2023001" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Role/Jabatan -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Jabatan / Role</label>
                    <select name="role" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                        <option value="" disabled selected>-- Pilih Jabatan --</option>
                        <option value="Dokter Umum">Dokter Umum</option>
                        <option value="Dokter Spesialis">Dokter Spesialis</option>
                        <option value="Dokter Gigi">Dokter Gigi</option>
                        <option value="Perawat Senior">Perawat Senior</option>
                        <option value="Perawat">Perawat</option>
                        <option value="Apoteker">Apoteker</option>
                        <option value="Staff Admin">Staff Admin</option>
                        <option value="Cleaning Service">Cleaning Service</option>
                    </select>
                </div>

                <!-- Tanggal Bergabung -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Bergabung</label>
                    <input type="date" name="join_date" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email (Opsional)</label>
                    <input type="email" name="email" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" placeholder="email@hospital.com">
                </div>

                <!-- No. Telepon -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">No. Telepon (Opsional)</label>
                    <input type="text" name="phone" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" placeholder="0812...">
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-cyan-200 transition-all active:scale-95">
                    Simpan Data Pegawai
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
