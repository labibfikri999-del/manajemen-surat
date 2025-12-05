{{-- resources/views/upload-dokumen.blade.php --}}
@php
    $user = auth()->user();
    $role = $user->role ?? 'guest';
    $roleLabels = [
        'direktur' => 'Direktur',
        'staff' => 'Staff Direktur', 
        'instansi' => $user->instansi->nama ?? 'Instansi',
    ];
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Upload Dokumen — YARSI NTB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('partials.styles')
</head>
<body class="bg-emerald-50">
    <div id="app" class="flex flex-col">
        @include('partials.header')
        @include('partials.sidebar-menu')

        {{-- Main content --}}
        <main id="main" class="transition-smooth main-with-sidebar flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
            <div class="max-w-4xl mx-auto">
                @include('partials.flash-messages')

                {{-- Page header --}}
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-emerald-900">Upload Dokumen</h1>
                    <p class="text-emerald-600 mt-2">Upload dokumen untuk validasi oleh Direktur</p>
                </div>

                {{-- Upload Form --}}
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="space-y-6">
                            {{-- Judul Dokumen --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Dokumen <span class="text-red-500">*</span></label>
                                <input type="text" name="judul" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Masukkan judul dokumen...">
                            </div>

                            {{-- Jenis Dokumen --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Dokumen <span class="text-red-500">*</span></label>
                                <select name="jenis" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="surat_masuk">Surat Masuk</option>
                                    <option value="surat_keluar">Surat Keluar</option>
                                    <option value="proposal">Proposal</option>
                                    <option value="laporan">Laporan</option>
                                    <option value="sk">Surat Keputusan (SK)</option>
                                    <option value="kontrak">Kontrak/Perjanjian</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                <textarea name="deskripsi" rows="4" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Deskripsi singkat tentang dokumen ini..."></textarea>
                            </div>

                            {{-- File Upload --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">File Dokumen <span class="text-red-500">*</span></label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-emerald-500 transition-colors" id="dropZone">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">Drag & drop file disini, atau</p>
                                    <label class="mt-2 inline-block px-4 py-2 bg-emerald-600 text-white rounded-lg cursor-pointer hover:bg-emerald-700 transition">
                                        <span>Pilih File</span>
                                        <input type="file" name="file" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx" required id="fileInput">
                                    </label>
                                    <p class="mt-2 text-xs text-gray-500">PDF, DOC, DOCX, XLS, XLSX (Maks. 10MB)</p>
                                    <p id="fileName" class="mt-2 text-sm text-emerald-600 font-medium hidden"></p>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="flex gap-4">
                                <button type="submit" class="flex-1 px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition flex items-center justify-center gap-2" id="submitBtn">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    Upload Dokumen
                                </button>
                                <a href="{{ route('tracking-dokumen') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                                    Lihat Status
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    @include('partials.scripts')
    <script>
        const fileInput = document.getElementById('fileInput');
        const fileName = document.getElementById('fileName');
        const dropZone = document.getElementById('dropZone');
        const uploadForm = document.getElementById('uploadForm');
        const submitBtn = document.getElementById('submitBtn');

        fileInput.addEventListener('change', function() {
            if (this.files[0]) {
                fileName.textContent = '📄 ' + this.files[0].name;
                fileName.classList.remove('hidden');
            }
        });

        // Drag & Drop
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-emerald-500', 'bg-emerald-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-emerald-500', 'bg-emerald-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-emerald-500', 'bg-emerald-50');
            if (e.dataTransfer.files[0]) {
                fileInput.files = e.dataTransfer.files;
                fileName.textContent = '📄 ' + e.dataTransfer.files[0].name;
                fileName.classList.remove('hidden');
            }
        });

        // Form submit via AJAX
        uploadForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(uploadForm);
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Uploading...';

            try {
                const response = await fetch('/api/dokumen', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    // Show success notification
                    showToast('✓ Dokumen berhasil diunggah', 'success');
                    
                    // Reset form
                    uploadForm.reset();
                    fileName.classList.add('hidden');
                    fileName.textContent = '';
                    
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                } else {
                    // Show error
                    const errorMsg = data.error || data.message || 'Upload gagal, coba lagi';
                    showToast(errorMsg, 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('❌ Terjadi kesalahan saat upload', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });

        // Toast notification function
        function showToast(message, type = 'success') {
            // Create toast element
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-100 border-green-300 text-green-700' 
                          : type === 'error' ? 'bg-red-100 border-red-300 text-red-700'
                          : 'bg-blue-100 border-blue-300 text-blue-700';
            
            const icon = type === 'success' ? '✓'
                       : type === 'error' ? '✗'
                       : 'ℹ️';

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
