@extends('aset.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    <div class="flex justify-between items-end gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Maintenance & Perbaikan</h1>
            <p class="text-slate-500">Jadwal servis dan log perbaikan aset.</p>
        </div>
        <a href="{{ route('aset.maintenance.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-emerald-200 transition-all">
            + Jadwal Baru
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-amber-400">
             <p class="text-xs text-slate-400 font-bold uppercase">Terjadwal</p>
             <p class="text-2xl font-bold text-slate-800">{{ $stats['scheduled'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-blue-400">
             <p class="text-xs text-slate-400 font-bold uppercase">Sedang Dikerjakan</p>
             <p class="text-2xl font-bold text-slate-800">{{ $stats['in_progress'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-emerald-400">
             <p class="text-xs text-slate-400 font-bold uppercase">Selesai</p>
             <p class="text-2xl font-bold text-slate-800">{{ $stats['completed'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
             <p class="text-xs text-slate-400 font-bold uppercase">Total Biaya</p>
             <p class="text-xl font-bold text-slate-800 truncate">Rp {{ number_format($stats['total_cost'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="p-6">Jadwal</th>
                    <th class="p-6">Aset</th>
                    <th class="p-6">Deskripsi</th>
                    <th class="p-6">Vendor</th>
                    <th class="p-6">Biaya</th>
                    <th class="p-6">Status</th>
                    <th class="p-6">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-sm text-slate-600">
                @forelse($maintenances as $m)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-6 font-medium">{{ $m->scheduled_date->translatedFormat('d M Y') }}</td>
                    <td class="p-6">
                        <div class="font-bold text-slate-800">{{ $m->aset->name }}</div>
                        <div class="text-xs text-slate-400">{{ $m->aset->code }}</div>
                    </td>
                    <td class="p-6">{{ $m->description }}</td>
                    <td class="p-6">{{ $m->vendor ?? '-' }}</td>
                    <td class="p-6">Rp {{ number_format($m->cost, 0, ',', '.') }}</td>
                    <td class="p-6">
                        @php
                            $colors = [
                                'Scheduled' => 'bg-amber-100 text-amber-700',
                                'In Progress' => 'bg-blue-100 text-blue-700',
                                'Completed' => 'bg-emerald-100 text-emerald-700',
                                'Cancelled' => 'bg-slate-100 text-slate-600',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $colors[$m->status] }}">
                            {{ $m->status }}
                        </span>
                    </td>
                    <td class="p-6">
                         <!-- Simple Status Update Form/Dropdown could go here -->
                        <form action="{{ route('aset.maintenance.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                            @csrf @method('DELETE')
                            <button class="text-rose-500 hover:text-rose-700 font-bold text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-12 text-center text-slate-400">
                        Belum ada jadwal maintenance.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-6 border-t border-slate-50">
            {{ $maintenances->links() }}
        </div>
    </div>
</div>
@endsection
