@extends('pegawai.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Riwayat Absensi</h1>
            <p class="text-slate-500">Monitor catatan kehadiran dan ketepatan waktu Anda.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-center items-center text-center">
            <div class="w-12 h-12 rounded-2xl bg-fuchsia-50 text-fuchsia-600 flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h2 class="text-3xl font-bold text-slate-800">{{ $stats['total'] }}</h2>
            <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Total Hari Kerja</p>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-center items-center text-center">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-3xl font-bold text-emerald-600">{{ $stats['hadir'] }}</h2>
            <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Hadir Tepat Waktu</p>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-center items-center text-center">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-3xl font-bold text-amber-600">{{ $stats['telat'] }}</h2>
            <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Keterlambatan</p>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-center items-center text-center">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h2 class="text-3xl font-bold text-blue-600">{{ $stats['sakit'] }}</h2>
            <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Ijin / Sakit</p>
        </div>
    </div>

    <!-- Attendance History List -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Catatan Aktivitas</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Jam Masuk</th>
                        <th class="px-6 py-4">Jam Pulang</th>
                        <th class="px-6 py-4 text-center">Durasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($attendances as $attendance)
                    <tr class="hover:bg-fuchsia-50/50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-700">
                            {{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('l, d F Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($attendance->status == 'Hadir')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Hadir</span>
                            @elseif($attendance->status == 'Telat')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Telat</span>
                            @elseif($attendance->status == 'Sakit')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">Sakit</span>
                            @elseif($attendance->status == 'Ijin')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700">Ijin</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">Alpha</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono text-slate-600">{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '-' }}</td>
                        <td class="px-6 py-4 font-mono text-slate-600">{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-' }}</td>
                        <td class="px-6 py-4 text-center font-mono text-fuchsia-600 font-bold">
                            @if($attendance->clock_in && $attendance->clock_out)
                                {{ \Carbon\Carbon::parse($attendance->clock_in)->diff(\Carbon\Carbon::parse($attendance->clock_out))->format('%H jam %I menit') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400 italic">Belum ada catatan absensi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6">
            {{ $attendances->links() }}
        </div>
    </div>
</div>
@endsection
