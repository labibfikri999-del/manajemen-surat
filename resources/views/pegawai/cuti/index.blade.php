@extends('pegawai.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Pengajuan Cuti</h1>
            <p class="text-slate-500">Kelola dan ajukan permohonan cuti Anda.</p>
        </div>
        <a href="{{ route('pegawai.cuti.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-pink-200 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Ajukan Cuti Baru
        </a>
    </div>

    <!-- Active Applications List -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Riwayat Pengajuan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Tipe Cuti</th>
                        <th class="px-6 py-4">Tanggal Mulai</th>
                        <th class="px-6 py-4">Tanggal Selesai</th>
                        <th class="px-6 py-4">Alasan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Diajukan Pada</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($leaves as $leave)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-700">{{ $leave->type }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ \Carbon\Carbon::parse($leave->start_date)->translatedFormat('d F Y') }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ \Carbon\Carbon::parse($leave->end_date)->translatedFormat('d F Y') }}</td>
                        <td class="px-6 py-4 text-slate-500 italic truncate max-w-xs">{{ $leave->reason }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($leave->status == 'Approved')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Disetujui</span>
                            @elseif($leave->status == 'Rejected')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">Ditolak</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Menunggu</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-400 text-sm">{{ $leave->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <p class="text-slate-500 font-medium">Belum ada pengajuan cuti.</p>
                            <p class="text-slate-400 text-sm mt-1">Klik tombol di atas untuk mengajukan cuti baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6">
            {{ $leaves->links() }}
        </div>
    </div>
</div>
@endsection
