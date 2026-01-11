@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Jadwal Shift</h1>
            <p class="text-slate-500">Kelola jadwal shift pegawai.</p>
        </div>
        <a href="{{ route('sdm.jadwal.create') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-cyan-200 transition-all active:scale-95 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Jadwal
        </a>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <form action="{{ route('sdm.jadwal.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <div>
                <input type="date" name="date" value="{{ request('date') }}" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500">
            </div>

            <div class="md:col-span-2">
                <select name="pegawai_id" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500">
                    <option value="">-- Semua Pegawai --</option>
                    @foreach($pegawais as $pegawai)
                        <option value="{{ $pegawai->id }}" {{ request('pegawai_id') == $pegawai->id ? 'selected' : '' }}>{{ $pegawai->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold transition-all">
                Filter
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-bold text-slate-700">Tanggal</th>
                        <th class="px-6 py-4 font-bold text-slate-700">Pegawai</th>
                        <th class="px-6 py-4 font-bold text-slate-700">Shift</th>
                        <th class="px-6 py-4 font-bold text-slate-700">Waktu</th>
                        <th class="px-6 py-4 font-bold text-slate-700">Status</th>
                        <th class="px-6 py-4 font-bold text-slate-700 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($shifts as $shift)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($shift->date)->format('d M Y') }}</span>
                            <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($shift->date)->format('l') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-600 font-bold text-sm">
                                    {{ substr($shift->pegawai->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800 text-sm">{{ $shift->pegawai->name ?? 'Pegawai Terhapus' }}</h3>
                                    <p class="text-xs text-slate-500">{{ $shift->pegawai->role ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-medium border border-blue-100">
                                {{ $shift->shift_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
                        </td>
                        <td class="px-6 py-4">
                             @php
                                $statusColors = [
                                    'Scheduled' => 'bg-slate-100 text-slate-600',
                                    'On Duty' => 'bg-cyan-100 text-cyan-600',
                                    'Completed' => 'bg-emerald-100 text-emerald-600',
                                    'Absent' => 'bg-red-100 text-red-600',
                                ];
                                $colorClass = $statusColors[$shift->status] ?? 'bg-slate-100 text-slate-600';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                {{ $shift->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('sdm.jadwal.edit', $shift->id) }}" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-cyan-600 hover:border-cyan-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('sdm.jadwal.destroy', $shift->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal shift ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-red-600 hover:border-red-200 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p>Belum ada jadwal shift yang ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($shifts->hasPages())
        <div class="p-6 border-t border-slate-100">
            {{ $shifts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
