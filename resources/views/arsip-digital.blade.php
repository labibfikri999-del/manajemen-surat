@php $user = auth()->user(); $role = $user->role ?? 'guest'; @endphp
{{-- resources/views/arsip-digital.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Arsip Digital — YARSI NTB</title>
  <link rel="icon" type="image/png" href="{{ asset('images/Logo Yayasan Bersih.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @include('partials.styles')
  <style>
    /* Animasi untuk update statistik */
    #statTotalDokumen, #statUkuranTotal, #statAksesTerakhir {
      transition: transform 0.3s ease, color 0.3s ease;
    }
    #statTotalDokumen.scale-110, #statUkuranTotal.scale-110, #statAksesTerakhir.scale-110 {
      transform: scale(1.1);
      color: #059669;
    }
  </style>
</head>
<body class="bg-gray-50">
  <div id="app" class="flex flex-col">
    @include('partials.header')

    @include('partials.sidebar-menu')

    <main id="main" class="transition-smooth main-with-sidebar flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
      @include('partials.flash-messages')
      <div class="max-w-7xl mx-auto">
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-emerald-900">Arsip Digital</h1>
          <p class="text-emerald-600 mt-2">Kelola dan cari dokumen dalam arsip digital</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 mb-6">
          <div class="bg-white rounded-lg shadow p-3 md:p-4">
            <div class="text-emerald-600 text-xs md:text-sm font-medium">Total Dokumen</div>
            <p id="statTotalDokumen" class="text-2xl md:text-3xl font-bold text-emerald-900 mt-2">{{ $totalArsip ?? 0 }}</p>
            <p class="text-xs text-emerald-500 mt-1">Terarsipkan</p>
          </div>
          <div class="bg-white rounded-lg shadow p-3 md:p-4">
            <div class="text-emerald-600 text-xs md:text-sm font-medium">Ukuran Total</div>
            <p id="statUkuranTotal" class="text-2xl md:text-3xl font-bold text-emerald-900 mt-2">{{ $totalSize ?? '0 B' }}</p>
            <p class="text-xs text-emerald-500 mt-1">Penyimpanan</p>
          </div>
          <div class="bg-white rounded-lg shadow p-3 md:p-4">
            <div class="text-emerald-600 text-xs md:text-sm font-medium">Akses Terakhir</div>
            <p id="statAksesTerakhir" class="text-lg md:text-xl font-bold text-emerald-900 mt-2">
              @if(!empty($lastAccess))
                {{ $lastAccess->diffForHumans() }}
              @else
                Belum ada data
              @endif
            </p>
            <p class="text-xs text-emerald-500 mt-1">Aktivitas</p>
          </div>
        </div>

        <div class="flex flex-col sm:flex-row flex-wrap gap-3 mb-6">
          <div id="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 w-full mb-2">
            <button onclick="goToRoot()" class="hover:text-emerald-600 font-medium">📁 Arsip Digital</button>
            <span id="breadcrumbPath"></span>
          </div>
          <button id="btnBack" class="hidden bg-gray-100 text-gray-700 px-4 py-2 rounded hover:bg-gray-200 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
          </button>
          <button id="btnUploadArsip" class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 flex items-center justify-center gap-2 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Upload File
          </button>
          <input id="searchInput" type="text" placeholder="Cari dokumen..." class="flex-1 sm:flex-initial px-4 py-2 border border-emerald-300 rounded focus:outline-none focus:border-emerald-500" aria-label="Cari dokumen" />
        </div>

        {{-- Folder Kategori --}}
        <div id="folderGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
          <div onclick="openFolder('UMUM')" class="folder-card bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-200 rounded-xl p-6 cursor-pointer hover:shadow-lg hover:border-blue-400 transition-all group">
            <div class="flex flex-col items-center text-center">
              <div class="w-16 h-16 bg-blue-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
              </div>
              <span class="font-bold text-blue-800">UMUM</span>
              <span class="text-xs text-blue-600 mt-1" id="countUMUM">0 dokumen</span>
            </div>
          </div>
          <div onclick="openFolder('SDM')" class="folder-card bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-200 rounded-xl p-6 cursor-pointer hover:shadow-lg hover:border-purple-400 transition-all group">
            <div class="flex flex-col items-center text-center">
              <div class="w-16 h-16 bg-purple-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>
              <span class="font-bold text-purple-800">SDM</span>
              <span class="text-xs text-purple-600 mt-1" id="countSDM">0 dokumen</span>
            </div>
          </div>
          <div onclick="openFolder('ASSET')" class="folder-card bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-200 rounded-xl p-6 cursor-pointer hover:shadow-lg hover:border-amber-400 transition-all group">
            <div class="flex flex-col items-center text-center">
              <div class="w-16 h-16 bg-amber-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
              </div>
              <span class="font-bold text-amber-800">ASSET</span>
              <span class="text-xs text-amber-600 mt-1" id="countASSET">0 dokumen</span>
            </div>
          </div>
          <div onclick="openFolder('HUKUM')" class="folder-card bg-gradient-to-br from-red-50 to-red-100 border-2 border-red-200 rounded-xl p-6 cursor-pointer hover:shadow-lg hover:border-red-400 transition-all group">
            <div class="flex flex-col items-center text-center">
              <div class="w-16 h-16 bg-red-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
              </div>
              <span class="font-bold text-red-800">HUKUM</span>
              <span class="text-xs text-red-600 mt-1" id="countHUKUM">0 dokumen</span>
            </div>
          </div>
          <div onclick="openFolder('KEUANGAN')" class="folder-card bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200 rounded-xl p-6 cursor-pointer hover:shadow-lg hover:border-green-400 transition-all group">
            <div class="flex flex-col items-center text-center">
              <div class="w-16 h-16 bg-green-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <span class="font-bold text-green-800">KEUANGAN</span>
              <span class="text-xs text-green-600 mt-1" id="countKEUANGAN">0 dokumen</span>
            </div>
          </div>
        </div>

        {{-- Document List (hidden by default, shown when folder is opened) --}}
        <div id="documentList" class="hidden">
          <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
              <h2 id="folderTitle" class="text-lg font-semibold text-gray-900">Dokumen</h2>
              <span id="docCount" class="text-sm text-gray-500"></span>
            </div>
            <div id="docTableContainer" class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokumen</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Arsip</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diproses Oleh</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                  </tr>
                </thead>
                <tbody id="docTableBody" class="bg-white divide-y divide-gray-200">
                  {{-- Documents will be loaded here --}}
                </tbody>
              </table>
            </div>
            <div id="emptyDocState" class="hidden p-8 text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              <p class="mt-2 text-gray-500">Belum ada dokumen dalam folder ini</p>
            </div>
          </div>
        </div>

        {{-- Legacy file grid (hidden) --}}
        <div id="fileGrid" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-4">
          {{-- Skeleton Loading --}}
          <div class="bg-white rounded-lg shadow p-4 animate-pulse skeleton-card">
            <div class="flex items-center justify-between mb-3">
              <div class="h-8 w-8 bg-emerald-200 rounded"></div>
              <div class="h-5 w-16 bg-emerald-200 rounded"></div>
            </div>
            <div class="h-4 bg-emerald-200 rounded w-3/4 mb-2"></div>
            <div class="h-3 bg-emerald-200 rounded w-1/2"></div>
            <div class="flex gap-2 mt-3">
              <div class="h-7 bg-emerald-200 rounded flex-1"></div>
              <div class="h-7 bg-emerald-200 rounded flex-1"></div>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow p-4 animate-pulse skeleton-card">
            <div class="flex items-center justify-between mb-3">
              <div class="h-8 w-8 bg-emerald-200 rounded"></div>
              <div class="h-5 w-16 bg-emerald-200 rounded"></div>
            </div>
            <div class="h-4 bg-emerald-200 rounded w-3/4 mb-2"></div>
            <div class="h-3 bg-emerald-200 rounded w-1/2"></div>
            <div class="flex gap-2 mt-3">
              <div class="h-7 bg-emerald-200 rounded flex-1"></div>
              <div class="h-7 bg-emerald-200 rounded flex-1"></div>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow p-4 animate-pulse skeleton-card">
            <div class="flex items-center justify-between mb-3">
              <div class="h-8 w-8 bg-emerald-200 rounded"></div>
              <div class="h-5 w-16 bg-emerald-200 rounded"></div>
            </div>
            <div class="h-4 bg-emerald-200 rounded w-3/4 mb-2"></div>
            <div class="h-3 bg-emerald-200 rounded w-1/2"></div>
            <div class="flex gap-2 mt-3">
              <div class="h-7 bg-emerald-200 rounded flex-1"></div>
              <div class="h-7 bg-emerald-200 rounded flex-1"></div>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow p-4 animate-pulse skeleton-card">
            <div class="flex items-center justify-between mb-3">
              <div class="h-8 w-8 bg-emerald-200 rounded"></div>
              <div class="h-5 w-16 bg-emerald-200 rounded"></div>
            </div>
            <div class="h-4 bg-emerald-200 rounded w-3/4 mb-2"></div>
            <div class="h-3 bg-emerald-200 rounded w-1/2"></div>
            <div class="flex gap-2 mt-3">
              <div class="h-7 bg-emerald-200 rounded flex-1"></div>
              <div class="h-7 bg-emerald-200 rounded flex-1"></div>
            </div>
          </div>
        </div>
      </div>
    </main>

    {{-- Toast Notification --}}
    <div id="toast" class="fixed top-24 right-4 z-[9999] transform translate-x-[150%] transition-all duration-500 ease-out pointer-events-none" style="display: none;">
      <div class="pointer-events-auto bg-white rounded-xl shadow-2xl p-5 flex items-start gap-4 min-w-[360px] max-w-md border-l-4 backdrop-blur-sm" id="toastContent">
        <div id="toastIcon" class="shrink-0 mt-0.5"></div>
        <div class="flex-1 min-w-0">
          <p id="toastTitle" class="font-bold text-base text-gray-900"></p>
          <p id="toastMessage" class="text-sm mt-1 text-gray-600 leading-relaxed"></p>
        </div>
        <button onclick="hideToast()" class="shrink-0 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full p-1 transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
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
            <p class="text-sm text-gray-600 mt-1" id="confirmMessage">Apakah Anda yakin ingin menghapus file ini?</p>
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

    {{-- Modal Form Upload --}}
    <div id="modalBackdrop" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[110] transition-opacity duration-200 modal-backdrop-desktop"></div>
    <div id="modalForm" class="hidden fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-2xl z-[120] w-full max-w-lg mx-4 transition-all duration-200 max-h-[90vh] overflow-y-auto">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 id="modalTitle" class="text-2xl font-bold text-emerald-900">Upload Dokumen</h2>
          <button id="closeModal" class="text-emerald-400 hover:text-emerald-600 text-2xl">×</button>
        </div>
        
        <form id="fileForm" class="space-y-4" enctype="multipart/form-data">
          <div>
            <label class="block text-sm font-medium text-emerald-700 mb-2">Nama Dokumen <span class="text-red-500">*</span></label>
            <input type="text" id="formNamaFile" placeholder="Nama dokumen" required class="w-full px-4 py-2 border border-emerald-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          
          <div>
            <label class="block text-sm font-medium text-emerald-700 mb-2">Kategori Arsip <span class="text-red-500">*</span></label>
            <select id="formKategoriArsip" required class="w-full px-4 py-2 border border-emerald-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Pilih Kategori</option>
              <option value="UMUM">📁 Umum</option>
              <option value="SDM">👥 SDM</option>
              <option value="ASSET">🏢 Asset</option>
              <option value="HUKUM">⚖️ Hukum</option>
              <option value="KEUANGAN">💰 Keuangan</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-emerald-700 mb-2">Deskripsi</label>
            <textarea id="formDeskripsi" rows="2" placeholder="Deskripsi dokumen (opsional)" class="w-full px-4 py-2 border border-emerald-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-emerald-700 mb-2">Upload File <span class="text-red-500">*</span></label>
            <div class="relative">
              <input type="file" id="formFile" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx,.xlsx,.xls" required class="w-full px-4 py-2 border border-emerald-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
            </div>
            <p class="text-xs text-emerald-500 mt-1">Format: PDF, PNG, JPG, DOC, DOCX, XLS, XLSX (Max 10MB)</p>
            <div id="filePreview" class="hidden mt-2 p-3 bg-emerald-50 rounded-lg">
              <div class="flex items-center gap-2">
                <svg id="fileIcon" class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <div class="flex-1 min-w-0">
                  <p id="fileName" class="text-sm font-medium text-emerald-700 truncate"></p>
                  <p id="fileSize" class="text-xs text-emerald-500"></p>
                </div>
                <button type="button" id="removeFile" class="text-red-500 hover:text-red-700 p-1">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
              </div>
            </div>
          </div>
          
          <div class="flex gap-3 pt-4">
            <button type="button" id="btnModalCancel" class="flex-1 px-4 py-2 border border-emerald-300 text-emerald-700 rounded-lg hover:bg-emerald-50 transition">Batal</button>
            <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">Upload</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Global Functions Script - Defined before DOMContentLoaded --}}
  <script>
    // Global state variables
    let currentFolder = null;
    let isArsipUploadMode = false;

    // ========== TOAST NOTIFICATION FUNCTIONS ==========
    function showToast(title, message, type = 'success') {
      const toast = document.getElementById('toast');
      const toastContent = document.getElementById('toastContent');
      const toastIcon = document.getElementById('toastIcon');
      const toastTitle = document.getElementById('toastTitle');
      const toastMessage = document.getElementById('toastMessage');
      
      const icons = {
        success: '<div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center"><svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>',
        error: '<div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center"><svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></div>',
        warning: '<div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center"><svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>',
        info: '<div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center"><svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>'
      };
      
      const colors = {
        success: 'border-green-500 bg-gradient-to-r from-green-50 to-white',
        error: 'border-red-500 bg-gradient-to-r from-red-50 to-white',
        warning: 'border-yellow-500 bg-gradient-to-r from-yellow-50 to-white',
        info: 'border-blue-500 bg-gradient-to-r from-blue-50 to-white'
      };
      
      toastContent.className = 'pointer-events-auto rounded-xl shadow-2xl p-5 flex items-start gap-4 min-w-[360px] max-w-md border-l-4 ' + colors[type];
      toastIcon.innerHTML = icons[type];
      toastTitle.textContent = title;
      toastMessage.textContent = message;
      
      // Show toast with animation
      toast.style.display = 'block';
      setTimeout(() => {
        toast.classList.remove('translate-x-[150%]');
        toast.classList.add('translate-x-0');
      }, 10);
      
      // Auto hide after 4 seconds
      setTimeout(() => hideToast(), 4000);
    }
    
    function hideToast() {
      const toast = document.getElementById('toast');
      toast.classList.remove('translate-x-0');
      toast.classList.add('translate-x-[150%]');
      setTimeout(() => {
        toast.style.display = 'none';
      }, 500);
    }

    // ========== LOAD STATISTICS ==========
    async function loadArsipStats() {
      try {
        const response = await fetch('/api/arsip-stats');
        const data = await response.json();
        
        // Update dengan animasi
        const totalEl = document.getElementById('statTotalDokumen');
        const ukuranEl = document.getElementById('statUkuranTotal');
        const aksesEl = document.getElementById('statAksesTerakhir');
        
        if (totalEl) {
          totalEl.classList.add('scale-110');
          totalEl.textContent = data.total_dokumen;
          setTimeout(() => totalEl.classList.remove('scale-110'), 300);
        }
        if (ukuranEl) {
          ukuranEl.classList.add('scale-110');
          ukuranEl.textContent = data.ukuran_total;
          setTimeout(() => ukuranEl.classList.remove('scale-110'), 300);
        }
        if (aksesEl) {
          aksesEl.classList.add('scale-110');
          aksesEl.textContent = data.akses_terakhir;
          setTimeout(() => aksesEl.classList.remove('scale-110'), 300);
        }
        
        console.log('[ARSIP] Stats updated:', data);
      } catch (error) {
        console.error('Error loading stats:', error);
      }
    }

    // Open folder - called from onclick
    async function openFolder(kategori) {
      console.log('[ARSIP] openFolder called with:', kategori);
      currentFolder = kategori;
      
      const folderGrid = document.getElementById('folderGrid');
      const documentList = document.getElementById('documentList');
      const btnBack = document.getElementById('btnBack');
      const breadcrumbPath = document.getElementById('breadcrumbPath');
      const folderTitle = document.getElementById('folderTitle');
      const docCount = document.getElementById('docCount');
      const docTableBody = document.getElementById('docTableBody');
      const emptyDocState = document.getElementById('emptyDocState');
      
      // Update UI
      folderGrid.classList.add('hidden');
      documentList.classList.remove('hidden');
      btnBack.classList.remove('hidden');
      btnBack.classList.add('flex');
      breadcrumbPath.innerHTML = '<span class="mx-2">/</span><span class="text-emerald-600 font-medium">' + kategori + '</span>';
      folderTitle.textContent = 'Folder ' + kategori;
      
      // Load documents
      try {
        const response = await fetch('/api/arsip-by-kategori/' + kategori);
        const dokumens = await response.json();
        
        docCount.textContent = dokumens.length + ' dokumen';
        
        if (dokumens.length === 0) {
          docTableBody.innerHTML = '';
          emptyDocState.classList.remove('hidden');
          document.getElementById('docTableContainer').classList.add('hidden');
        } else {
          emptyDocState.classList.add('hidden');
          document.getElementById('docTableContainer').classList.remove('hidden');
          renderDocuments(dokumens);
        }
      } catch (error) {
        console.error('Error loading documents:', error);
        showToast('Gagal Memuat', 'Tidak dapat memuat dokumen dari folder ini', 'error');
      }
    }

    // Render documents in table
    function renderDocuments(dokumens) {
      const docTableBody = document.getElementById('docTableBody');
      docTableBody.innerHTML = dokumens.map(function(dok) {
        const downloadPath = dok.file_pengganti_path || dok.file_path;
        const hasPengganti = dok.file_pengganti_path ? true : false;
        
        return '<tr class="hover:bg-gray-50">' +
          '<td class="px-6 py-4 whitespace-nowrap">' +
            '<div class="flex items-center">' +
              '<div class="flex-shrink-0 h-10 w-10 bg-emerald-100 rounded-lg flex items-center justify-center">' +
                '<svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                  '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>' +
                '</svg>' +
              '</div>' +
              '<div class="ml-4">' +
                '<div class="text-sm font-medium text-gray-900">' + dok.judul + '</div>' +
                '<div class="text-sm text-gray-500">' + (dok.nomor_dokumen || '-') + '</div>' +
                (hasPengganti ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">File Diproses Staff</span>' : '') +
              '</div>' +
            '</div>' +
          '</td>' +
          '<td class="px-6 py-4 whitespace-nowrap">' +
            '<span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">' + (dok.instansi ? dok.instansi.nama : 'N/A') + '</span>' +
          '</td>' +
          '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' +
            (dok.tanggal_arsip ? new Date(dok.tanggal_arsip).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-') +
          '</td>' +
          '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' +
            (dok.processor ? dok.processor.name : 'N/A') +
          '</td>' +
          '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">' +
            '<a href="/api/dokumen/' + dok.id + '/download" class="text-emerald-600 hover:text-emerald-900 mr-3 inline-flex items-center">' +
              '<svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>' +
              'Download' +
            '</a>' +
            (hasPengganti ? '<a href="/api/dokumen/' + dok.id + '/download?original=true" class="text-gray-600 hover:text-gray-900 text-xs"></a>' : '') +
          '</td>' +
        '</tr>';
      }).join('');
    }

    // Go back to root
    function goToRoot() {
      console.log('[ARSIP] goToRoot called');
      currentFolder = null;
      document.getElementById('folderGrid').classList.remove('hidden');
      document.getElementById('documentList').classList.add('hidden');
      document.getElementById('btnBack').classList.add('hidden');
      document.getElementById('btnBack').classList.remove('flex');
      document.getElementById('breadcrumbPath').innerHTML = '';
      loadFolderCounts();
    }

    // Load folder counts
    async function loadFolderCounts() {
      console.log('[ARSIP] loadFolderCounts called');
      try {
        const response = await fetch('/api/arsip-kategori-count');
        if (!response.ok) {
          throw new Error('API response not ok: ' + response.status);
        }
        const counts = await response.json();
        console.log('[ARSIP] Counts received:', counts);
        
        document.getElementById('countUMUM').textContent = counts.UMUM + ' dokumen';
        document.getElementById('countSDM').textContent = counts.SDM + ' dokumen';
        document.getElementById('countASSET').textContent = counts.ASSET + ' dokumen';
        document.getElementById('countHUKUM').textContent = counts.HUKUM + ' dokumen';
        document.getElementById('countKEUANGAN').textContent = counts.KEUANGAN + ' dokumen';
        console.log('[ARSIP] Folder counts updated successfully');
      } catch (error) {
        console.error('[ARSIP] Error loading folder counts:', error);
      }
    }

    // Open upload modal
    function openUploadModal() {
      console.log('[ARSIP] openUploadModal called');
      isArsipUploadMode = true;
      document.getElementById('modalTitle').textContent = 'Upload File ke Arsip Digital';
      document.getElementById('fileForm').reset();
      var filePreview = document.getElementById('filePreview');
      if (filePreview) filePreview.classList.add('hidden');
      document.getElementById('formNamaFile').placeholder = 'Judul dokumen';
      document.getElementById('modalForm').classList.remove('hidden');
      document.getElementById('modalBackdrop').classList.remove('hidden');
    }

    // Close modal
    function closeUploadModal() {
      document.getElementById('modalForm').classList.add('hidden');
      document.getElementById('modalBackdrop').classList.add('hidden');
      isArsipUploadMode = false;
    }

    // Initialize when DOM ready
    document.addEventListener('DOMContentLoaded', function() {
      console.log('[ARSIP] DOMContentLoaded - initializing...');
      
      // Load folder counts and stats on page load
      loadFolderCounts();
      loadArsipStats();
      
      // Refresh counts and stats every 30 seconds
      setInterval(function() {
        loadFolderCounts();
        loadArsipStats();
      }, 30000);
      
      // Button event listeners
      var btnUploadArsip = document.getElementById('btnUploadArsip');
      if (btnUploadArsip) {
        btnUploadArsip.addEventListener('click', openUploadModal);
      }
      
      var btnBack = document.getElementById('btnBack');
      if (btnBack) {
        btnBack.addEventListener('click', goToRoot);
      }
      
      var closeModalBtn = document.getElementById('closeModal');
      if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeUploadModal);
      }
      
      var modalBackdrop = document.getElementById('modalBackdrop');
      if (modalBackdrop) {
        modalBackdrop.addEventListener('click', closeUploadModal);
      }
      
      // File form submission
      var fileForm = document.getElementById('fileForm');
      if (fileForm) {
        fileForm.addEventListener('submit', async function(e) {
          e.preventDefault();
          
          if (!isArsipUploadMode) return;
          
          var formNamaFile = document.getElementById('formNamaFile');
          var formKategoriArsip = document.getElementById('formKategoriArsip');
          var formDeskripsi = document.getElementById('formDeskripsi');
          var formFile = document.getElementById('formFile');
          
          if (!formNamaFile.value || !formKategoriArsip.value || !formFile.files[0]) {
            showToast('Form Tidak Lengkap', 'Nama dokumen, kategori, dan file harus diisi!', 'warning');
            return;
          }
          
          var submitBtn = e.target.querySelector('button[type="submit"]');
          submitBtn.disabled = true;
          submitBtn.textContent = 'Mengunggah...';
          
          var uploadFormData = new FormData();
          uploadFormData.append('judul', formNamaFile.value);
          uploadFormData.append('kategori_arsip', formKategoriArsip.value);
          uploadFormData.append('deskripsi', formDeskripsi.value || '');
          uploadFormData.append('file', formFile.files[0]);
          
          try {
            var response = await fetch('/api/arsip-upload', {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
              },
              credentials: 'same-origin',
              body: uploadFormData
            });
            
            // Check if response is JSON
            var contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
              var text = await response.text();
              console.error('Non-JSON response:', text.substring(0, 200));
              throw new Error('Session expired. Silakan refresh halaman dan login kembali.');
            }
            
            var data = await response.json();
            
            if (response.ok) {
              showToast('Upload Berhasil! ✓', 'File berhasil diupload ke folder ' + formKategoriArsip.value, 'success');
              closeUploadModal();
              loadFolderCounts();
              loadArsipStats(); // Update statistik realtime
            } else {
              showToast('Upload Gagal', data.message || data.error || 'Gagal upload file', 'error');
            }
          } catch (error) {
            console.error('Error:', error);
            showToast('Terjadi Kesalahan', error.message, 'error');
          } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Upload';
          }
        });
      }
      
      console.log('[ARSIP] Initialization complete');
    });
  </script>

  <script>
    // Legacy script untuk backward compatibility
    document.addEventListener('DOMContentLoaded', function() {
    
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

    if (sidebar) {
      sidebar.querySelectorAll('a').forEach(a=>{
        a.addEventListener('click', function(){
          if(window.innerWidth < 768) closeMobile();
        });
      });
    }

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
    onResize();

    // Folder Navigation untuk Arsip Digital
    let currentFolder = null;
    const folderGrid = document.getElementById('folderGrid');
    const documentList = document.getElementById('documentList');
    const btnBack = document.getElementById('btnBack');
    const breadcrumbPath = document.getElementById('breadcrumbPath');
    const folderTitle = document.getElementById('folderTitle');
    const docCount = document.getElementById('docCount');
    const docTableBody = document.getElementById('docTableBody');
    const emptyDocState = document.getElementById('emptyDocState');
    const searchInput = document.getElementById('searchInput');

    // Load folder counts
    async function loadFolderCounts() {
      try {
        console.log('[' + new Date().toLocaleTimeString() + '] Fetching folder counts...');
        const response = await fetch('/api/arsip-kategori-count');
        if (!response.ok) {
          throw new Error('API response not ok: ' + response.status);
        }
        const counts = await response.json();
        console.log('[' + new Date().toLocaleTimeString() + '] Counts received:', counts);
        
        document.getElementById('countUMUM').textContent = counts.UMUM + ' dokumen';
        document.getElementById('countSDM').textContent = counts.SDM + ' dokumen';
        document.getElementById('countASSET').textContent = counts.ASSET + ' dokumen';
        document.getElementById('countHUKUM').textContent = counts.HUKUM + ' dokumen';
        document.getElementById('countKEUANGAN').textContent = counts.KEUANGAN + ' dokumen';
        console.log('[' + new Date().toLocaleTimeString() + '] Folder counts updated successfully');
      } catch (error) {
        console.error('[' + new Date().toLocaleTimeString() + '] Error loading folder counts:', error);
      }
    }

    // Open folder
    window.openFolder = async function(kategori) {
      currentFolder = kategori;
      
      // Update UI
      folderGrid.classList.add('hidden');
      documentList.classList.remove('hidden');
      btnBack.classList.remove('hidden');
      btnBack.classList.add('flex');
      breadcrumbPath.innerHTML = `<span class="mx-2">/</span><span class="text-emerald-600 font-medium">${kategori}</span>`;
      folderTitle.textContent = 'Folder ' + kategori;
      
      // Load documents
      try {
        const response = await fetch(`/api/arsip-by-kategori/${kategori}`);
        const dokumens = await response.json();
        
        docCount.textContent = dokumens.length + ' dokumen';
        
        if (dokumens.length === 0) {
          docTableBody.innerHTML = '';
          emptyDocState.classList.remove('hidden');
          document.getElementById('docTableContainer').classList.add('hidden');
        } else {
          emptyDocState.classList.add('hidden');
          document.getElementById('docTableContainer').classList.remove('hidden');
          renderDocuments(dokumens);
        }
      } catch (error) {
        console.error('Error loading documents:', error);
        showToast('Error', 'Gagal memuat dokumen', 'error');
      }
    }

    // Render documents table
    function renderDocuments(dokumens) {
      docTableBody.innerHTML = dokumens.map(dok => {
        // Prioritaskan file pengganti jika ada
        const downloadPath = dok.file_pengganti_path || dok.file_path;
        const fileName = dok.file_pengganti_name || dok.file_name;
        const fileType = dok.file_pengganti_type || dok.file_type;
        const fileLabel = dok.file_pengganti_path ? `${fileName} (Hasil Proses)` : fileName;
        
        return `
        <tr class="hover:bg-gray-50">
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
              <div class="flex-shrink-0 h-10 w-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">${dok.judul}</div>
                <div class="text-sm text-gray-500">${dok.nomor_dokumen || '-'}</div>
                ${dok.file_pengganti_path ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">File Diproses Staff</span>' : ''}
              </div>
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
              ${dok.instansi?.nama || 'N/A'}
            </span>
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            ${dok.tanggal_arsip ? new Date(dok.tanggal_arsip).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-'}
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            ${dok.processor?.name || 'N/A'}
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <a href="/storage/${downloadPath}" target="_blank" class="text-emerald-600 hover:text-emerald-900 mr-3 inline-flex items-center">
              <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
              </svg>
              Download
            </a>
            ${dok.file_pengganti_path ? `<a href="/storage/${dok.file_path}" target="_blank" class="text-gray-600 hover:text-gray-900 text-xs" title="Download file asli">File Asli</a>` : ''}
          </td>
        </tr>
      `;
      }).join('');
    }

    // Go back to root
    // Go back to root
    window.goToRoot = function() {
      currentFolder = null;
      folderGrid.classList.remove('hidden');
      documentList.classList.add('hidden');
      btnBack.classList.add('hidden');
      btnBack.classList.remove('flex');
      breadcrumbPath.innerHTML = '';
      loadFolderCounts();
    }

    // Event listeners
    btnBack.addEventListener('click', goToRoot);

    // Search functionality
    if (searchInput) {
      searchInput.addEventListener('input', async (e) => {
        const query = e.target.value.toLowerCase();
        
        if (currentFolder) {
          // Search within folder
          const response = await fetch(`/api/arsip-by-kategori/${currentFolder}`);
          const dokumens = await response.json();
          const filtered = dokumens.filter(d => 
            d.judul.toLowerCase().includes(query) || 
            (d.nomor_dokumen && d.nomor_dokumen.toLowerCase().includes(query))
          );
          renderDocuments(filtered);
        }
      });
    }

    // Initialize
    console.log('[' + new Date().toLocaleTimeString() + '] Initializing folder counts...');
    loadFolderCounts();
    
    // Also reload counts periodically
    setInterval(loadFolderCounts, 30000); // Refresh every 30 seconds

    // Upload Arsip Button
    const btnUploadArsip = document.getElementById('btnUploadArsip');
    console.log('[ARSIP] btnUploadArsip element:', btnUploadArsip);
    
    if (btnUploadArsip) {
      btnUploadArsip.addEventListener('click', () => {
        console.log('[ARSIP] Upload button clicked');
        isArsipUploadMode = true;
        document.getElementById('modalTitle').textContent = 'Upload File ke Arsip Digital';
        document.getElementById('fileForm').reset();
        document.getElementById('filePreview').classList.add('hidden');
        document.getElementById('formNamaFile').placeholder = 'Judul dokumen';
        document.getElementById('modalForm').classList.remove('hidden');
        document.getElementById('modalBackdrop').classList.remove('hidden');
        console.log('[ARSIP] Modal opened, isArsipUploadMode:', isArsipUploadMode);
      });
    } else {
      console.log('[ARSIP] btnUploadArsip not found!');
    }

    // Legacy CRUD untuk Arsip Digital (keep for backward compatibility)
    const btnTambah = document.getElementById('btnTambah');
    const fileGrid = document.getElementById('fileGrid');
    let allFilesData = [];

    const typeColors = {
      'PDF': { bg: 'bg-orange-100', text: 'text-orange-700', icon: 'text-orange-500' },
      'DOCX': { bg: 'bg-blue-100', text: 'text-blue-700', icon: 'text-blue-500' },
      'XLSX': { bg: 'bg-green-100', text: 'text-green-700', icon: 'text-green-500' },
      'FOLDER': { bg: 'bg-yellow-100', text: 'text-yellow-700', icon: 'text-yellow-600' }
    };

    async function loadData() {
      try {
        const response = await fetch('/api/arsip-digital');
        const data = await response.json();
        allFilesData = data;
        renderGrid();
      } catch (error) {
        console.error('Error:', error);
      }
    }

    function getTypeColor(tipe) {
      return typeColors[tipe] || typeColors['PDF'];
    }

    function renderGrid() {
      // Remove skeleton cards
      const skeletonCards = fileGrid.querySelectorAll('.skeleton-card');
      skeletonCards.forEach(card => card.remove());
      
      // Clear existing data cards
      const dataCards = fileGrid.querySelectorAll('div:not(.skeleton-card)');
      dataCards.forEach(card => card.remove());
      
      allFilesData.forEach((item) => {
        const colors = getTypeColor(item.tipe);
        const card = document.createElement('div');
        card.dataset.id = item.id;
        card.className = 'bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow';
        
        // File view button
        let viewBtn = '';
        if (item.file_url) {
          viewBtn = `<a href="${item.file_url}" target="_blank" class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded hover:bg-green-200">Lihat</a>`;
        }
        
        card.innerHTML = `
          <div class="flex items-center justify-between mb-3">
            <svg class="w-8 h-8 ${colors.icon}" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2-13H7c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
            <span class="text-xs font-semibold px-2 py-1 ${colors.bg} ${colors.text} rounded">${item.tipe}</span>
          </div>
          <p class="font-semibold text-emerald-900 truncate">${item.nama_dokumen || item.nama_file}</p>
          <p class="text-xs text-emerald-500 mt-1">${item.ukuran} • ${item.tanggal_upload}</p>
          ${item.kategori ? `<p class="text-xs text-emerald-400 mt-1">Kategori: ${item.kategori}</p>` : ''}
          <div class="flex gap-2 mt-3">
            ${viewBtn}
            <button class="inline-flex items-center gap-1 text-xs font-medium bg-emerald-600 text-white px-3 py-1.5 rounded-lg hover:bg-emerald-700 transition btn-edit">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              Edit
            </button>
            <button class="inline-flex items-center gap-1 text-xs font-medium bg-red-600 text-white px-3 py-1.5 rounded-lg hover:bg-red-700 transition btn-delete">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              Hapus
            </button>
          </div>
        `;
        fileGrid.appendChild(card);
        
        const editBtn = card.querySelector('.btn-edit');
        const deleteBtn = card.querySelector('.btn-delete');
        if (editBtn) editBtn.addEventListener('click', () => editFile(card, item));
        if (deleteBtn) deleteBtn.addEventListener('click', () => deleteFile(card, item));
      });
    }

    // Modal handling
    const modalBackdrop = document.getElementById('modalBackdrop');
    const modalForm = document.getElementById('modalForm');
    const closeModalBtn = document.getElementById('closeModal');
    const btnModalCancel = document.getElementById('btnModalCancel');
    const fileForm = document.getElementById('fileForm');
    const formFile = document.getElementById('formFile');
    const filePreview = document.getElementById('filePreview');
    const fileNameEl = document.getElementById('fileName');
    const fileSizeEl = document.getElementById('fileSize');
    const removeFileBtn = document.getElementById('removeFile');
    let isEditMode = false;
    let editingFileId = null;

    // File upload preview handling
    if (formFile) {
      formFile.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        // Check file size (max 10MB)
        if (file.size > 10 * 1024 * 1024) {
          showToast('File Terlalu Besar', 'Ukuran file maksimal 10MB', 'error');
          this.value = '';
          return;
        }
        fileNameEl.textContent = file.name;
        fileSizeEl.textContent = formatFileSize(file.size);
        filePreview.classList.remove('hidden');
        
        // Auto-fill nama dokumen if empty
        const formNamaFile = document.getElementById('formNamaFile');
        if (!formNamaFile.value) {
          formNamaFile.value = file.name.replace(/\.[^/.]+$/, ""); // Remove extension
        }
      } else {
        filePreview.classList.add('hidden');
      }
    });
    }

    function formatFileSize(bytes) {
      if (bytes === 0) return '0 Bytes';
      const k = 1024;
      const sizes = ['Bytes', 'KB', 'MB', 'GB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    if (removeFileBtn) {
      removeFileBtn.addEventListener('click', function() {
        if (formFile) formFile.value = '';
        if (filePreview) filePreview.classList.add('hidden');
      });
    }

    function openModal(isEdit = false, fileId = null) {
      isEditMode = isEdit;
      editingFileId = fileId;
      const modalTitle = document.getElementById('modalTitle');
      const formNamaFile = document.getElementById('formNamaFile');
      const formTipeFile = document.getElementById('formTipeFile');
      const formDeskripsi = document.getElementById('formDeskripsi');

      // Reset file input
      formFile.value = '';
      filePreview.classList.add('hidden');

      if (isEdit && fileId) {
        const item = allFilesData.find(d => d.id === fileId);
        modalTitle.textContent = 'Edit Dokumen';
        formNamaFile.value = item.nama_dokumen || item.nama_file;
        formTipeFile.value = item.kategori || '';
        formDeskripsi.value = item.deskripsi || '';
        formFile.required = false; // File not required when editing
      } else {
        modalTitle.textContent = 'Upload Dokumen';
        formNamaFile.value = '';
        formTipeFile.value = '';
        formDeskripsi.value = '';
        formFile.required = true;
      }

      modalBackdrop.classList.remove('hidden');
      modalForm.classList.remove('hidden');
      formNamaFile.focus();
    }

    function closeModal() {
      if (modalBackdrop) modalBackdrop.classList.add('hidden');
      if (modalForm) modalForm.classList.add('hidden');
      if (fileForm) fileForm.reset();
      if (filePreview) filePreview.classList.add('hidden');
    }

    if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
    if (btnModalCancel) btnModalCancel.addEventListener('click', closeModal);
    if (modalBackdrop) modalBackdrop.addEventListener('click', closeModal);

    if (btnTambah) {
      btnTambah.addEventListener('click', () => {
        openModal(false);
      });
    }

    // Arsip Upload Handler - untuk upload file dari halaman arsip digital
    let isArsipUploadMode = false;
    
    if (fileForm) {
      fileForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Handle Arsip Digital Upload
        if (isArsipUploadMode) {
          const formNamaFile = document.getElementById('formNamaFile');
          const formKategoriArsip = document.getElementById('formKategoriArsip');
          const formDeskripsi = document.getElementById('formDeskripsi');
          const formFile = document.getElementById('formFile');
          
          if (!formNamaFile.value || !formKategoriArsip.value || !formFile.files[0]) {
            showToast('❌ Error', 'Nama, kategori, dan file harus diisi!', 'error');
            return;
          }
          
          const submitBtn = e.target.querySelector('button[type="submit"]');
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<svg class="animate-spin inline-block w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengunggah...';
          
          const uploadFormData = new FormData();
          uploadFormData.append('judul', formNamaFile.value);
          uploadFormData.append('kategori_arsip', formKategoriArsip.value);
          uploadFormData.append('deskripsi', formDeskripsi.value || '');
          uploadFormData.append('file', formFile.files[0]);
          
          try {
            const response = await fetch('/api/arsip-upload', {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
              },
              credentials: 'same-origin',
              body: uploadFormData
            });
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
              const text = await response.text();
              console.error('Non-JSON response:', text.substring(0, 200));
              throw new Error('Session expired. Silakan refresh halaman dan login kembali.');
            }
            
            const data = await response.json();
            
            if (response.ok) {
              showToast('✓ Sukses', 'File berhasil diupload ke folder ' + formKategoriArsip.value, 'success');
              document.getElementById('modalForm').classList.add('hidden');
              document.getElementById('modalBackdrop').classList.add('hidden');
              loadFolderCounts(); // Refresh counts
              loadArsipStats(); // Update statistik realtime
            } else {
              showToast('❌ Error', data.message || data.error || 'Gagal upload file', 'error');
            }
          } catch (error) {
            console.error('Error:', error);
            showToast('❌ Error', error.message, 'error');
          } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Upload';
            isArsipUploadMode = false;
          }
          return;
        }
        
        // Legacy CRUD mode (existing code)
        const formNamaFile = document.getElementById('formNamaFile');
        const formTipeFile = document.getElementById('formTipeFile');
        const formDeskripsi = document.getElementById('formDeskripsi');

        if (!formNamaFile.value) {
          showToast('Form Tidak Lengkap', 'Nama dokumen harus diisi!', 'warning');
          return;
        }
        
        // Check if file is required (when adding new)
        if (!isEditMode && !formFile.files[0]) {
          showToast('File Belum Dipilih', 'Silakan pilih file untuk diupload!', 'warning');
          return;
        }

        // Use FormData for file upload
        const formData = new FormData();
        formData.append('nama_dokumen', formNamaFile.value);
        formData.append('kategori', formTipeFile.value || '');
      formData.append('deskripsi', formDeskripsi.value || '');
      
      // Add file if selected
      if (formFile.files[0]) {
        formData.append('file', formFile.files[0]);
      }

      try {
        if (isEditMode && editingFileId) {
          // Edit mode - use POST with _method=PUT for FormData
          formData.append('_method', 'PUT');
          const response = await fetch(`/api/arsip-digital/${editingFileId}`, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
            const index = allFilesData.findIndex(item => item.id === editingFileId);
            if (index !== -1) allFilesData[index] = updatedData;
            closeModal();
            renderGrid();
            showToast('Berhasil!', 'File berhasil diperbarui', 'success');
          } else {
            showToast('Gagal Memperbarui', updatedData.message || 'Gagal memperbarui file', 'error');
          }
        } else {
          const response = await fetch('/api/arsip-digital', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
            allFilesData.unshift(newData);
            closeModal();
            renderGrid();
            showToast('Berhasil!', 'File berhasil diupload', 'success');
          } else {
            showToast('Gagal Upload', newData.message || 'Gagal mengupload file', 'error');
          }
        }
      } catch (error) {
        console.error('Error:', error);
        showToast('Error', error.message, 'error');
      }
      });
    }
      try {
        const response = await fetch('/api/arsip-digital', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            nama_file: namaFile,
            tipe: tipe
          })
        });
        if (response.ok) {
          const newData = await response.json();
          allFilesData.unshift(newData);
          renderGrid();
          showToast('Upload Berhasil! ✓', 'File berhasil diupload ke arsip digital', 'success');
        }
      } catch (error) {
        showToast('Terjadi Kesalahan', error.message, 'error');
      }
    }

    async function editFile(card, item) {
      openModal(true, item.id);
    }

    async function deleteFile(card, item) {
      const confirmed = await showConfirm('Konfirmasi Hapus', 'Apakah Anda yakin ingin menghapus file ini? File yang dihapus tidak dapat dikembalikan.');
      if (!confirmed) return;
      
      const id = parseInt(item.id);
      
      try {
        const response = await fetch(`/api/arsip-digital/${id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });
        if (response.ok) {
          allFilesData = allFilesData.filter(f => parseInt(f.id) !== id);
          renderGrid();
          showToast('Berhasil!', 'File berhasil dihapus', 'success');
        }
      } catch (error) {
        console.error('Error deleting:', error);
        showToast('Error', error.message, 'error');
      }
    }

    if (searchInput) {
      searchInput.addEventListener('input', (e) => {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('#fileGrid > div').forEach(card => {
          const text = card.textContent.toLowerCase();
          card.style.display = text.includes(q) ? '' : 'none';
        });
      });
    }

    // Button Buat Folder
    const btnFolder = document.getElementById('btnFolder');
    if (btnFolder) {
      btnFolder.addEventListener('click', () => {
        const folderName = prompt('Nama Folder:');
        if (folderName) {
          saveNewFile(folderName, 'FOLDER');
        }
      });
    }

    // Legacy loadData disabled - using new folder system
    // loadData();
    
    }); // End of DOMContentLoaded
  </script>
  @include('partials.scripts')
</body>
</html>





