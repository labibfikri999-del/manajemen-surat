@extends('keuangan.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Klaim Asuransi</h1>
            <p class="text-slate-500">Kelola status pengajuan klaim asuransi pasien.</p>
        </div>
        <a href="{{ route('keuangan.klaim.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-200 transition-all active:scale-95 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Buat Klaim Baru
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500">
                        <th class="p-6 font-bold">Provider</th>
                        <th class="p-6 font-bold">Tanggal Pengajuan</th>
                        <th class="p-6 font-bold text-right">Nilai Klaim</th>
                        <th class="p-6 font-bold text-center">Status</th>
                        <th class="p-6 font-bold text-center">Bukti</th>
                        <th class="p-6 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($claims as $claim)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="p-6 text-slate-800 font-bold text-lg">
                            {{ $claim->provider }}
                        </td>
                        <td class="p-6 text-slate-500 font-medium">
                            {{ \Carbon\Carbon::parse($claim->submitted_at)->format('d M Y') }}
                            <span class="text-xs text-slate-400 block">({{ \Carbon\Carbon::parse($claim->submitted_at)->diffInDays(now()) }} hari yang lalu)</span>
                        </td>
                        <td class="p-6 text-right font-bold text-slate-700">
                            Rp {{ number_format($claim->amount, 0, ',', '.') }}
                        </td>
                        <td class="p-6 text-center">
                            @php
                                $statusClass = match($claim->status) {
                                    'Paid' => 'bg-emerald-100 text-emerald-700',
                                    'Submitted' => 'bg-blue-100 text-blue-700',
                                    'Verifikasi' => 'bg-amber-100 text-amber-700',
                                    default => 'bg-slate-100 text-slate-600'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusClass }}">
                                {{ $claim->status }}
                            </span>
                        </td>
                        <td class="p-6 text-center">
                            @if($claim->attachment)
                                <a href="{{ asset('storage/' . $claim->attachment) }}" target="_blank" class="text-blue-600 hover:text-blue-700 font-bold text-xs underline">
                                    Lihat File
                                </a>
                            @else
                                <span class="text-slate-300 text-xs">-</span>
                            @endif
                        </td>
                        <td class="p-6 text-center">
                            <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('keuangan.klaim.edit', $claim->id) }}" class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-amber-50 hover:text-amber-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('keuangan.klaim.destroy', $claim->id) }}" method="POST" onsubmit="return confirm('Hapus klaim ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-red-50 hover:text-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-slate-400">Belum ada data klaim.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-slate-50">
            {{ $claims->links() }}
        </div>
    </div>
</div>
@endsection
