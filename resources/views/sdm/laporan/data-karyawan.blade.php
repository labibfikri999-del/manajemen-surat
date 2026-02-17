@extends('sdm.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="flex items-center justify-between no-print">
        <h1 class="text-2xl font-bold text-slate-800">Laporan Data Karyawan</h1>
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
            <h2 class="text-xl font-bold text-slate-800 uppercase tracking-wide">Laporan Data Karyawan</h2>
            <p class="text-slate-500 text-sm mt-1">Per Tanggal: {{ date('d F Y') }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b-2 border-slate-200 text-xs uppercase tracking-wider text-slate-600 font-bold">
                        <th class="px-4 py-3 text-center w-12">No</th>
                        <th class="px-4 py-3">NIK / Nama</th>
                        <th class="px-4 py-3">Jabatan</th>
                        <th class="px-4 py-3">Kontak</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3">Tanggal Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($pegawais as $index => $pegawai)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-4 py-3 text-center font-medium text-slate-500">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="font-bold text-slate-800">{{ $pegawai->name }}</div>
                            <div class="text-xs text-slate-500 font-mono">{{ $pegawai->nip }}</div>
                        </td>
                        <td class="px-4 py-3 text-slate-700">{{ $pegawai->position ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">
                            <div>{{ $pegawai->email }}</div>
                            <div class="text-xs">{{ $pegawai->phone }}</div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex px-2 py-1 rounded text-xs font-bold uppercase {{ $pegawai->status == 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $pegawai->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-700">
                            {{ \Carbon\Carbon::parse($pegawai->join_date)->format('d F Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500 italic">Tidak ada data karyawan.</td>
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
