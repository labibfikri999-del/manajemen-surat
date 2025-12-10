{{-- resources/views/validasi-dokumen.blade.php --}}
@php
    $user = auth()->user();
    $role = $user->role ?? 'guest';
    $roleLabels = [
        'direktur' => 'Direktur',
        'staff' => 'Staff Direktur', 
        'instansi' => $user->instansi->nama ?? 'Instansi',
    ];
    
    $statusColors = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'review' => 'bg-blue-100 text-blue-800',
        'disetujui' => 'bg-green-100 text-green-800',
        'ditolak' => 'bg-red-100 text-red-800',
    ];
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Validasi Dokumen — YARSI NTB</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Yayasan Bersih.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('partials.styles')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
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
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-emerald-900">Validasi     Dokumen</h1>
                    <p class="text-emerald-600 mt-2">Review dan validasi dokumen dari instansi</p>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                        <div class="text-3xl font-bold text-yellow-600">{{ $dokumens->where('status', 'pending')->count() }}</div>
                        <div class="text-sm text-gray-600">Menunggu Validasi</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                        <div class="text-3xl font-bold text-blue-600">{{ $dokumens->where('status', 'review')->count() }}</div>
                        <div class="text-sm text-gray-600">Sedang Review</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-emerald-500">
                        <div class="text-3xl font-bold text-emerald-600">{{ $dokumens->count() }}</div>
                        <div class="text-sm text-gray-600">Total</div>
                    </div>
                </div>

                {{-- Dokumen List --}}
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Dokumen Masuk</h2>
                    </div>
                    
                    @if($dokumens->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($dokumens as $dok)
                                <div class="p-4 hover:bg-gray-50">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$dok->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($dok->status) }}
                                                </span>
                                                <span class="text-xs text-gray-500">{{ $dok->created_at->timezone('Asia/Makassar')->format('d M Y, H:i') }}</span>
                                            </div>
                                            <h3 class="mt-2 font-semibold text-gray-900">{{ $dok->judul }}</h3>
                                            <p class="text-sm text-gray-600">{{ $dok->deskripsi }}</p>
                                            <div class="mt-2 flex flex-wrap gap-2 text-xs text-gray-500">
                                                <span class="px-2 py-1 bg-gray-100 rounded">{{ $dok->instansi->nama ?? 'N/A' }}</span>
                                                <span class="px-2 py-1 bg-gray-100 rounded">{{ ucfirst(str_replace('_', ' ', $dok->jenis)) }}</span>
                                                <span class="px-2 py-1 bg-gray-100 rounded">Pengguna</span>
                                            </div>
                                        </div>
                                        
                                        <div class="flex gap-2">
                                            @if($dok->file_path)
                                                <button onclick="showPreviewModal('{{ asset('storage/' . $dok->file_path) }}', '{{ $dok->judul }}', '{{ strtolower(pathinfo($dok->file_path, PATHINFO_EXTENSION)) }}')"
                                                   class="btn btn-sm btn-secondary flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    Lihat
                                                </button>
                                            @endif
                                            <button onclick="showValidasiModal({{ $dok->id }}, '{{ $dok->judul }}')" 
                                                    class="btn btn-sm btn-primary flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Validasi
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="mt-2 text-gray-500">Tidak ada dokumen yang perlu divalidasi</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    {{-- Modal Preview Dokumen --}}
    <div id="previewModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-[60] p-4 transition-all duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col mx-auto transform transition-all scale-100">
            <div class="p-4 border-b flex items-center justify-between bg-gray-50 rounded-t-2xl">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span id="previewTitle">Preview Dokumen</span>
                    </h3>
                </div>
                <div class="flex items-center gap-2">
                    <a id="downloadBtn" href="#" target="_blank" class="btn btn-ghost btn-ghost-primary" title="Download / Buka di Tab Baru">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                    <button onclick="closePreviewModal()" class="btn btn-ghost btn-ghost-danger" title="Tutup">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <div class="flex-1 bg-gray-100 relative p-0 overflow-hidden rounded-b-2xl">
                <div id="previewLoading" class="absolute inset-0 flex items-center justify-center bg-white z-10">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="animate-spin w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <p class="text-sm text-gray-500 font-medium">Memuat dokumen...</p>
                    </div>
                </div>
                <iframe id="previewFrame" class="w-full h-full border-0" onload="document.getElementById('previewLoading').classList.add('hidden')"></iframe>
                <div id="previewError" class="absolute inset-0 flex items-center justify-center bg-white hidden z-20">
                    <div class="text-center p-8 max-w-md">
                        <div class="bg-amber-100 text-amber-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2">Tidak dapat menampilkan preview</h4>
                        <p class="text-gray-600 mb-6">Format file ini mungkin tidak didukung untuk preview langsung oleh browser anda.</p>
                        <a id="downloadFallback" href="#" target="_blank" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download File
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Validasi --}}
    <div id="validasiModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4 transition-all duration-300 overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-auto transform transition-all scale-100 my-8">
            <div class="p-6 border-b flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Validasi Dokumen</h3>
                    <p id="modalDocTitle" class="text-sm text-gray-500 mt-1"></p>
                </div>
                <button onclick="closeValidasiModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="validasiForm" class="flex flex-col">
                @csrf
                <input type="hidden" id="dokumenId" name="dokumenId">
                <div class="p-6 space-y-6 overflow-y-auto max-h-96">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Keputusan Validasi</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-red-200 hover:bg-red-50 transition-all group peer-checked:border-red-500 peer-checked:bg-red-100">
                                <input type="radio" name="status" value="ditolak" class="peer sr-only" onchange="toggleSignatureArea(this.value)" required>
                                <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform peer-checked:bg-red-600 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-red-500/50">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </div>
                                <span class="font-medium text-gray-700 peer-checked:text-red-700 peer-checked:font-bold">Tolak Dokumen</span>
                            </label>

                            <label class="relative flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-emerald-200 hover:bg-emerald-50 transition-all group peer-checked:border-emerald-500 peer-checked:bg-emerald-100">
                                <input type="radio" name="status" value="disetujui" class="peer sr-only" required onchange="toggleSignatureArea(this.value)">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-emerald-500/50">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="font-medium text-gray-700 peer-checked:text-emerald-700 peer-checked:font-bold">Setujui Dokumen</span>
                            </label>
                        </div>
                    </div>
                    
                    <div id="prioritasArea" class="hidden animate-fade-in space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Sifat Surat (Prioritas) <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-3 gap-4">
                                <!-- BIASA -->
                                <label class="relative flex flex-col items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-blue-300 hover:bg-blue-50/50 transition-all group peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-md peer-checked:ring-1 peer-checked:ring-blue-500">
                                    <input type="radio" name="prioritas" value="BIASA" class="peer sr-only">
                                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform peer-checked:bg-blue-600 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-blue-500/30">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-600 peer-checked:text-blue-700 transition-colors">BIASA</span>
                                    <span class="text-xs text-gray-400 mt-1">Standar</span>
                                </label>

                                <!-- SEGERA -->
                                <label class="relative flex flex-col items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-amber-300 hover:bg-amber-50/50 transition-all group peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:shadow-md peer-checked:ring-1 peer-checked:ring-amber-500">
                                    <input type="radio" name="prioritas" value="SEGERA" class="peer sr-only">
                                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform peer-checked:bg-amber-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-amber-500/30">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-600 peer-checked:text-amber-700 transition-colors">SEGERA</span>
                                    <span class="text-xs text-gray-400 mt-1">Urgent</span>
                                </label>

                                <!-- AMAT SEGERA -->
                                <label class="relative flex flex-col items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:border-red-300 hover:bg-red-50/50 transition-all group peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:shadow-md peer-checked:ring-1 peer-checked:ring-red-500">
                                    <input type="radio" name="prioritas" value="AMAT SEGERA" class="peer sr-only">
                                    <div class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform peer-checked:bg-red-600 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-red-500/30">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-600 peer-checked:text-red-700 transition-colors">AMAT SEGERA</span>
                                    <span class="text-xs text-gray-400 mt-1">High Priority</span>
                                </label>
                            </div>
                        </div>


                    </div>
                    
                    <div id="disposisiArea" class="hidden animate-fade-in mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Disposisi Ke (Opsional)</label>
                        <div class="relative">
                            <select name="disposisi_tujuan" class="w-full px-4 py-3 border border-gray-300 rounded-xl appearance-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-shadow bg-white">
                                <option value="">-- Pilih Tujuan Disposisi --</option>
                                <option value="KEUANGAN">KEUANGAN & ADMIN</option>
                                <option value="SDM">SDM & KEPEGAWAIAN</option>
                                <option value="HUKUM">HUKUM & LEGAL</option>
                                <option value="ASSET">ASSET & LOGISTIK</option>
                                <option value="UMUM">UMUM & RUMAH TANGGA</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Pilih departemen jika surat ini perlu ditindaklanjuti spesifik.</p>
                    </div>
                    

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-shadow" placeholder="Berikan catatan jika ada..."></textarea>
                    </div>
                </div>
                <div class="sticky bottom-0 p-6 border-t bg-gray-50 flex gap-3 justify-end rounded-b-2xl flex-shrink-0">
                    <button type="button" onclick="closeValidasiModal()" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary shadow-lg" id="submitValidasiBtn">
                        <span>Simpan Keputusan</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('partials.scripts')
    <script>
        // Auto-refresh logic for real-time updates
        setInterval(async () => {
            // Check if any modal is open (prevent reload while user is validating)
            const modals = document.querySelectorAll('[id$="Modal"]');
            const isModalOpen = Array.from(modals).some(m => !m.classList.contains('hidden'));
            if (isModalOpen) return;

            try {
                const response = await fetch(window.location.href);
                if (!response.ok) return;
                
                const text = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(text, 'text/html');
                
                const currentContent = document.querySelector('div.max-w-7xl').innerHTML;
                const newContent = doc.querySelector('div.max-w-7xl').innerHTML;
                
                if (currentContent !== newContent) {
                    showToast('Data dokumen baru masuk, memuat ulang...', 'info');
                    setTimeout(() => window.location.reload(), 1500);
                }
            } catch (e) {}
        }, 10000);

        function toggleSignatureArea(value) {
            const prioritasArea = document.getElementById('prioritasArea');
            const disposisiArea = document.getElementById('disposisiArea'); // New
            const prioritasRadios = prioritasArea.querySelectorAll('input[name="prioritas"]');
            
            if (value === 'disetujui') {
                prioritasArea.classList.remove('hidden');
                if(disposisiArea) disposisiArea.classList.remove('hidden'); // Show Disposisi
                
                // Set required only when visible
                prioritasRadios.forEach(radio => {
                    radio.required = true;
                });
            } else {
                prioritasArea.classList.add('hidden');
                if(disposisiArea) disposisiArea.classList.add('hidden'); // Hide Disposisi
                
                prioritasRadios.forEach(radio => {
                    radio.required = false;
                });
            }
        }

        function showValidasiModal(id, title) {
            document.getElementById('modalDocTitle').textContent = title;
            document.getElementById('dokumenId').value = id;
            document.getElementById('validasiForm').reset();
            document.getElementById('validasiModal').classList.remove('hidden');
            document.getElementById('validasiModal').classList.add('flex');
        }

        function showPreviewModal(url, title, extension) {
            const modal = document.getElementById('previewModal');
            const frame = document.getElementById('previewFrame');
            const titleEl = document.getElementById('previewTitle');
            const loading = document.getElementById('previewLoading');
            const error = document.getElementById('previewError');
            const downloadBtn = document.getElementById('downloadBtn');
            const downloadFallback = document.getElementById('downloadFallback');

            // Hide Navbar
            const navbar = document.querySelector('header');
            if (navbar) navbar.style.display = 'none';

            titleEl.textContent = title;
            loading.classList.remove('hidden');
            error.classList.add('hidden');
            frame.src = url;
            
            downloadBtn.href = url;
            downloadFallback.href = url;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Handle non-previewable files roughly
            const previewable = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'txt'];
            if (!previewable.includes(extension.toLowerCase())) {
                loading.classList.add('hidden');
                error.classList.remove('hidden');
            }
        }

        function closePreviewModal() {
            const modal = document.getElementById('previewModal');
            const frame = document.getElementById('previewFrame');
            
            // Show Navbar
            const navbar = document.querySelector('header');
            if (navbar) navbar.style.display = '';

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            frame.src = ''; // Stop loading
        }

        // Close modal on click outside
        document.getElementById('previewModal').addEventListener('click', function(e) {
            if (e.target === this) closePreviewModal();
        });

        function closeValidasiModal() {
            document.getElementById('validasiModal').classList.add('hidden');
            document.getElementById('validasiModal').classList.remove('flex');
        }

        document.getElementById('validasiModal').addEventListener('click', function(e) {
            if (e.target === this) closeValidasiModal();
        });

        // Form submit via AJAX
        document.getElementById('validasiForm').addEventListener('submit', async (e) => {
            e.preventDefault();// Debug log

            const dokumenId = document.getElementById('dokumenId').value;
            const status = document.querySelector('input[name="status"]:checked')?.value;
            const catatan = document.querySelector('textarea[name="catatan"]').value;
            const submitBtn = document.getElementById('submitValidasiBtn');
            const originalBtnText = submitBtn.innerHTML;// Debug log// Debug log

            if (!status) {
                showToast('Pilih keputusan terlebih dahulu', 'warning');
                return;
            }



            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Menyimpan...';

            try {
                const prioritas = document.querySelector('input[name="prioritas"]:checked')?.value;// Debug log
                
                // Content Prioritas & Disposisi check
                if (status === 'disetujui') {
                    if (!prioritas) {
                        showToast('Pilih sifat surat terlebih dahulu', 'warning');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                        return;
                    }

                }

                const payload = {
                    status: status,
                    prioritas: prioritas || null, 
                    disposisi_tujuan: null,
                    catatan: catatan
                };// Debug log

                const response = await fetch(`/api/dokumen/${dokumenId}/validasi`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (response.ok) {
                    closeValidasiModal();
                    showToast(`✓ ${status === 'disetujui' ? 'Dokumen disetujui' : 'Dokumen ditolak'}`, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    const errorMsg = data.error || data.message || 'Validasi gagal';
                    showToast(`❌ ${errorMsg}`, 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            } catch(e) {
                console.error('Error:', e); // Debug log
                showToast(`❌ Error: ${e.message}`, 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });

        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-100 border-green-300 text-green-700' 
                          : type === 'error' ? 'bg-red-100 border-red-300 text-red-700'
                          : 'bg-yellow-100 border-yellow-300 text-yellow-700';
            
            const icon = type === 'success' ? '✓'
                       : type === 'error' ? '✗'
                       : '⚠️';

            toast.className = `fixed top-20 right-4 p-4 rounded-lg border ${bgColor} flex items-center gap-3 min-w-[300px] max-w-md animate-fade-in shadow-lg z-[9999]`;
            toast.innerHTML = `
                <span class="text-xl">${icon}</span>
                <span>${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-auto font-bold">×</button>
            `;

            document.body.appendChild(toast);

            // Auto remove after 3 seconds
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Notification Logic for Direktur
        let lastNotifCount = -1;
        async function checkNotifications() {
            try {
                const res = await fetch('/api/notifikasi/count');
                const data = await res.json();
                const newCount = data.count;

                if (lastNotifCount !== -1 && newCount > lastNotifCount) {
                     const diff = newCount - lastNotifCount;
                     showToast(`🔔 Ada ${diff} surat masuk baru menunggu validasi!`, 'info');
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
