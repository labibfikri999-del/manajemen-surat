@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-4xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('sdm.jadwal.index') }}" class="p-2 bg-white rounded-full text-slate-500 hover:text-cyan-600 shadow-sm border border-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Tambah Jadwal Shift</h1>
            <p class="text-slate-500">Buat jadwal shift baru untuk pegawai.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
        <form action="{{ route('sdm.jadwal.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pegawai -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Pegawai</label>
                    <select name="sdm_pegawai_id" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                        <option value="" disabled selected>-- Pilih Pegawai --</option>
                        @foreach($pegawais as $pegawai)
                            <option value="{{ $pegawai->id }}">{{ $pegawai->name }} - {{ $pegawai->role }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Shift Name -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Shift</label>
                    <select name="shift_name" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                        <option value="" disabled selected>-- Pilih Shift --</option>
                        <option value="Pagi">Shift Pagi (07:00 - 14:00)</option>
                        <option value="Siang">Shift Siang (14:00 - 21:00)</option>
                        <option value="Malam">Shift Malam (21:00 - 07:00)</option>
                    </select>
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal</label>
                    <input type="date" name="date" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                </div>

                <!-- Waktu Mulai -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Waktu Mulai</label>
                    <input type="time" name="start_time" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                </div>

                <!-- Waktu Selesai -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Waktu Selesai</label>
                    <input type="time" name="end_time" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-cyan-200 transition-all active:scale-95">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
