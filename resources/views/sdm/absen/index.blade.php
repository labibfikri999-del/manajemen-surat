@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 space-y-8">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Data Absensi</h1>
        <p class="text-slate-500">Kelola kehadiran pegawai rumah sakit.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content: Log Absensi -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Filter Tanggal -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center gap-4">
                <span class="font-bold text-slate-700">Tanggal:</span>
                <form action="{{ route('sdm.absen.index') }}" method="GET" class="flex-1 flex gap-2">
                    <input type="date" name="date" value="{{ $date }}" class="w-full md:w-auto flex-1 rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500">
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-xl font-bold transition-all">Lihat</button>
                </form>
            </div>

            <!-- List Absensi -->
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h2 class="font-bold text-lg text-slate-800">Log Kehadiran ({{ \Carbon\Carbon::parse($date)->format('d M Y') }})</h2>
                    <span class="text-sm text-slate-500">{{ count($attendances) }} Pegawai Hadir</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 font-bold text-slate-700">Pegawai</th>
                                <th class="px-6 py-4 font-bold text-slate-700">Jam Masuk</th>
                                <th class="px-6 py-4 font-bold text-slate-700">Jam Pulang</th>
                                <th class="px-6 py-4 font-bold text-slate-700">Status</th>
                                <th class="px-6 py-4 font-bold text-slate-700 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($attendances as $absensi)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-600 font-bold text-xs">
                                            {{ substr($absensi->pegawai->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-slate-800 text-sm">{{ $absensi->pegawai->name }}</h3>
                                            <p class="text-xs text-slate-500">{{ $absensi->pegawai->role }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ \Carbon\Carbon::parse($absensi->clock_in)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    @if($absensi->clock_out)
                                        {{ \Carbon\Carbon::parse($absensi->clock_out)->format('H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClass = match($absensi->status) {
                                            'Hadir' => 'bg-emerald-100 text-emerald-600',
                                            'Telat' => 'bg-yellow-100 text-yellow-600',
                                            'Ijin' => 'bg-blue-100 text-blue-600',
                                            'Sakit' => 'bg-purple-100 text-purple-600',
                                            default => 'bg-slate-100 text-slate-600'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ $absensi->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if(!$absensi->clock_out && $date == date('Y-m-d'))
                                    <form action="{{ route('sdm.absen.update', $absensi->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="clock_out_action" value="1">
                                        <button type="submit" class="text-xs bg-slate-800 text-white px-3 py-1.5 rounded-lg hover:bg-slate-700 transition-colors">
                                            Clock Out
                                        </button>
                                    </form>
                                    @elseif($absensi->clock_out)
                                        <span class="text-xs text-slate-400">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                    Belum ada data absensi untuk tanggal ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar: Input Manual (Only for Today) -->
        @if($date == date('Y-m-d'))
        <div class="space-y-6">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <h2 class="font-bold text-lg text-slate-800 mb-4">Input Absensi Manual</h2>
                <p class="text-sm text-slate-500 mb-6">Catat kehadiran pegawai secara manual jika belum melakukan scan.</p>

                <form action="{{ route('sdm.absen.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Pegawai</label>
                        <select name="pegawai_id" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                            <option value="" disabled selected>-- Pegawai Belum Absen --</option>
                            @foreach($pegawais as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Jam Masuk</label>
                        <input type="time" name="clock_in" value="{{ date('H:i') }}" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Status Kehadiran</label>
                        <select name="status" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                            <option value="Hadir">Hadir</option>
                            <option value="Telat">Telat</option>
                            <option value="Ijin">Ijin</option>
                            <option value="Sakit">Sakit</option>
                        </select>
                    </div>

                     <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Catatan (Opsional)</label>
                        <textarea name="notes" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" rows="2"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white py-3 rounded-xl font-bold shadow-lg shadow-cyan-200 transition-all active:scale-95">
                        Simpan Absensi
                    </button>
                </form>
            </div>
            
            <div class="bg-blue-50 rounded-3xl p-6 border border-blue-100">
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-blue-800 mb-1">Panduan Absensi</h3>
                        <p class="text-sm text-blue-600 leading-relaxed">
                            Pastikan pegawai yang dipilih adalah benar sebelum menyimpan. Data absensi mempengaruhi perhitungan gaji bulanan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="bg-slate-50 rounded-3xl p-6 border border-slate-200 text-center">
            <p class="text-slate-500">Anda sedang melihat data historis. Input manual hanya tersedia untuk hari ini.</p>
            <a href="{{ route('sdm.absen.index') }}" class="text-cyan-600 font-bold hover:underline mt-2 inline-block">Kembali ke Hari Ini</a>
        </div>
        @endif
    </div>
</div>
@endsection
