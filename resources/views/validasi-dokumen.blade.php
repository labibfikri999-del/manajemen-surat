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
    <script src="https://cdn.tailwindcss.com"></script>
    @include('partials.styles')
</head>
<body class="bg-emerald-50">
    <div id="app" class="flex flex-col">
        @include('partials.header')
        @include('partials.sidebar-menu')

        {{-- Main content --}}
        <main id="main" class="transition-smooth main-with-sidebar flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
            <div class="max-w-7xl mx-auto">
                @include('partials.flash-messages')

                {{-- Page header --}}
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-emerald-900">Validasi Dokumen</h1>
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
                                                <a href="{{ Storage::url($dok->file_path) }}" target="_blank" 
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
    <div id="validasiModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Validasi Dokumen</h3>
                <p id="modalDocTitle" class="text-sm text-gray-500"></p>
            </div>
            <form id="validasiForm">
                @csrf
                <input type="hidden" id="dokumenId" name="dokumenId">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keputusan</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="disetujui" class="text-emerald-600" required>
                                <span class="text-green-600 font-medium">✓ Setujui</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="ditolak" class="text-red-600">
                                <span class="text-red-600 font-medium">✗ Tolak</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Catatan validasi (opsional)"></textarea>
                    </div>
                </div>
                <div class="p-6 border-t bg-gray-50 flex gap-3 justify-end rounded-b-xl">
                    <button type="button" onclick="closeValidasiModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition" id="submitValidasiBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @include('partials.scripts')
    <script>
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
            e.preventDefault();

            const dokumenId = document.getElementById('dokumenId').value;
            const status = document.querySelector('input[name="status"]:checked')?.value;
            const catatan = document.querySelector('textarea[name="catatan"]').value;
            const submitBtn = document.getElementById('submitValidasiBtn');
            const originalBtnText = submitBtn.textContent;

            if (!status) {
                showToast('Pilih keputusan terlebih dahulu', 'warning');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Menyimpan...';

            try {
                const response = await fetch(`/api/dokumen/${dokumenId}/validasi`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        status: status,
                        catatan: catatan
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    // Close modal
                    closeValidasiModal();
                    
                    // Show success notification
                    const resultText = status === 'disetujui' ? 'Dokumen disetujui' : 'Dokumen ditolak';
                    showToast(`✓ ${resultText}`, 'success');
                    
                    // Reload page after 1 second to refresh list
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    const errorMsg = data.error || data.message || 'Validasi gagal, coba lagi';
                    showToast(`❌ ${errorMsg}`, 'error');
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('❌ Terjadi kesalahan', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
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
