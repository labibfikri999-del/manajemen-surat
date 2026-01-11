@extends('aset.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    <div class="flex justify-between items-end gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Mutasi & Peminjaman</h1>
            <p class="text-slate-500">Log perpindahan dan peminjaman aset kantor.</p>
        </div>
        <a href="{{ route('aset.mutation.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-emerald-200 transition-all">
            + Catat Mutasi Baru
        </a>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-bold uppercase">Total Mutasi</p>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['mutasi'] }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
             <div>
                <p class="text-xs text-slate-400 font-bold uppercase">Peminjaman Aktif</p>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['peminjaman'] }}</p>
            </div>
        </div>
         <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
             <div>
                <p class="text-xs text-slate-400 font-bold uppercase">Aktivitas 7 Hari Terakhir</p>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['recent'] }}</p>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="p-6">Tanggal</th>
                    <th class="p-6">Aset</th>
                    <th class="p-6">Jenis</th>
                    <th class="p-6">Dari &rarr; Ke</th>
                    <th class="p-6">Penanggung Jawab</th>
                    <th class="p-6">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-sm text-slate-600">
                @forelse($mutations as $m)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-6 font-medium">{{ $m->date->translatedFormat('d M Y') }}</td>
                    <td class="p-6">
                        <div class="font-bold text-slate-800">{{ $m->aset->name }}</div>
                        <div class="text-xs text-slate-400">{{ $m->aset->code }}</div>
                    </td>
                    <td class="p-6">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $m->type == 'Mutasi' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $m->type }}
                        </span>
                    </td>
                    <td class="p-6">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-400">{{ $m->origin_location }}</span>
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            <span class="font-bold text-slate-800">{{ $m->destination_location }}</span>
                        </div>
                    </td>
                    <td class="p-6">{{ $m->person_in_charge }}</td>
                    <td class="p-6 text-slate-400 italic max-w-xs truncate">{{ $m->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-12 text-center text-slate-400">
                        Belum ada data mutasi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-6 border-t border-slate-50">
            {{ $mutations->links() }}
        </div>
    </div>
</div>
@endsection
