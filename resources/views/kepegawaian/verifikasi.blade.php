@extends('kepegawaian.layouts.app')

@section('title', 'Verifikasi Staff')
@section('page-title', 'Verifikasi Dokumen Staff')
@section('eyebrow', 'Staff Kepegawaian')

@section('content')
<div x-data="{ showFilter: false, keyword: '', currentStatus: '', matches(search, status) { return search.includes(this.keyword.toLowerCase()) && (!this.currentStatus || status === this.currentStatus); } }" class="grid gap-6 xl:grid-cols-[1.25fr_0.75fr]">
    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-slate-950">Antrean Verifikasi</h2>
                <p class="text-sm text-slate-500">Dokumen pegawai yang perlu dicek kelengkapannya.</p>
            </div>
            <div class="flex gap-2">
                <button type="button" @click="showFilter = !showFilter" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-bold text-white">Filter</button>
                <a href="{{ route('kepegawaian.verifikasi.export') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-bold text-slate-600">Export</a>
            </div>
        </div>
        <form x-show="showFilter" class="border-b border-slate-100 bg-slate-50 px-5 py-4">
            <div class="grid gap-3 sm:grid-cols-3">
                <input x-model="keyword" type="search" class="rounded-lg border border-slate-300 px-4 py-2 text-sm outline-none focus:border-brand-600 focus:ring-2 focus:ring-cyan-100" placeholder="Cari pegawai/unit">
                <select x-model="currentStatus" class="rounded-lg border border-slate-300 px-4 py-2 text-sm outline-none focus:border-brand-600 focus:ring-2 focus:ring-cyan-100">
                    <option value="">Semua status</option>
                    <option value="diajukan">Diajukan</option>
                    <option value="perlu_revisi">Perlu Revisi</option>
                    <option value="menunggu_sekjen">Menunggu Sekjen</option>
                    <option value="diperiksa_staff">Diperiksa Staff</option>
                </select>
                <button type="button" @click="keyword = ''; currentStatus = ''" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-700">Reset Filter</button>
            </div>
        </form>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Pegawai</th>
                        <th class="px-5 py-3">Dokumen</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($documents as $row)
                        <tr x-show="matches(@js(strtolower($row['pegawai'].' '.$row['unit'].' '.$row['nip'].' '.$row['kategori'])), @js($row['status']))" class="hover:bg-slate-50">
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-900">{{ $row['pegawai'] }}</p>
                                <p class="text-xs text-slate-500">{{ $row['unit'] }} | {{ $row['nip'] }}</p>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $row['kategori'] }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-lg bg-cyan-100 px-2.5 py-1 text-xs font-bold text-cyan-800">{{ $row['status_label'] }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @if($row['file_path'])
                                        <a href="{{ route('kepegawaian.dokumen.preview', $row['id']) }}" target="_blank" rel="noopener" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-700">Preview</a>
                                        <a href="{{ route('kepegawaian.dokumen.download', $row['id']) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-700">Download</a>
                                    @else
                                        <button type="button" disabled class="cursor-not-allowed rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-400">Preview</button>
                                    @endif
                                    <form action="{{ route('kepegawaian.verifikasi.action', $row['id']) }}" method="POST">
                                        @csrf
                                        <button name="action" value="forward" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white">Teruskan</button>
                                    </form>
                                    <form action="{{ route('kepegawaian.verifikasi.action', $row['id']) }}" method="POST">
                                        @csrf
                                        <button name="action" value="revise" class="rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-bold text-white">Revisi</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-sm font-semibold text-slate-500">Tidak ada antrean verifikasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-lg font-bold text-slate-950">Panel Pemeriksaan</h2>
        @if(count($documents) > 0)
            <form action="{{ route('kepegawaian.verifikasi.panel') }}" method="POST" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Dokumen</label>
                    <select name="document_id" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-brand-600 focus:ring-2 focus:ring-cyan-100">
                        @foreach($documents as $document)
                            <option value="{{ $document['id'] }}">{{ $document['pegawai'] }} - {{ $document['kategori'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Hasil Pemeriksaan</label>
                    <select name="keputusan" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-brand-600 focus:ring-2 focus:ring-cyan-100">
                        <option>Dokumen lengkap, teruskan ke Sekjen</option>
                        <option>Perlu revisi pegawai</option>
                        <option>Tolak karena tidak sesuai</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Catatan Staff</label>
                    <textarea name="catatan" rows="6" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-brand-600 focus:ring-2 focus:ring-cyan-100" placeholder="Contoh: Mohon unggah ulang file dengan halaman legalisir yang lebih jelas."></textarea>
                </div>
                <button class="w-full rounded-lg bg-brand-600 px-4 py-3 text-sm font-bold text-white hover:bg-brand-700">Simpan Keputusan</button>
            </form>
        @else
            <div class="mt-5 rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm font-semibold text-slate-500">Belum ada dokumen yang perlu diperiksa.</div>
        @endif
    </div>
</div>
@endsection
