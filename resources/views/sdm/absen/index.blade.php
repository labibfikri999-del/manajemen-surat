@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Manajemen Absensi</h1>
            <p class="text-slate-500">Input dan monitor kehadiran pegawai hari ini.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-bold text-slate-700">
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
            </span>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Input Absensi Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-cyan-100 text-cyan-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </span>
                    Input Absen
                </h2>
                
                @if($pegawais->isEmpty())
                <div class="text-center py-6 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                    <p class="text-slate-500 text-sm">Semua pegawai sudah absen hari ini.</p>
                </div>
                @else
                <form action="{{ route('sdm.absen.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Pilih Pegawai</label>
                        <select name="pegawai_id" class="w-full rounded-xl border-slate-200 text-slate-700 text-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                            <option value="" disabled selected>-- Pilih Nama --</option>
                            @foreach($pegawais as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->role }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Jam Masuk</label>
                            <input type="time" name="clock_in" value="{{ date('H:i') }}" class="w-full rounded-xl border-slate-200 text-slate-700 text-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Status</label>
                            <select name="status" class="w-full rounded-xl border-slate-200 text-slate-700 text-sm focus:border-cyan-500 focus:ring-cyan-500">
                                <option value="Hadir">Hadir</option>
                                <option value="Telat">Telat</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Ijin">Ijin</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Catatan</label>
                        <input type="text" name="notes" placeholder="Opsional" class="w-full rounded-xl border-slate-200 text-slate-700 text-sm focus:border-cyan-500 focus:ring-cyan-500">
                    </div>

                    <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white py-3 rounded-xl font-bold transition-all shadow-lg shadow-cyan-200 active:scale-95">
                        Simpan Absensi
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Attendance List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <h2 class="text-lg font-bold text-slate-800 mb-6">Kehadiran Hari Ini</h2>
                
                <div class="space-y-3">
                    @forelse($attendances as $att)
                    <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-sm">
                                {{ substr($att->pegawai->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">{{ $att->pegawai->name }}</h4>
                                <p class="text-xs text-slate-500">{{ $att->pegawai->role }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $att->status == 'Hadir' ? 'bg-green-100 text-green-700' : ($att->status == 'Telat' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                {{ $att->status }}
                            </span>
                            <p class="text-xs font-mono text-slate-500 mt-1">{{ \Carbon\Carbon::parse($att->clock_in)->format('H:i') }}</p>
                        </div>
                    </div>
                    @empty
                     <div class="text-center py-12">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-slate-500 font-medium">Belum ada data absensi hari ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
