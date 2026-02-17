@extends('sdm.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="flex items-center justify-between no-print">
        <h1 class="text-2xl font-bold text-slate-800">Laporan Data Pendidikan</h1>
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
            <h2 class="text-xl font-bold text-slate-800 uppercase tracking-wide">Laporan Riwayat Pendidikan Formal</h2>
            <p class="text-slate-500 text-sm mt-1">Per Tanggal: {{ date('d F Y') }}</p>
        </div>

        <div class="space-y-8">
            @forelse($pendidikans as $jenjang => $listPendidikan)
            <div>
                <h3 class="font-bold text-lg text-pink-700 border-b border-pink-100 pb-2 mb-3 flex items-center gap-2">
                    <span class="px-2 py-1 bg-pink-50 rounded text-sm">{{ $jenjang }}</span>
                    <span class="text-sm font-normal text-slate-500">({{ $listPendidikan->count() }} Data)</span>
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-xs uppercase tracking-wider text-slate-600 font-bold">
                                <th class="px-4 py-2 w-10 text-center">No</th>
                                <th class="px-4 py-2">Nama Karyawan</th>
                                <th class="px-4 py-2">Institusi / Universitas</th>
                                <th class="px-4 py-2">Jurusan / Prodi</th>
                                <th class="px-4 py-2 text-center">Tahun Lulus</th>
                                <th class="px-4 py-2 text-center">IPK / Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach($listPendidikan as $idx => $pendidikan)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-4 py-2 text-center text-slate-400">{{ $idx + 1 }}</td>
                                <td class="px-4 py-2 font-medium text-slate-800">{{ $pendidikan->pegawai->name }}</td>
                                <td class="px-4 py-2 text-slate-700">{{ $pendidikan->institusi }}</td>
                                <td class="px-4 py-2 text-slate-700">{{ $pendidikan->jurusan }}</td>
                                <td class="px-4 py-2 text-center text-slate-700">{{ $pendidikan->tahun_lulus }}</td>
                                <td class="px-4 py-2 text-center text-slate-600">{{ $pendidikan->ipk ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @empty
            <div class="text-center py-10 text-slate-500 italic">
                Belum ada data pendidikan.
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
