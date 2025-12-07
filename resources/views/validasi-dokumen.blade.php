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
                                                <span class="text-xs text-gray-500">{{ $dok->created_at->format('d M Y, H:i') }}</span>
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
                                                <a href="{{ route('dokumen.download', $dok->id) }}"
                                                   class="px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    Lihat
                                                </a>
                                            @endif
                                            <button onclick="showValidasiModal({{ $dok->id }}, '{{ $dok->judul }}')" 
                                                    class="px-3 py-2 text-sm bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition flex items-center gap-1">
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
                    
                    <div id="prioritasArea" class="hidden animate-fade-in">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Sifat Surat <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="relative flex flex-col items-center p-4 border-4 border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all group peer-checked:border-blue-500 peer-checked:bg-blue-200 peer-checked:shadow-lg peer-checked:shadow-blue-300/60">
                                <input type="radio" name="prioritas" value="BIASA" class="peer sr-only">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mb-2 group-hover:scale-125 transition-transform peer-checked:bg-blue-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-blue-400/60">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
                                </div>
                                <span class="text-sm font-bold text-gray-700 peer-checked:text-blue-800 peer-checked:text-base text-center">BIASA</span>
                            </label>

                            <label class="relative flex flex-col items-center p-4 border-4 border-gray-200 rounded-xl cursor-pointer hover:border-amber-300 hover:bg-amber-50 transition-all group peer-checked:border-amber-500 peer-checked:bg-amber-200 peer-checked:shadow-lg peer-checked:shadow-amber-300/60">
                                <input type="radio" name="prioritas" value="PENTING" class="peer sr-only">
                                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-2 group-hover:scale-125 transition-transform peer-checked:bg-amber-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-amber-400/60">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" opacity="0.5"/><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" clip-path="url(#clip)"/></svg>
                                </div>
                                <span class="text-sm font-bold text-gray-700 peer-checked:text-amber-800 peer-checked:text-base text-center">PENTING</span>
                            </label>

                            <label class="relative flex flex-col items-center p-4 border-4 border-gray-200 rounded-xl cursor-pointer hover:border-red-300 hover:bg-red-50 transition-all group peer-checked:border-red-500 peer-checked:bg-red-200 peer-checked:shadow-lg peer-checked:shadow-red-300/60">
                                <input type="radio" name="prioritas" value="MENDESAK" class="peer sr-only">
                                <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center mb-2 group-hover:scale-125 transition-transform peer-checked:bg-red-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-red-400/60">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
                                </div>
                                <span class="text-sm font-bold text-gray-700 peer-checked:text-red-800 peer-checked:text-base text-center">MENDESAK</span>
                            </label>
                        </div>
                    </div>
                    
                    <div id="signatureArea" class="hidden animate-fade-in">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanda Tangan Digital <span class="text-red-500">*</span></label>
                        <div class="relative group">
                            <div class="border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 hover:bg-white hover:border-emerald-400 transition-colors overflow-hidden relative">
                                <canvas id="signatureCanvas" class="w-full h-48 cursor-crosshair touch-none"></canvas>
                                
                                {{-- Placeholder text --}}
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none text-gray-400 opacity-50 group-hover:opacity-30 transition-opacity">
                                    <span class="text-sm">Tanda tangan disini</span>
                                </div>
                            </div>
                            
                            {{-- Tools --}}
                            <div class="absolute top-2 right-2 flex gap-2">
                                <button type="button" onclick="clearSignature()" class="p-1.5 bg-white border border-gray-200 rounded-lg shadow-sm text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus Tanda Tangan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Pastikan tanda tangan terlihat jelas.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-shadow" placeholder="Berikan catatan jika ada..."></textarea>
                    </div>
                </div>
                <div class="sticky bottom-0 p-6 border-t bg-gray-50 flex gap-3 justify-end rounded-b-2xl flex-shrink-0">
                    <button type="button" onclick="closeValidasiModal()" class="px-5 py-2.5 text-gray-700 font-medium bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-medium rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 flex items-center gap-2" id="submitValidasiBtn">
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

        // Signature Pad Init
        let signaturePad = null;
        
        function initSignaturePad() {
            const canvas = document.getElementById('signatureCanvas');
            
            // Adjust canvas resolution for high DPI screens
            const ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);

            signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0)', // Transparent
                penColor: 'rgb(0, 0, 0)'
            });
        }

        function toggleSignatureArea(value) {
            const signatureArea = document.getElementById('signatureArea');
            const prioritasArea = document.getElementById('prioritasArea');
            const prioritasRadios = prioritasArea.querySelectorAll('input[name="prioritas"]');
            if (value === 'disetujui') {
                signatureArea.classList.remove('hidden');
                prioritasArea.classList.remove('hidden');
                // Set required only when visible
                prioritasRadios.forEach(radio => {
                    radio.required = true;
                });
                setTimeout(() => {
                    initSignaturePad();
                }, 100);
            } else {
                signatureArea.classList.add('hidden');
                prioritasArea.classList.add('hidden');
                // Remove required when hidden
                prioritasRadios.forEach(radio => {
                    radio.required = false;
                    radio.checked = false;
                });
            }
        }

        function clearSignature() {
            if (signaturePad) signaturePad.clear();
        }

        function showValidasiModal(id, title) {
            document.getElementById('modalDocTitle').textContent = title;
            document.getElementById('dokumenId').value = id;
            document.getElementById('validasiForm').reset();
            document.getElementById('validasiModal').classList.remove('hidden');
            document.getElementById('validasiModal').classList.add('flex');
        }

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

            // Get signature if accepted
            let signatureData = null;
            if (status === 'disetujui' && signaturePad && !signaturePad.isEmpty()) {
                signatureData = signaturePad.toDataURL('image/png');
            } else if (status === 'disetujui' && (!signaturePad || signaturePad.isEmpty())) {
                showToast('Tanda tangan diperlukan untuk menyetujui dokumen', 'warning');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Menyimpan...';

            try {
                const prioritas = document.querySelector('input[name="prioritas"]:checked')?.value;// Debug log
                
                // Prioritas hanya required saat "Setujui"
                if (status === 'disetujui' && !prioritas) {
                    showToast('Pilih sifat surat terlebih dahulu', 'warning');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    return;
                }

                const payload = {
                    status: status,
                    prioritas: prioritas || null, // Kirim null jika tolak
                    catatan: catatan,
                    signature: signatureData
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
                    if(signaturePad) signaturePad.clear();
                    showToast(`✓ ${status === 'disetujui' ? 'Dokumen disetujui & ditandatangani' : 'Dokumen ditolak'}`, 'success');
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
    </script>
</body>
</html>
