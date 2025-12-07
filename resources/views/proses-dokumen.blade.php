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
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-emerald-900">Proses Dokumen</h1>
                    <p class="text-emerald-600 mt-2">Kelola dokumen yang sudah divalidasi Direktur</p>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                        <div class="text-3xl font-bold text-green-600">{{ $dokumens->where('status', 'disetujui')->count() }}</div>
                        <div class="text-sm text-gray-600">Perlu Diproses</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
                        <div class="text-3xl font-bold text-purple-600">{{ $dokumens->where('status', 'diproses')->count() }}</div>
                        <div class="text-sm text-gray-600">Sedang Diproses</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-emerald-500">
                        <div class="text-3xl font-bold text-emerald-600">{{ $dokumens->count() }}</div>
                        <div class="text-sm text-gray-600">Total</div>
                    </div>
                </div>

                {{-- Dokumen List --}}
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Dokumen dari Direktur</h2>
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
                        <div class="p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-2 text-gray-500">Belum ada dokumen yang perlu diproses</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    {{-- Modal Proses --}}
    <div id="prosesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
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
    <div id="selesaiModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
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
                            File Pengganti (Opsional)
                        </label>
                        <p class="text-xs text-gray-500 mb-2">Upload file pengganti jika dokumen sudah diubah (PDF, DOCX, XLS, dll)</p>
                        <label class="flex items-center justify-center px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-emerald-500 transition-colors bg-gray-50">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <span class="text-sm text-gray-600" id="fileReplacementLabel">Pilih File</span>
                            <input type="file" name="file_pengganti" id="fileReplacementInput" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                        </label>
                        <p class="text-xs text-gray-500 mt-1" id="fileReplacementName"></p>
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
        // Auto-refresh logic (Realtime)
        setInterval(async () => {
            const modals = document.querySelectorAll('[id$="Modal"]');
            const isModalOpen = Array.from(modals).some(m => !m.classList.contains('hidden'));
            if (isModalOpen) return;

            try {
                const response = await fetch(window.location.href);
                if (!response.ok) return;
                const text = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(text, 'text/html');
                const newContent = doc.querySelector('div.max-w-7xl').innerHTML;
                const currentContent = document.querySelector('div.max-w-7xl').innerHTML;
                
                if (newContent !== currentContent) {
                    showToast('Data baru masuk, memuat ulang...', 'info');
                    setTimeout(() => window.location.reload(), 1500);
                }
            } catch(e) {}
        }, 10000);

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

        // File Replacement Input Handler
        document.getElementById('fileReplacementInput').addEventListener('change', function() {
            if (this.files[0]) {
                const file = this.files[0];
                document.getElementById('fileReplacementLabel').textContent = '✓ File dipilih';
                document.getElementById('fileReplacementName').textContent = '📄 ' + file.name;
            }
        });

        // Selesai Form AJAX Submission
        document.getElementById('selesaiForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const documenId = document.getElementById('selesaiDocumenId').value;
            const button = document.getElementById('submitSelesaiBtn');
            const catatan = document.querySelector('#selesaiForm textarea[name="catatan"]').value;
            const kategoriArsip = document.querySelector('#selesaiForm input[name="kategori_arsip"]:checked');
            const fileReplacement = document.getElementById('fileReplacementInput').files[0];
            
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
                
                if (fileReplacement) {
                    formData.append('file_pengganti', fileReplacement);
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

        document.getElementById('selesaiModal').addEventListener('click', function(e) {
            if (e.target === this) closeSelesaiModal();
        });
    </script>
</body>
</html>
