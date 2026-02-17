@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-6xl mx-auto space-y-6">
    <!-- Breadcrumb / Back Button -->
    <div>
        <a href="{{ route('sdm.pegawai.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-lg text-slate-600 hover:text-cyan-600 shadow-sm border border-slate-200 transition-colors text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">Edit Data Pegawai</h1>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100">
        <form action="{{ route('sdm.pegawai.update', $pegawai->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Left Column: Photo Upload -->
                <div class="w-full md:w-1/4 flex flex-col items-center space-y-4">
                    <div class="w-48 h-48 bg-slate-100 rounded-xl border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden relative group">
                        <!-- Preview Image -->
                        <img id="photo-preview" src="{{ $pegawai->foto ? asset('storage/' . $pegawai->foto) : '#' }}" alt="Preview" class="w-full h-full object-cover {{ $pegawai->foto ? '' : 'hidden' }}">
                        
                        <!-- Placeholder -->
                        <div id="photo-placeholder" class="flex flex-col items-center justify-center text-slate-400 {{ $pegawai->foto ? 'hidden' : '' }}">
                            <svg class="w-16 h-16 mb-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                            <span class="text-xs font-medium">Foto Profil (Opsional)</span>
                        </div>
                    </div>

                    <div class="w-full text-center space-y-2">
                        <label class="block text-sm font-bold text-slate-700">Upload Foto Baru</label>
                        <input type="file" name="foto" id="foto-input" accept="image/*" class="block w-full text-sm text-slate-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-lg file:border-0
                            file:text-sm file:font-semibold
                            file:bg-cyan-50 file:text-cyan-700
                            hover:file:bg-cyan-100
                            cursor-pointer border border-slate-200 rounded-lg
                        ">
                        <p class="text-xs text-slate-500">Format: JPG, PNG. Max: 2MB</p>
                    </div>
                </div>

                <!-- Right Column: Form Fields -->
                <div class="w-full md:w-3/4 space-y-6">
                    <!-- Row 1 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">NIK <span class="text-red-500">*</span></label>
                            <input type="text" name="nip" value="{{ old('nip', $pegawai->nip) }}" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 shadow-sm placeholder:text-slate-400" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">NIDN (Opsional)</label>
                            <input type="text" name="nidn" value="{{ old('nidn', $pegawai->nidn) }}" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 shadow-sm placeholder:text-slate-400">
                        </div>
                    </div>

                    <!-- Row 2 -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $pegawai->name) }}" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 shadow-sm placeholder:text-slate-400" required>
                    </div>

                    <!-- Row 3 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="jenis_kelamin" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 shadow-sm" required>
                                <option value="" disabled>Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 shadow-sm placeholder:text-slate-400">
                        </div>
                    </div>

                    <!-- Row 4 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir) }}" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $pegawai->phone) }}" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 shadow-sm placeholder:text-slate-400">
                        </div>
                    </div>

                    <!-- Row 5 -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $pegawai->email) }}" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 shadow-sm placeholder:text-slate-400">
                    </div>

                     <!-- Row 5.5: Jabatan -->
                     <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Jabatan / Role <span class="text-red-500">*</span></label>
                        <select name="role" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 shadow-sm" required>
                            <option value="" disabled>Pilih Jabatan</option>
                            @foreach(['Dokter Umum', 'Dokter Spesialis', 'Dokter Gigi', 'Perawat', 'Perawat Senior', 'Bidan', 'Apoteker', 'Staff Admin', 'Cleaning Service', 'Satpam'] as $role)
                                <option value="{{ $role }}" {{ old('role', $pegawai->role) == $role ? 'selected' : '' }}>{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Row 6 -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" rows="3" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 shadow-sm placeholder:text-slate-400">{{ old('alamat_lengkap', $pegawai->alamat_lengkap) }}</textarea>
                    </div>

                    <!-- Row 7 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Pengangkatan <span class="text-red-500">*</span></label>
                            <input type="date" name="join_date" value="{{ old('join_date', $pegawai->join_date) }}" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Status Kerja <span class="text-red-500">*</span></label>
                            <select name="status_kerja" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 shadow-sm" required>
                                <option value="" disabled>Pilih Status Kerja</option>
                                @foreach(['Tetap', 'Kontrak', 'Magang', 'Paruh Waktu'] as $status)
                                    <option value="{{ $status }}" {{ old('status_kerja', $pegawai->status_kepegawaian) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Row 8 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Status Aktif <span class="text-red-500">*</span></label>
                            <select name="status" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 shadow-sm" required>
                                <option value="active" {{ old('status', $pegawai->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status', $pegawai->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="cuti" {{ old('status', $pegawai->status) == 'cuti' ? 'selected' : '' }}>Cuti</option>
                            </select>
                        </div>
                         <!-- Gaji Hidden or as needed -->
                    </div>

                    <!-- Informasi Gaji Section -->
                    <div class="border-t border-slate-50 pt-6 mt-6">
                        <h3 class="font-bold text-lg text-slate-800 mb-4">Informasi Gaji</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Gaji Pokok -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Gaji Pokok (Rp)</label>
                                <input type="number" name="gaji_pokok" value="{{ old('gaji_pokok', $pegawai->gaji_pokok) }}" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 shadow-sm">
                            </div>

                            <!-- Tunjangan -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Tunjangan Tetap (Rp)</label>
                                <input type="number" name="tunjangan" value="{{ old('tunjangan', $pegawai->tunjangan) }}" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 shadow-sm">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-100">
                <a href="{{ route('sdm.pegawai.index') }}" class="px-6 py-2.5 rounded-lg bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all active:scale-95">
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('foto-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photo-preview').src = e.target.result;
                document.getElementById('photo-preview').classList.remove('hidden');
                document.getElementById('photo-placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
