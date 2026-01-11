@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-4xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('sdm.jadwal.index') }}" class="p-2 bg-white rounded-full text-slate-500 hover:text-cyan-600 shadow-sm border border-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Edit Jadwal Shift</h1>
            <p class="text-slate-500">Perbarui jadwal shift pegawai.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
        <form action="{{ route('sdm.jadwal.update', $shift->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pegawai -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Pegawai</label>
                    <select name="sdm_pegawai_id" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                        <option value="" disabled>-- Pilih Pegawai --</option>
                        @foreach($pegawais as $pegawai)
                            <option value="{{ $pegawai->id }}" {{ old('sdm_pegawai_id', $shift->sdm_pegawai_id) == $pegawai->id ? 'selected' : '' }}>{{ $pegawai->name }} - {{ $pegawai->role }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Shift Name -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Shift</label>
                    <select name="shift_name" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                        <option value="" disabled>-- Pilih Shift --</option>
                        @foreach(['Pagi', 'Siang', 'Malam'] as $name)
                            <option value="{{ $name }}" {{ old('shift_name', $shift->shift_name) == $name ? 'selected' : '' }}>Shift {{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                         @foreach(['Scheduled', 'On Duty', 'Completed', 'Absent'] as $status)
                            <option value="{{ $status }}" {{ old('status', $shift->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal</label>
                    <input type="date" name="date" value="{{ old('date', $shift->date) }}" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                </div>

                <!-- Waktu Mulai -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Waktu Mulai</label>
                    <input type="time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($shift->start_time)->format('H:i')) }}" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                </div>

                <!-- Waktu Selesai -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Waktu Selesai</label>
                    <input type="time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($shift->end_time)->format('H:i')) }}" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-cyan-200 transition-all active:scale-95">
                    Perbarui Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
