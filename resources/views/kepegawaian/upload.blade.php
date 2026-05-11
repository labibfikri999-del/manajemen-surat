@extends('kepegawaian.layouts.app')

@section('title', 'Upload Dokumen')
@section('page-title', 'Upload Dokumen Pegawai')
@section('eyebrow', 'Portal Pegawai')

@section('content')
@php
    $initialChecklist = collect(array_keys($checklistItems))
        ->mapWithKeys(fn ($key) => [$key => (bool) old("checklist.$key")])
        ->all();
@endphp
<div x-data="{ fileName: 'Pilih atau tarik file ke sini', checks: @js($initialChecklist), get canSubmit() { return Object.values(this.checks).every(Boolean); } }" class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-lg font-bold text-slate-950">Form Pengajuan</h2>
        <p class="mt-1 text-sm text-slate-500">Dokumen yang dikirim akan masuk ke antrean verifikasi staff.</p>

        <form id="upload-form" action="{{ route('kepegawaian.upload.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">Kategori Dokumen</label>
                <select name="kategori" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-brand-600 focus:ring-2 focus:ring-cyan-100">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">Judul Dokumen</label>
                <input type="text" name="judul" value="{{ old('judul') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-brand-600 focus:ring-2 focus:ring-cyan-100" placeholder="Contoh: Ijazah S1 Tahun 2020">
            </div>
            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">Catatan Pegawai</label>
                <textarea name="catatan" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-brand-600 focus:ring-2 focus:ring-cyan-100" placeholder="Tambahkan keterangan singkat jika diperlukan">{{ old('catatan') }}</textarea>
            </div>
            <div class="rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 p-6 text-center">
                <svg class="mx-auto h-10 w-10 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 4v12m0-12l-4 4m4-4l4 4"/></svg>
                <p class="mt-3 text-sm font-bold text-slate-800" x-text="fileName"></p>
                <p class="mt-1 text-xs text-slate-500">PDF, DOC, DOCX, JPG, PNG sampai 10 MB</p>
                <input x-ref="documentFile" name="file" type="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden" @change="fileName = $event.target.files?.[0]?.name || 'Pilih atau tarik file ke sini'">
                <button type="button" @click="$refs.documentFile.click()" class="mt-4 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50">Pilih File</button>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" name="action" value="submit" :disabled="!canSubmit" class="rounded-lg bg-brand-600 px-5 py-3 text-sm font-bold text-white hover:bg-brand-700 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:text-slate-500">Kirim Dokumen</button>
                <button type="submit" name="action" value="draft" class="rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">Simpan Draft</button>
            </div>
        </form>
    </div>

    <div class="space-y-6">
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-bold text-slate-950">Checklist Sebelum Kirim</h2>
            <div class="mt-5 grid gap-3 sm:grid-cols-2" id="upload-checklist">
                @foreach($checklistItems as $key => $item)
                    <label class="checklist-card flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 p-3 transition hover:border-brand-600 hover:bg-cyan-50">
                        <input form="upload-form" type="checkbox" name="checklist[{{ $key }}]" value="1" x-model="checks['{{ $key }}']" class="peer sr-only">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-400 peer-checked:bg-emerald-100 peer-checked:text-emerald-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-sm font-semibold text-slate-700">{{ $item }}</span>
                    </label>
                @endforeach
            </div>
            <p x-show="!canSubmit" class="mt-3 text-xs font-semibold text-amber-700">Centang semua checklist sebelum mengirim dokumen.</p>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="font-bold text-slate-950">Riwayat Saya</h2>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($documents as $item)
                    <div class="flex items-center justify-between gap-4 px-5 py-4">
                        <div>
                            <p class="text-sm font-semibold text-slate-700">{{ $item['kategori'] }} - {{ $item['status_label'] }}</p>
                            <p class="text-xs text-slate-500">{{ $item['tanggal'] }}</p>
                        </div>
                        @if($item['file_path'])
                            <a href="{{ route('kepegawaian.dokumen.download', $item['id']) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-50">Download</a>
                        @else
                            <button type="button" data-modal-target="detail-dokumen-modal" data-modal-toggle="detail-dokumen-modal" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-50">Detail</button>
                        @endif
                    </div>
                @empty
                    <div class="px-5 py-6 text-sm text-slate-500">Belum ada riwayat dokumen.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div id="detail-dokumen-modal" tabindex="-1" aria-hidden="true" class="fixed inset-x-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full items-center justify-center overflow-y-auto overflow-x-hidden p-4 md:inset-0">
        <div class="relative max-h-full w-full max-w-md">
            <div class="relative rounded-lg bg-white shadow">
                <div class="flex items-center justify-between border-b border-slate-100 p-4">
                    <h3 class="text-base font-bold text-slate-950">Detail Dokumen</h3>
                    <button type="button" data-modal-hide="detail-dokumen-modal" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-900">
                        <span class="sr-only">Tutup</span>
                        <svg class="h-4 w-4" aria-hidden="true" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
                <div class="p-4 text-sm text-slate-600">
                    Dokumen belum memiliki file unduhan. Silakan cek status terbaru atau unggah ulang bila diminta revisi.
                </div>
                <div class="flex justify-end border-t border-slate-100 p-4">
                    <button type="button" data-modal-hide="detail-dokumen-modal" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-bold text-white hover:bg-brand-700">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
