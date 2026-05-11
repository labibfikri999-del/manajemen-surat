@extends('kepegawaian.layouts.app')

@section('title', 'Persetujuan Sekjen')
@section('page-title', 'Persetujuan Sekjen')
@section('eyebrow', 'Validasi Akhir')

@section('content')
<div class="grid gap-6 xl:grid-cols-[0.7fr_1.3fr]">
    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-lg font-bold text-slate-950">Ringkasan Keputusan</h2>
        <div class="mt-5 space-y-3">
            <div class="rounded-lg border border-cyan-200 bg-cyan-50 p-4">
                <p class="text-sm font-semibold text-cyan-700">Menunggu keputusan</p>
                <p class="mt-2 text-3xl font-bold text-cyan-950">{{ $stats['menunggu'] }}</p>
            </div>
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4">
                <p class="text-sm font-semibold text-emerald-700">Disetujui bulan ini</p>
                <p class="mt-2 text-3xl font-bold text-emerald-950">{{ $stats['disetujui_bulan_ini'] }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                <p class="text-sm font-semibold text-slate-600">Ditolak bulan ini</p>
                <p class="mt-2 text-3xl font-bold text-slate-950">{{ $stats['ditolak_bulan_ini'] }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="font-bold text-slate-950">Dokumen Siap Diputuskan</h2>
            <p class="text-sm text-slate-500">Berkas yang sudah lolos verifikasi staff.</p>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($documents as $row)
                <div class="p-5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-base font-bold text-slate-950">{{ $row['kategori'] }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $row['pegawai'] }} | {{ $row['unit'] }} | {{ $row['tanggal'] }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if($row['file_path'])
                                <a href="{{ route('kepegawaian.dokumen.preview', $row['id']) }}" target="_blank" rel="noopener" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-bold text-slate-700">Preview</a>
                                <a href="{{ route('kepegawaian.dokumen.download', $row['id']) }}" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-bold text-slate-700">Download</a>
                            @else
                                <button type="button" disabled class="cursor-not-allowed rounded-lg border border-slate-200 px-3 py-2 text-sm font-bold text-slate-400">Preview</button>
                            @endif
                            <form action="{{ route('kepegawaian.persetujuan.action', $row['id']) }}" method="POST">
                                @csrf
                                <button name="action" value="approve" class="rounded-lg bg-emerald-600 px-3 py-2 text-sm font-bold text-white">Setujui</button>
                            </form>
                            <form action="{{ route('kepegawaian.persetujuan.action', $row['id']) }}" method="POST">
                                @csrf
                                <button name="action" value="reject" class="rounded-lg bg-red-600 px-3 py-2 text-sm font-bold text-white">Tolak</button>
                            </form>
                        </div>
                    </div>
                    <div class="mt-4 rounded-lg bg-slate-50 p-4 text-sm text-slate-600">
                        Catatan staff: {{ $row['catatan_staff'] ?: 'dokumen sudah dicek, identitas dan file pendukung sesuai dengan kategori pengajuan.' }}
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-sm font-semibold text-slate-500">Belum ada dokumen yang menunggu persetujuan.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
