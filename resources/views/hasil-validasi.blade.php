{{-- resources/views/hasil-validasi.blade.php --}}
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
        'ditolak' => 'bg-red-100 text-red-800',
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
    <title>Hasil Validasi — YARSI NTB</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Yayasan Bersih.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('partials.styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <h1 class="text-3xl font-bold text-emerald-900">Hasil Validasi</h1>
                    <p class="text-emerald-600 mt-2">Lihat dokumen yang sudah divalidasi</p>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                        <div class="text-3xl font-bold text-green-600">{{ $dokumens->where('status', 'disetujui')->count() }}</div>
                        <div class="text-sm text-gray-600">Disetujui</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
                        <div class="text-3xl font-bold text-red-600">{{ $dokumens->where('status', 'ditolak')->count() }}</div>
                        <div class="text-sm text-gray-600">Ditolak</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
                        <div class="text-3xl font-bold text-purple-600">{{ $dokumens->where('status', 'diproses')->count() }}</div>
                        <div class="text-sm text-gray-600">Diproses</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-emerald-500">
                        <div class="text-3xl font-bold text-emerald-600">{{ $dokumens->where('status', 'selesai')->count() }}</div>
                        <div class="text-sm text-gray-600">Selesai</div>
                    </div>
                </div>

                {{-- Filter --}}
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <div class="flex flex-wrap gap-2">
                        <button onclick="filterStatus('all')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium bg-emerald-600 text-white" data-status="all">
                            Semua
                        </button>
                        <button onclick="filterStatus('disetujui')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200" data-status="disetujui">
                            ✓ Disetujui
                        </button>
                        <button onclick="filterStatus('ditolak')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200" data-status="ditolak">
                            ✗ Ditolak
                        </button>
                        <button onclick="filterStatus('diproses')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200" data-status="diproses">
                            ⚡ Diproses
                        </button>
                        <button onclick="filterStatus('selesai')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200" data-status="selesai">
                            ✓ Selesai
                        </button>
                    </div>
                </div>

                {{-- Dokumen List --}}
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    @if($dokumens->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instansi</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200" id="dokumenList">
                                    @foreach($dokumens as $dok)
                                        <tr class="dokumen-item hover:bg-gray-50" data-status="{{ $dok->status }}">
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                {{ $dok->tanggal_validasi ? $dok->tanggal_validasi->timezone('Asia/Makassar')->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="font-medium text-gray-900">{{ $dok->judul }}</div>
                                                <div class="text-xs text-gray-500">{{ Str::limit($dok->deskripsi, 50) }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                {{ $dok->instansi->nama ?? 'N/A' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$dok->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($dok->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-xs text-gray-600">
                                                <div class="flex flex-col gap-1">
                                                    @if($dok->validator)
                                                        <span class="text-blue-600">Val: {{ $dok->validator->name }}</span>
                                                    @endif
                                                    @if($dok->processor)
                                                        <span class="text-purple-600">Pros: {{ $dok->processor->name }}</span>
                                                    @endif
                                                    @if($dok->catatan_validasi)
                                                        <span class="text-gray-500 italic">"{{ Str::limit($dok->catatan_validasi, 30) }}"</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    @if($dok->file_path)
                                                        <button onclick="showPreviewModal('{{ asset('storage/' . $dok->file_path) }}', '{{ $dok->judul }}', '{{ strtolower(pathinfo($dok->file_path, PATHINFO_EXTENSION)) }}')"
                                                           class="p-2 bg-emerald-100 text-emerald-600 rounded-lg hover:bg-emerald-200 transition"
                                                           title="Lihat File">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        </button>
                                                        
                                                        <a href="{{ route('dokumen.download', $dok->id) }}"
                                                           class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition"
                                                           title="Download File">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                        </a>
                                                    @endif

                                                    @if(((auth()->user()->id === $dok->user_id) || auth()->user()->isDirektur()) && in_array($dok->status, ['selesai', 'ditolak']))
                                                        <button onclick="deleteDokumen({{ $dok->id }})" 
                                                                class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition"
                                                                title="Hapus Dokumen">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-2 text-gray-500">Belum ada dokumen yang divalidasi</p>
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
                    <a id="downloadBtn" href="#" target="_blank" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-white rounded-lg transition" title="Download / Buka di Tab Baru">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                    <button onclick="closePreviewModal()" class="p-2 text-gray-500 hover:text-red-600 hover:bg-white rounded-lg transition" title="Tutup">
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
                        <a id="downloadFallback" href="#" target="_blank" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download File
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.scripts')
    <script>
        function filterStatus(status) {
            // Update buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                if (btn.dataset.status === status) {
                    btn.classList.remove('bg-gray-100', 'text-gray-700');
                    btn.classList.add('bg-emerald-600', 'text-white');
                } else {
                    btn.classList.remove('bg-emerald-600', 'text-white');
                    btn.classList.add('bg-gray-100', 'text-gray-700');
                }
            });

            // Filter items
            document.querySelectorAll('.dokumen-item').forEach(item => {
                if (status === 'all' || item.dataset.status === status) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
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

        // Delete Function
        function deleteDokumen(id) {
            Swal.fire({
                title: 'Hapus Dokumen?',
                text: "Apakah anda yakin ingin menghapus dokumen ini secara permanen? Data tidak dapat dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-xl shadow-xl',
                    confirmButton: 'px-4 py-2 rounded-lg font-medium',
                    cancelButton: 'px-4 py-2 rounded-lg font-medium'
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        // Show loading state
                        Swal.fire({
                            title: 'Menghapus...',
                            html: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        const response = await fetch(`/api/dokumen/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            await Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Dokumen berhasil dihapus.',
                                confirmButtonColor: '#10b981',
                                timer: 1500
                            });
                            location.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.error || 'Terjadi kesalahan saat menghapus.',
                                confirmButtonColor: '#ef4444'
                            });
                        }
                    } catch (e) {
                        console.error(e);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan koneksi.',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                }
            });
        }
    </script>
</body>
</html>
