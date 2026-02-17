@extends('sdm.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="flex items-center justify-between no-print">
        <h1 class="text-2xl font-bold text-slate-800">Laporan Rekapitulasi Golongan & Pangkat</h1>
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
            <h2 class="text-xl font-bold text-slate-800 uppercase tracking-wide">Rekapitulasi Golongan Karyawan</h2>
            <p class="text-slate-500 text-sm mt-1">Per Tanggal: {{ date('d F Y') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($golongans as $golongan => $listPegawai)
            <div class="border border-slate-200 rounded-lg overflow-hidden break-inside-avoid">
                <div class="bg-emerald-50 px-4 py-3 border-b border-emerald-100 flex justify-between items-center">
                    <h3 class="font-bold text-emerald-800">Golongan {{ $golongan }}</h3>
                    <span class="text-xs font-bold bg-white text-emerald-600 px-2 py-1 rounded border border-emerald-200">{{ $listPegawai->count() }} Orang</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs text-slate-500 font-bold uppercase">
                            <tr>
                                <th class="px-4 py-2 w-10 text-center">No</th>
                                <th class="px-4 py-2">Nama</th>
                                <th class="px-4 py-2 text-right">TMT</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($listPegawai as $idx => $pangkat)
                            <tr>
                                <td class="px-4 py-2 text-center text-slate-400">{{ $idx + 1 }}</td>
                                <td class="px-4 py-2">
                                    <div class="font-medium text-slate-800">{{ $pangkat->pegawai->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $pangkat->pegawai->nip }}</div>
                                </td>
                                <td class="px-4 py-2 text-right text-slate-600">
                                    {{ \Carbon\Carbon::parse($pangkat->tmt)->format('dM y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-10 text-slate-500 italic">
                Belum ada data golongan aktif.
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
