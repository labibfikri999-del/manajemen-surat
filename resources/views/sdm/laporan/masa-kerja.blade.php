@extends('sdm.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="flex items-center justify-between no-print">
        <h1 class="text-2xl font-bold text-slate-800">Laporan Masa Kerja</h1>
        <div class="flex gap-3">
             <a href="{{ route('sdm.laporan.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                Kembali
            </a>
            <button onclick="window.print()" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2-2v4h10z"></path></svg>
                Cetak Laporan
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-6 printable">
        <div class="text-center mb-8">
            <h2 class="text-xl font-bold text-slate-800 uppercase tracking-wide">Laporan Masa Kerja Karyawan</h2>
            <p class="text-slate-500 text-sm mt-1">Diurutkan dari masa kerja terlama</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b-2 border-slate-200 text-xs uppercase tracking-wider text-slate-600 font-bold">
                        <th class="px-4 py-3 text-center w-12">No</th>
                        <th class="px-4 py-3">Nama Karyawan</th>
                        <th class="px-4 py-3">Tanggal Bergabung</th>
                        <th class="px-4 py-3 text-center">Total Masa Kerja</th>
                        <th class="px-4 py-3">Jabatan Saat Ini</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($pegawais as $index => $pegawai)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-4 py-3 text-center font-medium text-slate-500">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <div class="font-bold text-slate-800">{{ $pegawai->name }}</div>
                            <div class="text-xs text-slate-500 font-mono">{{ $pegawai->nip }}</div>
                        </td>
                        <td class="px-4 py-3 text-slate-700">
                            {{ \Carbon\Carbon::parse($pegawai->join_date)->format('d F Y') }}
                        </td>
                         <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $pegawai->masa_kerja_tahun }} Thn {{ $pegawai->masa_kerja_bulan }} Bln
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $pegawai->position ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500 italic">Tidak ada data karyawan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
