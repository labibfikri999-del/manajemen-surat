@php $user = auth()->user(); $role = $user->role ?? 'guest'; @endphp
{{-- resources/views/surat-masuk.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Surat Masuk — YARSI NTB</title>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_rsi_ntb.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.styles')
</head>
<body class="bg-emerald-50">
  <div id="app" class="flex flex-col">
    @include('partials.header')

    @include('partials.sidebar-menu')

    {{-- Main content --}}
    <main id="main" class="transition-smooth main-with-sidebar flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
      @include('partials.flash-messages')
      <div class="max-w-7xl mx-auto">
        {{-- Page header --}}
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-emerald-900">Surat Masuk</h1>
          <p class="text-emerald-600 mt-2">Kelola semua surat yang masuk ke instansi</p>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 mb-6">
          <div class="bg-white rounded-lg shadow p-3 md:p-4">
            <div class="text-emerald-600 text-xs md:text-sm font-medium">Total Surat Masuk</div>
            <p id="countTotal" class="text-2xl md:text-3xl font-bold text-emerald-900 mt-2">0</p>
            <p class="text-xs text-emerald-500 mt-1">Tahun ini</p>
          </div>
          <div class="bg-white rounded-lg shadow p-3 md:p-4">
            <div class="text-emerald-600 text-xs md:text-sm font-medium">Manual (Perlu Perhatian)</div>
            <p id="countPending" class="text-2xl md:text-3xl font-bold text-orange-600 mt-2">0</p>
            <p class="text-xs text-emerald-500 mt-1">Entry Manual</p>
          </div>
          <div class="bg-white rounded-lg shadow p-3 md:p-4">
            <div class="text-emerald-600 text-xs md:text-sm font-medium">Digital (Pusat)</div>
            <p id="countSelesai" class="text-2xl md:text-3xl font-bold text-green-600 mt-2">0</p>
            <p class="text-xs text-emerald-500 mt-1">Dari Staff/Director</p>
          </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row flex-wrap gap-3 mb-6">
          <button id="btnTambah" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 flex items-center justify-center gap-2" aria-label="Tambah Surat Masuk Baru">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Surat Masuk
          </button>
          <button id="btnCetak" class="border border-emerald-300 text-emerald-700 px-4 py-2 rounded hover:bg-emerald-50 flex items-center justify-center gap-2" aria-label="Cetak Daftar Surat Masuk">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H9m4 0h4m-2-2v2m0 0v2m0-6V9m0 4h.01"/></svg>
            Cetak Daftar
          </button>
          <button id="btnImpor" class="border border-emerald-300 text-emerald-700 px-4 py-2 rounded hover:bg-emerald-50 flex items-center justify-center gap-2" aria-label="Impor File Surat Masuk">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Impor File
          </button>
          <input type="text" id="searchInput" placeholder="Cari surat..." class="flex-1 sm:flex-initial sm:ml-auto px-4 py-2 border border-emerald-300 rounded focus:outline-none focus:border-emerald-500" aria-label="Cari surat masuk" />
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">No</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No Agenda</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Tanggal</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No Surat</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Asal Surat</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Perihal</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Sifat</th>
                  <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                </tr>
              </thead>
              <tbody id="tableBody" class="divide-y divide-emerald-100">
                {{-- Skeleton Loading --}}
                <tr class="animate-pulse skeleton-row">
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-8"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-32"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-24"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-40"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-48"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-16"></div></td>
                  <td class="px-6 py-4"><div class="h-5 bg-emerald-200 rounded w-16"></div></td>
                  <td class="px-6 py-4"><div class="h-8 bg-emerald-200 rounded w-32 ml-auto"></div></td>
                </tr>
                <tr class="animate-pulse skeleton-row">
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-8"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-32"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-24"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-40"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-48"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-16"></div></td>
                  <td class="px-6 py-4"><div class="h-5 bg-emerald-200 rounded w-16"></div></td>
                  <td class="px-6 py-4"><div class="h-8 bg-emerald-200 rounded w-32 ml-auto"></div></td>
                </tr>
                <tr class="animate-pulse skeleton-row">
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-8"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-32"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-24"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-40"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-48"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-emerald-200 rounded w-16"></div></td>
                  <td class="px-6 py-4"><div class="h-5 bg-emerald-200 rounded w-16"></div></td>
                  <td class="px-6 py-4"><div class="h-8 bg-emerald-200 rounded w-32 ml-auto"></div></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-between mt-6">
          <p id="paginationInfo" class="text-sm text-emerald-600">Menampilkan 0 dari 0 surat masuk</p>
          <div class="flex gap-2">
            <button class="px-3 py-1 border border-emerald-300 rounded text-emerald-600 hover:bg-emerald-50">← Sebelumnya</button>
            <button class="px-3 py-1 border border-emerald-300 rounded text-emerald-600 hover:bg-emerald-50">Selanjutnya →</button>
          </div>
        </div>
      </div>
    </main>

    {{-- Toast Notification --}}
    <div id="toast" class="hidden fixed top-4 right-4 z-[9999] transform transition-all duration-300 ease-out pointer-events-none">
      <div class="bg-white rounded-lg shadow-2xl p-4 flex items-center gap-3 min-w-[320px] max-w-md border-l-4 pointer-events-auto" id="toastContent">
        <div id="toastIcon" class="shrink-0"></div>
        <div class="flex-1">
          <p id="toastTitle" class="font-semibold text-sm"></p>
          <p id="toastMessage" class="text-xs mt-1 text-gray-600"></p>
        </div>
        <button onclick="hideToast()" class="text-gray-400 hover:text-gray-600">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
    </div>

    {{-- Confirmation Modal --}}
    <div id="confirmModal" style="display: none;" class="fixed inset-0 z-[120] flex items-center justify-center">
      <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
      <div class="relative bg-white rounded-lg shadow-2xl p-6 max-w-md w-full mx-4 transform transition-all">
        <div class="flex items-center gap-4 mb-4">
          <div class="shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
          </div>
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-900" id="confirmTitle">Konfirmasi Hapus</h3>
            <p class="text-sm text-gray-600 mt-1" id="confirmMessage">Apakah Anda yakin ingin menghapus data ini?</p>
          </div>
        </div>
        <div class="flex gap-3 justify-end">
          <button id="confirmCancel" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
            Batal
          </button>
          <button id="confirmOk" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
            Hapus
          </button>
        </div>
      </div>
    </div>

    {{-- Modal Form --}}
    <div id="modalBackdrop" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[110] transition-opacity duration-200 modal-backdrop-desktop"></div>
    <div id="modalForm" class="hidden fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-2xl z-[120] w-full max-w-lg mx-4 transition-all duration-200 max-h-[90vh] overflow-y-auto">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 id="modalTitle" class="text-2xl font-bold text-emerald-900">Tambah Surat Masuk</h2>
          <button id="closeModal" class="text-emerald-400 hover:text-emerald-600 text-2xl">×</button>
        </div>
        
        <form id="suratForm" class="space-y-4" enctype="multipart/form-data">
          <!-- Form fields unchanged -->
          <div>
            <label class="block text-sm font-medium text-emerald-700 mb-2">Nomor Surat <span class="text-red-500">*</span></label>
            <input type="text" id="formNomorSurat" placeholder="SM-2025/001" required class="w-full px-4 py-2 border border-emerald-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          
          <div>
            <label class="block text-sm font-medium text-emerald-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
            <input type="date" id="formTanggal" required class="w-full px-4 py-2 border border-emerald-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          
          <div>
            <label class="block text-sm font-medium text-emerald-700 mb-2">Pengirim <span class="text-red-500">*</span></label>
            <input type="text" id="formPengirim" placeholder="Nama Pengirim" required class="w-full px-4 py-2 border border-emerald-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          
          <div>
            <label class="block text-sm font-medium text-emerald-700 mb-2">Perihal <span class="text-red-500">*</span></label>
            <textarea id="formPerihal" placeholder="Perihal Surat" rows="3" required class="w-full px-4 py-2 border border-emerald-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-emerald-700 mb-2">File Surat (PDF)</label>
            <div class="relative">
              <input type="file" id="formFile" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx" class="w-full px-4 py-2 border border-emerald-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
            </div>
            <p class="text-xs text-emerald-500 mt-1">Format: PDF, PNG, JPG, DOC, DOCX (Max 5MB)</p>
            <div id="filePreview" class="hidden mt-2 p-2 bg-emerald-50 rounded-lg items-center gap-2">
              <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              <span id="fileName" class="text-sm text-emerald-700 truncate"></span>
              <button type="button" id="removeFile" class="ml-auto text-red-500 hover:text-red-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>
          </div>
          
          <div class="flex gap-3 pt-4">
            <button type="button" id="btnModalCancel" class="flex-1 px-4 py-2 border border-emerald-300 text-emerald-700 rounded-lg hover:bg-emerald-50 transition">Batal</button>
            <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

    {{-- Modal Preview Dokumen (COPIED FROM TRACKING) --}}
    <div id="previewModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-[130] p-4 transition-all duration-300">
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

  <script>
    // Preview Modal Functions
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
        if (extension && !previewable.includes(extension.toLowerCase())) {
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

    // Toast Notification Functions
    function showToast(title, message, type = 'success') {
      const toast = document.getElementById('toast');
      const toastContent = document.getElementById('toastContent');
      const toastIcon = document.getElementById('toastIcon');
      const toastTitle = document.getElementById('toastTitle');
      const toastMessage = document.getElementById('toastMessage');
      
      const icons = {
        success: '<svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        error: '<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        warning: '<svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
        info: '<svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
      };
      
      const colors = {
        success: 'border-green-500',
        error: 'border-red-500',
        warning: 'border-yellow-500',
        info: 'border-blue-500'
      };
      
      toastContent.className = 'bg-white rounded-lg shadow-2xl p-4 flex items-center gap-3 min-w-[320px] max-w-md border-l-4 ' + colors[type];
      toastIcon.innerHTML = icons[type];
      toastTitle.textContent = title;
      toastMessage.textContent = message;
      
      toast.classList.remove('hidden');
      setTimeout(() => toast.classList.add('translate-x-0'), 10);
      
      setTimeout(() => hideToast(), 3000);
    }
    
    function hideToast() {
      const toast = document.getElementById('toast');
      toast.classList.add('translate-x-[150%]');
      setTimeout(() => toast.classList.add('hidden'), 300);
    }

    // Custom Confirmation Dialog
    function showConfirm(title, message) {
      return new Promise((resolve) => {
        const confirmModal = document.getElementById('confirmModal');
        const confirmTitle = document.getElementById('confirmTitle');
        const confirmMessage = document.getElementById('confirmMessage');
        const confirmOk = document.getElementById('confirmOk');
        const confirmCancel = document.getElementById('confirmCancel');
        
        confirmTitle.textContent = title;
        confirmMessage.textContent = message;
        confirmModal.style.display = 'flex';
        
        const handleOk = () => {
          confirmModal.style.display = 'none';
          cleanup();
          resolve(true);
        };
        
        const handleCancel = () => {
          confirmModal.style.display = 'none';
          cleanup();
          resolve(false);
        };
        
        const cleanup = () => {
          confirmOk.removeEventListener('click', handleOk);
          confirmCancel.removeEventListener('click', handleCancel);
        };
        
        confirmOk.addEventListener('click', handleOk);
        confirmCancel.addEventListener('click', handleCancel);
      });
    }

    const sidebar = document.getElementById('sidebar');
    const main = document.getElementById('main');
    const btnCollapse = document.getElementById('btnCollapse');
    const btnOpenMobile = document.getElementById('btnOpenMobile');
    const mobileOverlay = document.getElementById('mobileOverlay');

    let collapsed = false;

    try {
      const saved = localStorage.getItem('sidebar.collapsed');
      if (saved === 'true') {
        collapsed = true;
        sidebar.classList.add('sidebar-collapsed');
      }
    } catch(e){ }

    function setMainClass() {
      if (!main) return;
      if (collapsed) {
        main.classList.remove('main-with-sidebar');
        main.classList.add('main-with-sidebar-collapsed');
      } else {
        main.classList.remove('main-with-sidebar-collapsed');
        main.classList.add('main-with-sidebar');
      }
    }
    setMainClass();

    if (btnCollapse) {
      btnCollapse.addEventListener('click', ()=>{
        collapsed = !collapsed;
        if (collapsed) {
          sidebar.classList.add('sidebar-collapsed');
        } else {
          sidebar.classList.remove('sidebar-collapsed');
        }
        try { localStorage.setItem('sidebar.collapsed', collapsed ? 'true' : 'false'); } catch(e){}
        setMainClass();
      });
    }

    function openMobile(){
      sidebar.classList.remove('sidebar-hidden-mobile');
      mobileOverlay.classList.remove('hidden');
    }
    function closeMobile(){
      sidebar.classList.add('sidebar-hidden-mobile');
      mobileOverlay.classList.add('hidden');
    }
    if (btnOpenMobile) btnOpenMobile.addEventListener('click', openMobile);
    if (mobileOverlay) mobileOverlay.addEventListener('click', closeMobile);

    sidebar.querySelectorAll('a').forEach(a=>{
      a.addEventListener('click', function(){
        if(window.innerWidth < 768) closeMobile();
      });
    });

    function onResize(){
      if(window.innerWidth >= 768) {
        sidebar.classList.remove('sidebar-hidden-mobile');
        mobileOverlay.classList.add('hidden');
      } else {
        sidebar.classList.add('sidebar-hidden-mobile');
      }
      setMainClass();
    }
    window.addEventListener('resize', onResize);
    // Tampilkan sidebar di desktop saat load pertama
    if(window.innerWidth >= 768) {
      sidebar.classList.remove('sidebar-hidden-mobile');
      mobileOverlay.classList.add('hidden');
    }

    // CRUD Functionality
    const tableBody = document.getElementById('tableBody');
    const btnTambah = document.getElementById('btnTambah');
    const searchInput = document.getElementById('searchInput');

    // Setup existing edit/delete buttons
    let allRowsData = [];

    // Load data dari server
    async function loadData() {
      try {
        const response = await fetch('/api/surat-masuk');
        const manualData = await response.json();
        
        // Merge with digital docs from controller
        const digitalData = @json($dokumenDigital ?? []).map(doc => ({
          id: 'digital_' + doc.id,
          nomor_surat: doc.nomor_dokumen,
          tanggal_diterima: doc.created_at.split('T')[0],
          pengirim: doc.user ? doc.user.name : 'Pusat/Staff', // Fallback name
          perihal: doc.judul + (doc.deskripsi ? ' - ' + doc.deskripsi : ''),
          file_url: '/storage/' + doc.file_path,
          is_digital: true, // Flag to identify
          status: 'Digital'
        }));

        allRowsData = [...digitalData, ...manualData];
        renderTable();
        updateCounters(); // New function call
      } catch (error) {
        console.error('Error loading data:', error);
      }
    }

    function updateCounters() {
        // Calculate counts
        const total = allRowsData.length;
        // Logic: 'Digital' or 'Selesai' considered 'processed'
        // 'Manual' (which is default if not specified) considered 'pending'? 
        // Or based on your specific logic. Assuming 'Digital' is automatically 'processed' or 'inbox'
        
        // For now, let's assume:
        // Digital = "Sudah Ditindaklanjuti" (Archived/Sent by staff)
        // Manual = "Belum Ditindaklanjuti" (Uploaded manually by unit, needs processing?)
        // OR better: Check if a 'status' field exists.
        
        const digitalCount = allRowsData.filter(d => d.is_digital).length;
        const manualCount = allRowsData.filter(d => !d.is_digital).length;

        document.getElementById('countTotal').textContent = total;
        document.getElementById('countPending').textContent = manualCount; // Assumption: Manual needs attention
        document.getElementById('countSelesai').textContent = digitalCount; // Assumption: Digital is likely 'done' validation
        
        // Update pagination text
        const paginationInfo = document.getElementById('paginationInfo');
        if(paginationInfo) {
            paginationInfo.textContent = `Menampilkan ${total} dari ${total} surat masuk`;
        }
    }

    // Render tabel dari data
    function renderTable() {
      // Remove skeleton rows
      const skeletonRows = tableBody.querySelectorAll('.skeleton-row');
      skeletonRows.forEach(row => row.remove());
      
      // Clear existing data rows (keep skeleton if any)
      const dataRows = tableBody.querySelectorAll('tr:not(.skeleton-row)');
      dataRows.forEach(row => row.remove());
      
      if (allRowsData.length === 0) {
          const emptyRow = document.createElement('tr');
          emptyRow.innerHTML = `
            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
               <div class="flex flex-col items-center justify-center">
                   <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                   <p>Belum ada data surat masuk</p>
               </div>
            </td>
          `;
          tableBody.appendChild(emptyRow);
          return;
      }

      allRowsData.forEach((item, index) => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50 transition-colors group border-b border-gray-50';
        row.dataset.id = item.id;
        
        // Mock / Derived Data
        const noAgenda = `SM/${new Date().getFullYear()}/12/${String(index + 1).padStart(4, '0')}`;
        const jenis = item.klasifikasi ? item.klasifikasi.nama : (item.is_digital ? 'Surat Digital' : 'Surat Masuk');
        const sifat = 'Biasa'; // Default mock
        const sifatColor = 'bg-gray-100 text-gray-600';

        // Action Buttons
        const viewBtn = item.file_url || item.file ? `
            <button onclick="${item.file_url ? `showPreviewModal('${item.file_url}', '${item.perihal || item.nomor_surat}', '${item.file_url.split('.').pop()}')` : `window.open('/storage/${item.file}', '_blank')`}" 
                    class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition" 
                    title="Lihat File">
               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>
        ` : `
            <button disabled class="p-1.5 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            </button>
        `;

        const editBtn = item.is_digital ? `
            <button disabled class="p-1.5 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed" title="Dokumen Pusat (Tidak dapat diedit)">
               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </button>
        ` : `
            <button class="p-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition btn-edit" title="Edit">
               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </button>
        `;

        const deleteBtn = item.is_digital ? `
            <button disabled class="p-1.5 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed" title="Dokumen Pusat (Tidak dapat dihapus)">
               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        ` : `
            <button class="p-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition btn-delete" title="Hapus">
               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        `;

        const actionButtons = `
            <div class="flex items-center justify-center gap-2">
               ${editBtn}
               ${viewBtn}
               ${deleteBtn}
            </div>
        `;

        row.innerHTML = `
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">${index + 1}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">${noAgenda}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${item.tanggal_diterima}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">${item.nomor_surat || '-'}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${item.pengirim}</td>
          <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="${item.perihal}">${item.perihal}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${jenis}</td>
          <td class="px-6 py-4 whitespace-nowrap">
             <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full ${sifatColor}">
               ${sifat}
             </span>
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
             ${actionButtons}
          </td>
        `;
        
        tableBody.appendChild(row);
        if (!item.is_digital) setupRowActions(row);
      });
    }

    function setupRowActions(row) {
      const editBtn = row.querySelector('.btn-edit');
      const deleteBtn = row.querySelector('.btn-delete');
      if (editBtn) editBtn.addEventListener('click', () => editRow(row));
      if (deleteBtn) deleteBtn.addEventListener('click', () => deleteRow(row));
    }

    // Modal handling
    const modalBackdrop = document.getElementById('modalBackdrop');
    const modalForm = document.getElementById('modalForm');
    const closeModalBtn = document.getElementById('closeModal');
    const btnModalCancel = document.getElementById('btnModalCancel');
    const suratForm = document.getElementById('suratForm');
    const formFile = document.getElementById('formFile');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const removeFileBtn = document.getElementById('removeFile');
    let isEditMode = false;
    let editingRowId = null;

    // File upload preview handling
    formFile.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        // Check file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
          showToast('File Terlalu Besar', 'Ukuran file maksimal 5MB', 'error');
          this.value = '';
          return;
        }
        fileName.textContent = file.name;
        filePreview.classList.remove('hidden');
        filePreview.classList.add('flex');
      } else {
        filePreview.classList.add('hidden');
        filePreview.classList.remove('flex');
      }
    });

    removeFileBtn.addEventListener('click', function() {
      formFile.value = '';
      filePreview.classList.add('hidden');
      filePreview.classList.remove('flex');
    });

    function openModal(isEdit = false, rowId = null) {
      isEditMode = isEdit;
      editingRowId = rowId;
      const modalTitle = document.getElementById('modalTitle');
      const formNomorSurat = document.getElementById('formNomorSurat');
      const formTanggal = document.getElementById('formTanggal');
      const formPengirim = document.getElementById('formPengirim');
      const formPerihal = document.getElementById('formPerihal');

      // Reset file input
      formFile.value = '';
      filePreview.classList.add('hidden');

      if (isEdit && rowId) {
        // Use loose equality (==) to match string "5" with number 5
        const item = allRowsData.find(d => d.id == rowId); 
        if (!item) {
            console.error('Data not found for ID:', rowId, allRowsData);
            alert('Error: Data tidak ditemukan. Silakan refresh halaman.');
            return;
        }
        modalTitle.textContent = 'Edit Surat Masuk';
        formNomorSurat.value = item.nomor_surat;
        formTanggal.value = item.tanggal_diterima;
        formPengirim.value = item.pengirim;
        formPerihal.value = item.perihal;
      } else {
        modalTitle.textContent = 'Tambah Surat Masuk';
        formNomorSurat.value = '';
        formTanggal.value = new Date().toISOString().slice(0, 10);
        formPengirim.value = '';
        formPerihal.value = '';
      }

      modalBackdrop.classList.remove('hidden');
      modalForm.classList.remove('hidden');
      formNomorSurat.focus();
    }

    function closeModal() {
      modalBackdrop.classList.add('hidden');
      modalForm.classList.add('hidden');
      suratForm.reset();
      filePreview.classList.add('hidden');
    }

    closeModalBtn.addEventListener('click', closeModal);
    btnModalCancel.addEventListener('click', closeModal);
    modalBackdrop.addEventListener('click', closeModal);

    // Tambah surat button
    btnTambah.addEventListener('click', () => {
      openModal(false);
    });

    // Form submission
    suratForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const formNomorSurat = document.getElementById('formNomorSurat');
      const formTanggal = document.getElementById('formTanggal');
      const formPengirim = document.getElementById('formPengirim');
      const formPerihal = document.getElementById('formPerihal');

      if (!formNomorSurat.value || !formTanggal.value || !formPengirim.value || !formPerihal.value) {
        showToast('Data Tidak Lengkap', 'Semua field wajib diisi', 'warning');
        return;
      }

      // Use FormData for file upload support
      const formData = new FormData();
      formData.append('nomor_surat', formNomorSurat.value);
      formData.append('tanggal_diterima', formTanggal.value);
      formData.append('pengirim', formPengirim.value);
      formData.append('perihal', formPerihal.value);
      
      // Add file if selected
      if (formFile.files[0]) {
        formData.append('file', formFile.files[0]);
      }

      try {
        if (isEditMode && editingRowId) {
          // Edit mode - use POST with _method=PUT for FormData
          formData.append('_method', 'PUT');
          const response = await fetch(`/api/surat-masuk/${editingRowId}`, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
              'Accept': 'application/json'
            },
            body: formData
          });

          const responseText = await response.text();
          let updatedData;
          try {
            updatedData = JSON.parse(responseText);
          } catch (e) {
            console.error('Response text:', responseText);
            showToast('Error Server', 'Respons server tidak valid', 'error');
            return;
          }

          if (response.ok) {
            const index = allRowsData.findIndex(item => item.id === editingRowId);
            if (index !== -1) {
              allRowsData[index] = updatedData;
            }
            renderTable();
            closeModal();
            showToast('Berhasil!', 'Data surat masuk berhasil diperbarui', 'success');
          } else {
            const errorMsg = updatedData.message || updatedData.errors || 'Gagal memperbarui data';
            showToast('Gagal Memperbarui', typeof errorMsg === 'object' ? JSON.stringify(errorMsg) : errorMsg, 'error');
          }
        } else {
          // Add mode
          const response = await fetch('/api/surat-masuk', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
              'Accept': 'application/json'
            },
            body: formData
          });

          const responseText = await response.text();
          let newData;
          try {
            newData = JSON.parse(responseText);
          } catch (e) {
            console.error('Response text:', responseText);
            showToast('Error Server', 'Respons server tidak valid', 'error');
            return;
          }

          if (response.ok) {
            allRowsData.unshift(newData);
            renderTable();
            closeModal();
            showToast('Berhasil!', 'Data surat masuk berhasil ditambahkan', 'success');
          } else {
            const errorMsg = newData.message || newData.errors || 'Gagal menyimpan data';
            showToast('Gagal Menyimpan', typeof errorMsg === 'object' ? JSON.stringify(errorMsg) : errorMsg, 'error');
          }
        }
      } catch (error) {
        console.error('Error:', error);
        showToast('Error', error.message, 'error');
      }
    });



    // Edit row - open modal
    function editRow(row) {
      const id = row.dataset.id;
      openModal(true, id);
    }

    // Hapus row
    async function deleteRow(row) {
      const id = parseInt(row.dataset.id);
      
      const confirmed = await showConfirm('Konfirmasi Hapus', 'Apakah Anda yakin ingin menghapus data ini? Data yang dihapus tidak dapat dikembalikan.');
      if (!confirmed) {
        return;
      }
      
      try {
        const response = await fetch(`/api/surat-masuk/${id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });
        
        const responseText = await response.text();
        let result;
        try {
          result = JSON.parse(responseText);
        } catch (e) {
          console.error('Response text:', responseText);
          showToast('Error Server', 'Respons server tidak valid', 'error');
          return;
        }
        
        if (response.ok) {
          allRowsData = allRowsData.filter(item => parseInt(item.id) !== id);
          renderTable();
          showToast('Berhasil!', 'Data berhasil dihapus', 'success');
        } else {
          const errorMsg = result.message || 'Gagal menghapus data';
          showToast('Gagal Menghapus', errorMsg, 'error');
        }
      } catch (error) {
        console.error('Error deleting:', error);
        showToast('Error', error.message, 'error');
      }
    }



    // Search
    searchInput.addEventListener('input', (e) => {
      const q = e.target.value.toLowerCase();
      document.querySelectorAll('#tableBody tr').forEach(row => {
        if (row.classList.contains('empty')) return;
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
      });
    });

    // Button Cetak Daftar
    const btnCetak = document.getElementById('btnCetak');
    btnCetak.addEventListener('click', () => {
      const printWindow = window.open('', '_blank');
      const htmlContent = `
        <html>
          <head>
            <title>Daftar Surat Masuk</title>
            <style>
              body { font-family: Arial, sans-serif; margin: 20px; }
              h1 { text-align: center; }
              table { width: 100%; border-collapse: collapse; margin-top: 20px; }
              th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
              th { background-color: #f2f2f2; }
            </style>
          </head>
          <body>
            <h1>Daftar Surat Masuk</h1>
            <p>Tanggal: ${new Date().toLocaleDateString('id-ID')}</p>
            <table>
              <tr>
                <th>No.</th>
                <th>Nomor Surat</th>
                <th>Tanggal</th>
                <th>Pengirim</th>
                <th>Perihal</th>
              </tr>
              ${allRowsData.map((item, i) => `
                <tr>
                  <td>${i + 1}</td>
                  <td>${item.nomor_surat}</td>
                  <td>${item.tanggal_diterima}</td>
                  <td>${item.pengirim}</td>
                  <td>${item.perihal}</td>
                </tr>
              `).join('')}
            </table>
          </body>
        </html>
      `;
      printWindow.document.write(htmlContent);
      printWindow.document.close();
      setTimeout(() => printWindow.print(), 250);
    });

    // Button Impor File
    const btnImpor = document.getElementById('btnImpor');
    btnImpor.addEventListener('click', () => {
      const input = document.createElement('input');
      input.type = 'file';
      input.accept = '.csv,.xlsx';
      input.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
          alert('File ' + file.name + ' dipilih. Fitur import sedang dalam proses.');
        }
      });
      input.click();
    });

    document.addEventListener('DOMContentLoaded', () => {
      loadData();
    });
  </script>
  @include('partials.scripts')
</body>
</html>




