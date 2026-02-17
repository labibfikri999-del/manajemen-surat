@extends('sdm.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="flex items-center justify-between no-print">
        <h1 class="text-2xl font-bold text-slate-800">Laporan Rekapitulasi Jabatan</h1>
        <div class="flex gap-3">
             <a href="{{ route('sdm.laporan.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                Kembali
            </a>
            <button onclick="window.print()" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Laporan
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-6 printable">
        <div class="text-center mb-8">
            <h2 class="text-xl font-bold text-slate-800 uppercase tracking-wide">Rekapitulasi Jabatan Karyawan</h2>
            <p class="text-slate-500 text-sm mt-1">Per Tanggal: {{ date('d F Y') }}</p>
        </div>

        <div class="space-y-8">
            @forelse($jabatans as $namaJabatan => $listPegawai)
            <div>
                <h3 class="font-bold text-lg text-indigo-700 border-b border-indigo-100 pb-2 mb-3 flex justify-between items-center">
                    {{ $namaJabatan }}
                    <span class="text-sm font-normal text-slate-500 bg-indigo-50 px-2 py-1 rounded">{{ $listPegawai->count() }} Karyawan</span>
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-xs uppercase tracking-wider text-slate-600 font-bold">
                                <th class="px-4 py-2 w-12 text-center">No</th>
                                <th class="px-4 py-2">NIK</th>
                                <th class="px-4 py-2">Nama Karyawan</th>
                                <th class="px-4 py-2">Jenis Jabatan</th>
                                <th class="px-4 py-2">TMT Jabatan</th>
                                <th class="px-4 py-2">SK Nomor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach($listPegawai as $idx => $history)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-4 py-2 text-center text-slate-500">{{ $idx + 1 }}</td>
                                <td class="px-4 py-2 text-slate-600 font-mono text-xs">{{ $history->pegawai->nip }}</td>
                                <td class="px-4 py-2 font-medium text-slate-800">{{ $history->pegawai->name }}</td>
                                <td class="px-4 py-2 text-slate-700">{{ ucfirst($history->jenis_jabatan) }}</td>
                                <td class="px-4 py-2 text-slate-700">{{ \Carbon\Carbon::parse($history->tmt)->format('d F Y') }}</td>
                                <td class="px-4 py-2 text-slate-600 text-xs">{{ $history->sk_nomor ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @empty
            <div class="text-center py-10 text-slate-500 italic">
                Belum ada data riwayat jabatan aktif.
            </div>
            @endforelse
        </div>
        
        <div class="mt-8 text-xs text-slate-400 text-right print-footer">
            Dicetak oleh: {{ auth()->user()->name }} | {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        .printable { box-shadow: none; border: none; padding: 0; }
        body { background: white; }
    }
</style>
@endsection
