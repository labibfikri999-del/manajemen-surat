{{-- resources/views/proses-dokumen.blade.php --}}
@php
    $user = auth()->user();
    $role = $user->role ?? 'guest';
    $roleLabels = [
        'direktur' => 'Direktur',
        'staff' => 'Staff Direktur', 
        'instansi' => $user->instansi->nama ?? 'Instansi',
    ];
    
    $statusColors = [
        'disetujui' => 'bg-green-100 text-green-800',
        'diproses' => 'bg-purple-100 text-purple-800',
        'selesai' => 'bg-emerald-100 text-emerald-800',
    ];

    $priorityColors = [
        'AMAT SEGERA' => 'bg-red-100 text-red-800 border-red-200',
        'SEGERA' => 'bg-amber-100 text-amber-800 border-amber-200',
        'BIASA' => 'bg-blue-100 text-blue-800 border-blue-200',
    ];
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Proses Dokumen — YARSI NTB</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Yayasan Bersih.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('partials.styles')
    <style>
        .kategori-option:has(input:checked) {
            border-color: #10b981;
            background-color: #ecfdf5;
        }
        .kategori-option:has(input:checked) span {
            color: #047857;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div id="app" class="flex flex-col">
        @include('partials.header')
        @include('partials.sidebar-menu')

        {{-- Main content --}}
        <main id="main" class="transition-smooth main-with-sidebar flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
            <div class="max-w-7xl mx-auto">
                @include('partials.flash-messages')

                {{-- Page header --}}
                <div class="mb-8 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-emerald-900">Proses Dokumen</h1>
                        <p class="text-emerald-600 mt-2">Kelola dokumen yang sudah divalidasi Direktur</p>
                    </div>
                    <div class="bg-white px-6 py-3 rounded-xl shadow-sm border border-emerald-100 flex items-center gap-4 animate-fade-in">
                        <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide">Total Surat Masuk</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $dokumens->count() }} Dokumen</p>
                        </div>
                    </div>
                </div>

                {{-- Folder Grid (Desktop & Mobile) --}}
                <div id="folderGrid" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 animate-fade-in">
                    <!-- Folder: AMAT SEGERA -->
                    <div onclick="openPriorityFolder('AMAT SEGERA')" class="group relative bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl transition-all cursor-pointer border-2 border-transparent hover:border-red-200">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                            <svg class="w-24 h-24 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
                        </div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-red-600 transition">Amat Segera</h3>
                                <p class="text-sm text-gray-500">Prioritas Tertinggi</p>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <div class="text-3xl font-bold text-gray-900">{{ $dokumens->where('prioritas', 'AMAT SEGERA')->count() }}</div>
                            <span class="text-xs font-medium px-2 py-1 bg-red-50 text-red-700 rounded-lg">Dokumen</span>
                        </div>
                    </div>

                    <!-- Folder: SEGERA -->
                    <div onclick="openPriorityFolder('SEGERA')" class="group relative bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl transition-all cursor-pointer border-2 border-transparent hover:border-amber-200">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                            <svg class="w-24 h-24 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        </div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-amber-600 transition">Segera</h3>
                                <p class="text-sm text-gray-500">Prioritas Menengah</p>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <div class="text-3xl font-bold text-gray-900">{{ $dokumens->where('prioritas', 'SEGERA')->count() }}</div>
                            <span class="text-xs font-medium px-2 py-1 bg-amber-50 text-amber-700 rounded-lg">Dokumen</span>
                        </div>
                    </div>

                    <!-- Folder: BIASA -->
                    <div onclick="openPriorityFolder('BIASA')" class="group relative bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl transition-all cursor-pointer border-2 border-transparent hover:border-blue-200">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                            <svg class="w-24 h-24 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V8h16v10z"/></svg>
                        </div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition">Biasa / Normal</h3>
                                <p class="text-sm text-gray-500">Prioritas Standar</p>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <div class="text-3xl font-bold text-gray-900">{{ $dokumens->where('prioritas', 'BIASA')->count() + $dokumens->where('prioritas', null)->count() }}</div>
                            <span class="text-xs font-medium px-2 py-1 bg-blue-50 text-blue-700 rounded-lg">Dokumen</span>
                        </div>
                    </div>
                </div>

                {{-- Dokumen List (Container) --}}
                <div id="documentList" class="bg-white rounded-xl shadow-lg overflow-hidden hidden animate-slide-up">
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <button onclick="closePriorityFolder()" class="p-2 hover:bg-gray-100 rounded-lg transition text-gray-500 hover:text-gray-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            </button>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900" id="folderTitle">Folder Dokumen</h2>
                                <p class="text-sm text-gray-500" id="folderSubtitle">Menampilkan dokumen dalam folder ini</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($dokumens->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($dokumens as $dok)
                                <div class="p-4 hover:bg-gray-50 transition-colors duration-150 document-item" 
                                     data-priority="{{ $dok->prioritas ?? 'BIASA' }}"
                                     data-status="{{ $dok->status }}">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$dok->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($dok->status) }}
                                                </span>
                                                @if($dok->prioritas)
                                                    <span class="inline-flex px-2 py-1 text-xs font-bold border rounded-full {{ $priorityColors[$dok->prioritas] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ $dok->prioritas }}
                                                    </span>
                                                @endif
                                                <span class="text-xs text-gray-500">Validasi: {{ $dok->tanggal_validasi ? $dok->tanggal_validasi->format('d M Y') : '-' }}</span>
                                            </div>
                                            <h3 class="mt-2 font-semibold text-gray-900">{{ $dok->judul }}</h3>
                                            <p class="text-sm text-gray-600">{{ $dok->deskripsi }}</p>
                                            <div class="mt-2 flex flex-wrap gap-2 text-xs text-gray-500">
                                                <span class="px-2 py-1 bg-gray-100 rounded">{{ $dok->instansi->nama ?? 'N/A' }}</span>
                                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded">Disetujui oleh: {{ $dok->validator->name ?? 'N/A' }}</span>
                                            </div>
                                            @if($dok->catatan_validasi)
                                                <p class="mt-2 text-sm text-emerald-600 italic">"{{ $dok->catatan_validasi }}"</p>
                                            @endif
                                        </div>
                                        
                                        <div class="flex gap-2">
                                            @if($dok->file_path)
                                                <a href="{{ route('dokumen.download', $dok->id) }}"
                                                   class="px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                    Download
                                                </a>
                                            @endif
                                            @if($dok->status === 'disetujui')
                                                <button onclick="showProsesModal({{ $dok->id }}, '{{ $dok->judul }}')" 
                                                        class="px-3 py-2 text-sm bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                    Proses
                                                </button>
                                            @elseif($dok->status === 'diproses')
                                                <button onclick="showSelesaiModal({{ $dok->id }}, '{{ $dok->judul }}')" 
                                                        class="px-3 py-2 text-sm bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    Selesai
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center text-gray-400">
                             Belum ada dokumen.
                        </div>
                    @endif
                </div>

    {{-- Modal Proses --}}
    <div id="prosesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[100] p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Proses Dokumen</h3>
                <p id="prosesDocTitle" class="text-sm text-gray-500"></p>
            </div>
            <form id="prosesForm">
                @csrf
                <input type="hidden" id="prosesDocumenId" name="dokumenId">
                <input type="hidden" id="prosesStatus" name="status" value="diproses">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Proses</label>
                        <textarea name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Tambahkan catatan proses..."></textarea>
                    </div>
                </div>
                <div class="p-6 border-t bg-gray-50 flex gap-3 justify-end rounded-b-xl">
                    <button type="button" onclick="closeProsesModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition" id="submitProsesBtn">Mulai Proses</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Selesai --}}
    <div id="selesaiModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[100] p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Selesaikan & Arsipkan Dokumen</h3>
                <p id="selesaiDocTitle" class="text-sm text-gray-500"></p>
            </div>
            <form id="selesaiForm">
                @csrf
                <input type="hidden" id="selesaiDocumenId" name="dokumenId">
                <input type="hidden" id="selesaiStatus" name="status" value="selesai">
                <div class="p-6 space-y-4">
                    {{-- Kategori Arsip --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-500">*</span> Pilih Kategori Arsip
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="kategori-option flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-emerald-400 transition">
                                <input type="radio" name="kategori_arsip" value="UMUM" class="sr-only" required>
                                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <span class="font-medium text-gray-700">Umum</span>
                            </label>
                            <label class="kategori-option flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-emerald-400 transition">
                                <input type="radio" name="kategori_arsip" value="SDM" class="sr-only" required>
                                <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <span class="font-medium text-gray-700">SDM</span>
                            </label>
                            <label class="kategori-option flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-emerald-400 transition">
                                <input type="radio" name="kategori_arsip" value="ASSET" class="sr-only" required>
                                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <span class="font-medium text-gray-700">Asset</span>
                            </label>
                            <label class="kategori-option flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-emerald-400 transition">
                                <input type="radio" name="kategori_arsip" value="HUKUM" class="sr-only" required>
                                <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                                </div>
                                <span class="font-medium text-gray-700">Hukum</span>
                            </label>
                            <label class="kategori-option flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-emerald-400 transition col-span-2">
                                <input type="radio" name="kategori_arsip" value="KEUANGAN" class="sr-only" required>
                                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span class="font-medium text-gray-700">Keuangan</span>
                            </label>
                        </div>
                    </div>

                    {{-- Upload File Pengganti --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            File Balasan (Opsional)
                        </label>
                        <p class="text-xs text-gray-500 mb-2">Upload file balasan untuk pengirim dokumen (PDF, DOCX, XLS, dll)</p>
                        <label class="flex items-center justify-center px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-emerald-500 transition-colors bg-gray-50">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <span class="text-sm text-gray-600" id="fileReplacementLabel">Pilih File</span>
                            <input type="file" name="file_balasan" id="fileBalasanInput" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                        </label>
                        <p class="text-xs text-gray-500 mt-1" id="fileReplacementName"></p>
                        <p class="text-xs text-gray-500 mt-1" id="fileBalasanName"></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Penyelesaian</label>
                        <textarea name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Catatan penyelesaian dokumen..."></textarea>
                    </div>
                </div>
                <div class="p-6 border-t bg-gray-50 flex gap-3 justify-end rounded-b-xl">
                    <button type="button" onclick="closeSelesaiModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition" id="submitSelesaiBtn">Selesai & Arsipkan</button>
                </div>
            </form>
        </div>
    </div>

    @include('partials.scripts')
    <script>


        // Toast Notification Function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-emerald-500' : 'bg-red-500';
            const icon = type === 'success' ? '✓' : '❌';
            
            toast.innerHTML = `
                <div class="fixed top-20 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 z-[9999] animate-slide-in">
                    <span class="text-xl">${icon}</span>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        function showProsesModal(id, title) {
            document.getElementById('prosesDocTitle').textContent = title;
            document.getElementById('prosesDocumenId').value = id;
            document.getElementById('prosesModal').classList.remove('hidden');
            document.getElementById('prosesModal').classList.add('flex');
        }

        function closeProsesModal() {
            document.getElementById('prosesModal').classList.add('hidden');
            document.getElementById('prosesModal').classList.remove('flex');
            document.getElementById('prosesForm').reset();
        }

        function showSelesaiModal(id, title) {
            document.getElementById('selesaiDocTitle').textContent = title;
            document.getElementById('selesaiDocumenId').value = id;
            document.getElementById('selesaiModal').classList.remove('hidden');
            document.getElementById('selesaiModal').classList.add('flex');
        }

        function closeSelesaiModal() {
            document.getElementById('selesaiModal').classList.add('hidden');
            document.getElementById('selesaiModal').classList.remove('flex');
            document.getElementById('selesaiForm').reset();
        }

        // Proses Form AJAX Submission
        document.getElementById('prosesForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const documenId = document.getElementById('prosesDocumenId').value;
            const button = document.getElementById('submitProsesBtn');
            const catatan = document.querySelector('#prosesForm textarea[name="catatan"]').value;
            
            button.disabled = true;
            button.innerHTML = '<svg class="animate-spin inline-block w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
            
            try {
                const response = await fetch(`/api/dokumen/${documenId}/proses`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        status: 'diproses',
                        catatan: catatan
                    })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    showToast('✓ Dokumen berhasil diproses', 'success');
                    closeProsesModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast('❌ Error: ' + (data.message || 'Gagal memproses dokumen'), 'error');
                    button.disabled = false;
                    button.textContent = 'Mulai Proses';
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('❌ Error: ' + error.message, 'error');
                button.disabled = false;
                button.textContent = 'Mulai Proses';
            }
        });

        // File Balasan Input Handler
        const fileBalasanInput = document.getElementById('fileBalasanInput');
        const fileBalasanLabel = document.getElementById('fileReplacementLabel');
        const fileBalasanName = document.getElementById('fileBalasanName');
        fileBalasanInput.addEventListener('change', function() {
            if (this.files[0]) {
                const file = this.files[0];
                fileBalasanLabel.textContent = '✓ File dipilih';
                fileBalasanName.textContent = '📄 ' + file.name;
            } else {
                fileBalasanLabel.textContent = 'Pilih File';
                fileBalasanName.textContent = '';
            }
        });

        // Selesai Form AJAX Submission
        document.getElementById('selesaiForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const documenId = document.getElementById('selesaiDocumenId').value;
            const button = document.getElementById('submitSelesaiBtn');
            const catatan = document.querySelector('#selesaiForm textarea[name="catatan"]').value;
            const kategoriArsip = document.querySelector('#selesaiForm input[name="kategori_arsip"]:checked');
            const fileBalasan = document.getElementById('fileBalasanInput').files[0];
            
            if (!kategoriArsip) {
                showToast('❌ Pilih kategori arsip terlebih dahulu', 'error');
                return;
            }
            
            button.disabled = true;
            button.innerHTML = '<svg class="animate-spin inline-block w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';
            
            try {
                const formData = new FormData();
                formData.append('status', 'selesai');
                formData.append('catatan', catatan);
                formData.append('kategori_arsip', kategoriArsip.value);
                
                const fileBalasan = document.getElementById('fileBalasanInput').files[0];
                if (fileBalasan) {
                    formData.append('file_balasan', fileBalasan);
                }
                
                const response = await fetch(`/api/dokumen/${documenId}/proses`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    showToast('✓ Dokumen berhasil ditandai selesai & diarsipkan', 'success');
                    closeSelesaiModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast('❌ Error: ' + (data.message || 'Gagal menyelesaikan dokumen'), 'error');
                    button.disabled = false;
                    button.textContent = 'Selesai & Arsipkan';
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('❌ Error: ' + error.message, 'error');
                button.disabled = false;
                button.textContent = 'Selesai & Arsipkan';
            }
        });

        // Close modals when clicking outside
        document.getElementById('prosesModal').addEventListener('click', function(e) {
            if (e.target === this) closeProsesModal();
        });

        // Folder Navigation Logic
        function openPriorityFolder(priority) {
            // Update Title
            const folderTitle = document.getElementById('folderTitle');
            const folderSubtitle = document.getElementById('folderSubtitle');
            
            let title = '';
            let subtitle = '';
            
            switch(priority) {
                case 'AMAT SEGERA':
                    title = 'Folder: Amat Segera';
                    subtitle = 'Dokumen dengan prioritas tertinggi yang harus segera diproses';
                    break;
                case 'SEGERA':
                    title = 'Folder: Segera';
                    subtitle = 'Dokumen prioritas menengah';
                    break;
                case 'BIASA':
                    title = 'Folder: Biasa / Normal';
                    subtitle = 'Dokumen standar';
                    break;
            }
            
            folderTitle.textContent = title;
            folderSubtitle.textContent = subtitle;
            
            // Filter Items
            const items = document.querySelectorAll('.document-item');
            let hasVisibleItems = false;
            
            items.forEach(item => {
                // If priority is BIASA, also show items with no priority (null/empty)
                const itemPriority = item.getAttribute('data-priority');
                
                if (itemPriority === priority || (priority === 'BIASA' && !itemPriority)) {
                    item.classList.remove('hidden');
                    hasVisibleItems = true;
                } else {
                    item.classList.add('hidden');
                }
            });
            
            // Toggle Views
            document.getElementById('folderGrid').classList.add('hidden');
            document.getElementById('documentList').classList.remove('hidden');
            
            if (!hasVisibleItems) {
                // Show empty state if needed? (Already handled by empty list check in Blade, but filtering might hide all)
            }
        }

        function closePriorityFolder() {
            document.getElementById('documentList').classList.add('hidden');
            document.getElementById('folderGrid').classList.remove('hidden');
        }

        document.getElementById('selesaiModal').addEventListener('click', function(e) {
            if (e.target === this) closeSelesaiModal();
        });

        // Notification Logic for Staff
        let lastNotifCount = -1;
        async function checkNotifications() {
            try {
                const res = await fetch('/api/notifikasi/count');
                const data = await res.json();
                const newCount = data.count;

                if (lastNotifCount !== -1 && newCount > lastNotifCount) {
                     const diff = newCount - lastNotifCount;
                     showToast(`🔔 Ada ${diff} dokumen baru siap diproses!`, 'success');
                }
                lastNotifCount = newCount;
            } catch (e) {
                console.error("Notif check failed", e);
            }
        }
        
        // Initial check
        checkNotifications();
        // Poll every 10 seconds
        setInterval(checkNotifications, 10000);
    </script>
</body>
</html>
