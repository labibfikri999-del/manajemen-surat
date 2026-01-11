@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-4xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('sdm.pegawai.index') }}" class="p-2 bg-white rounded-full text-slate-500 hover:text-cyan-600 shadow-sm border border-slate-100 transition-colors">
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
            
            <!-- Opsi Buat Akun Login -->
            <div x-data="{ createAccount: false }" class="border-t border-slate-50 pt-6">
                <div class="flex items-center gap-2 mb-4">
                    <input type="checkbox" id="create_account" name="create_account" value="1" x-model="createAccount" class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500 w-5 h-5">
                    <label for="create_account" class="text-sm font-bold text-slate-700 select-none cursor-pointer">Buatkan Akun Login Portal Pegawai?</label>
                </div>

                <div x-show="createAccount" x-transition class="bg-slate-50 rounded-xl p-6 space-y-4 border border-slate-200">
                    <div class="flex items-center gap-2 text-cyan-600 mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        <h3 class="font-bold text-sm">Pengaturan Akun Login</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                            <input type="text" name="username" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" placeholder="Username untuk login">
                        </div>
                        <div>
                             <!-- Empty filler for alignment -->
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                            <input type="password" name="password" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" placeholder="Minimal 6 karakter">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" placeholder="Ulangi password">
                        </div>
                    </div>
                     <p class="text-xs text-slate-500 italic mt-2">* Akun ini akan otomatis terhubung dengan data pegawai via email.</p>
                </div>
            </div>
            
            <div class="border-t border-slate-50 pt-6">
                <h3 class="font-bold text-lg text-slate-800 mb-4">Informasi Gaji</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Gaji Pokok -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Gaji Pokok (Rp)</label>
                        <input type="number" name="gaji_pokok" value="0" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500">
                    </div>

                    <!-- Tunjangan -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tunjangan Tetap (Rp)</label>
                        <input type="number" name="tunjangan" value="0" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500">
                    </div>
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
