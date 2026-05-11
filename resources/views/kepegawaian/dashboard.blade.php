@extends('kepegawaian.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Kepegawaian')
@section('eyebrow', 'Ringkasan Operasional')

@section('content')
@php
    $role = auth()->user()->role ?? 'pegawai';
    $listRoute = in_array($role, ['staff', 'staff_kepegawaian'])
        ? route('kepegawaian.verifikasi')
        : (in_array($role, ['sekjen', 'direktur']) ? route('kepegawaian.persetujuan') : route('kepegawaian.upload'));
@endphp
<div class="space-y-6">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Total Pegawai</p>
            <p class="mt-3 text-3xl font-bold text-slate-950">{{ $stats['pegawai'] }}</p>
            <p class="mt-2 text-xs font-semibold text-brand-700">Akun aktif kepegawaian</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Dokumen Diajukan</p>
            <p class="mt-3 text-3xl font-bold text-slate-950">{{ $stats['diajukan'] }}</p>
            <p class="mt-2 text-xs font-semibold text-slate-500">Bulan berjalan</p>
        </div>
        <div class="rounded-lg border border-amber-200 bg-amber-50 p-5 shadow-sm">
            <p class="text-sm font-semibold text-amber-700">Perlu Revisi</p>
            <p class="mt-3 text-3xl font-bold text-amber-900">{{ $stats['revisi'] }}</p>
            <p class="mt-2 text-xs font-semibold text-amber-700">Menunggu perbaikan pegawai</p>
        </div>
        <div class="rounded-lg border border-cyan-200 bg-cyan-50 p-5 shadow-sm">
            <p class="text-sm font-semibold text-cyan-700">Menunggu Sekjen</p>
            <p class="mt-3 text-3xl font-bold text-cyan-950">{{ $stats['menunggu_sekjen'] }}</p>
            <p class="mt-2 text-xs font-semibold text-cyan-700">Sudah diverifikasi staff</p>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.4fr_0.6fr]">
        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4">
                <div>
                    <h2 class="font-bold text-slate-950">Dokumen Terbaru</h2>
                    <p class="text-sm text-slate-500">Pengajuan pegawai yang sedang berjalan.</p>
                </div>
                <a href="{{ $listRoute }}" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-bold text-slate-600 hover:bg-slate-50">Lihat semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Pegawai</th>
                            <th class="px-5 py-3">Unit</th>
                            <th class="px-5 py-3">Kategori</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($documents as $row)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <p class="font-bold text-slate-900">{{ $row['pegawai'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $row['nip'] }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $row['unit'] }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $row['kategori'] }}</td>
                                <td class="px-5 py-4">
                                    <span class="rounded-lg px-2.5 py-1 text-xs font-bold {{ $row['status'] === 'perlu_revisi' ? 'bg-amber-100 text-amber-800' : ($row['status'] === 'disetujui' ? 'bg-emerald-100 text-emerald-800' : 'bg-cyan-100 text-cyan-800') }}">{{ $row['status_label'] }}</span>
                                </td>
                                <td class="px-5 py-4 text-slate-500">{{ $row['tanggal'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-sm font-semibold text-slate-500">Belum ada dokumen kepegawaian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-4">
            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="font-bold text-slate-950">Alur Status</h2>
                <div class="mt-5 space-y-4">
                    @foreach(['Diajukan Pegawai', 'Diperiksa Staff', 'Revisi Jika Perlu', 'Persetujuan Sekjen', 'Diarsipkan'] as $step)
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center text-sm font-bold">{{ $loop->iteration }}</div>
                            <p class="text-sm font-semibold text-slate-700">{{ $step }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-900 p-5 text-white shadow-sm">
                <h2 class="font-bold">Kontrol Akun</h2>
                <p class="mt-2 text-sm text-slate-300">Akun pegawai dapat dibuat manual, import Excel, atau generate dari NIP dengan password awal sementara.</p>
                @if(in_array($role, ['staff', 'staff_kepegawaian']))
                    <a href="{{ route('kepegawaian.akun') }}" class="mt-4 inline-flex rounded-lg bg-white px-4 py-2 text-sm font-bold text-slate-900">Kelola Akun</a>
                @else
                    <a href="{{ $listRoute }}" class="mt-4 inline-flex rounded-lg bg-white px-4 py-2 text-sm font-bold text-slate-900">Lihat Dokumen</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
