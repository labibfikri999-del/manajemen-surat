{{-- resources/views/arsip-digital.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Arsip Digital — Arsiparis</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root{
      --header-h: 64px;
      --sidebar-w: 16rem;
      --sidebar-collapsed-w: 4.5rem;
    }
    html,body,#app { height: 100%; }
    .transition-smooth { transition: all .22s cubic-bezier(.2,.8,.2,1); }
    html, body { overflow-x: hidden; }
    .site-header { height: var(--header-h); position: sticky; top: 0; z-index: 100; }
    .sidebar {
      width: var(--sidebar-w);
      min-width: var(--sidebar-w);
      transition: width .25s ease, transform .25s ease;
      position: fixed;
      top: var(--header-h);
      left: 0;
      bottom: 0;
      z-index: 50;
      overflow-y: auto;
      background: white;
    }
    .sidebar-collapsed { width: var(--sidebar-collapsed-w) !important; min-width: var(--sidebar-collapsed-w) !important; }
    .sidebar.sidebar-collapsed .nav-label,
    .sidebar.sidebar-collapsed .sidebar-brand-text { display: none !important; }
    .sidebar.sidebar-collapsed .nav-item { justify-content: center; }
    @media (max-width: 767.98px) { 
      .sidebar { transform: translateX(-100%); z-index: 60; }
      .sidebar-hidden-mobile { transform: translateX(-100%) !important; }
      .sidebar:not(.sidebar-hidden-mobile) { transform: translateX(0) !important; }
      .sidebar { width: var(--sidebar-w) !important; min-width: var(--sidebar-w) !important; } 
    }
    @media (min-width: 768px) { .sidebar { transform: translateX(0) !important; } }
    .main-with-sidebar { margin-left: var(--sidebar-w); }
    .main-with-sidebar-collapsed { margin-left: var(--sidebar-collapsed-w); }
    @media (max-width: 767.98px) { 
      .main-with-sidebar { margin-left: 0 !important; }
      .main-with-sidebar-collapsed { margin-left: 0 !important; }
    }
    .mobile-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,.5); z-index: 55; }
    @media (min-width: 768px) { .sidebar { transform: translateX(0) !important; } }
    @media (max-width: 767.98px) { .sidebar { transform: translateX(-100%); } }
    .nav-item.active { background: #f0f9ff; color: #0369a1; }
    .nav-item:hover { background: #f0f9ff; }
    .tooltip-text { position: absolute; left: 100%; top: 50%; transform: translateY(-50%); background: #0c4a6e; color: white; padding: 0.5rem 0.75rem; border-radius: 0.375rem; white-space: nowrap; opacity: 0; pointer-events: none; transition: opacity .2s; margin-left: 0.5rem; }
    .tooltip.show-tooltip .tooltip-text { opacity: 1; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    /* Pastikan backdrop tidak menutupi sidebar di desktop */
    @media (min-width: 768px) {
      .modal-backdrop-desktop { left: var(--sidebar-w) !important; }
    }
  </style>
</head>
<body class="bg-sky-50">
  <div id="app" class="flex flex-col">
    <header class="site-header bg-white border-b border-sky-100 flex items-center justify-between px-4 md:px-6 lg:px-8 shadow-sm">
      <div class="flex items-center gap-4">
        <button id="btnOpenMobile" class="md:hidden text-sky-700">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="flex items-center gap-2">
          <svg class="w-8 h-8 text-sky-600" fill="currentColor" viewBox="0 0 24 24"><path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M3 7a2 2 0 012-2h14a2 2 0 012 2m0 0V5a2 2 0 00-2-2H5a2 2 0 00-2 2v2m0 0h16"/></svg>
          <span class="text-xl font-bold text-sky-700">Arsiparis</span>
        </div>
      </div>
      <div class="text-sm text-sky-600">Arsip Digital</div>
    </header>

    <aside id="sidebar" class="sidebar sidebar-hidden-mobile border-r border-sky-100">
      <div class="p-4">
        <button id="btnCollapse" class="hidden md:flex w-full items-center justify-center mb-4 p-2 rounded hover:bg-sky-50 text-sky-700">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>

        <nav class="mt-5">
          <ul class="space-y-1">
            <li><a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9.75L12 3l9 6.75V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V9.75z"/></svg><span class="nav-label text-sm font-medium">Dashboard</span><span class="tooltip-text">Dashboard</span></a></li>
            <li><a href="{{ route('surat-masuk') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8.5A2.5 2.5 0 015.5 6h13A2.5 2.5 0 0121 8.5v7A2.5 2.5 0 0118.5 18h-13A2.5 2.5 0 013 15.5v-7zM3 8.5l7 4 7-4"/></svg><span class="nav-label text-sm">Surat Masuk</span><span class="tooltip-text">Surat Masuk</span></a></li>
            <li><a href="{{ route('surat-keluar') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2 12l18-7-7 18-3-8-8-3z"/></svg><span class="nav-label text-sm">Surat Keluar</span><span class="tooltip-text">Surat Keluar</span></a></li>
            <li><a href="#" class="flex items-center gap-3 p-2 rounded-md nav-item active tooltip relative"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7h18M8 7v-2a1 1 0 011-1h6a1 1 0 011 1v2M21 7l-1 13a2 2 0 01-2 2H6a2 2 0 01-2-2L3 7"/></svg><span class="nav-label text-sm">Arsip Digital</span><span class="tooltip-text">Arsip Digital</span></a></li>
            <li><a href="{{ route('laporan') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3v18h18M9 17V9M13 17V5M17 17v-4"/></svg><span class="nav-label text-sm">Laporan</span><span class="tooltip-text">Laporan</span></a></li>
            <li><a href="{{ route('data-master') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 2C7.6 2 4 3.8 4 6v12c0 2.2 3.6 4 8 4s8-1.8 8-4V6c0-2.2-3.6-4-8-4zM4 10c0 2.2 3.6 4 8 4s8-1.8 8-4"/></svg><span class="nav-label text-sm">Data Master</span><span class="tooltip-text">Data Master</span></a></li>
          </ul>
        </nav>

        <div class="sidebar-footer">
          <div class="mt-6 border-t pt-4">
            <div class="text-xs text-sky-600 mb-2 sidebar-brand-text">Admin</div>
            <button type="button" class="logout-btn w-full flex items-center gap-3 px-3 py-2 rounded-md border border-sky-100 hover:bg-sky-50 transition-smooth text-sky-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" /></svg>
              <span class="logout-label nav-label text-sm font-medium text-sky-700">Logout</span>
            </button>
          </div>
        </div>
      </div>
    </aside>

    <div id="mobileOverlay" class="mobile-overlay hidden"></div>

    <main id="main" class="transition-smooth main-with-sidebar flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
      <div class="max-w-7xl mx-auto">
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-sky-900">Arsip Digital</h1>
          <p class="text-sky-600 mt-2">Kelola dan cari dokumen dalam arsip digital</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 mb-6">
          <div class="bg-white rounded-lg shadow p-3 md:p-4">
            <div class="text-sky-600 text-xs md:text-sm font-medium">Total Dokumen</div>
            <p class="text-2xl md:text-3xl font-bold text-sky-900 mt-2">211</p>
            <p class="text-xs text-sky-500 mt-1">Terarsipkan</p>
          </div>
          <div class="bg-white rounded-lg shadow p-3 md:p-4">
            <div class="text-sky-600 text-xs md:text-sm font-medium">Ukuran Total</div>
            <p class="text-2xl md:text-3xl font-bold text-sky-900 mt-2">2.3 GB</p>
            <p class="text-xs text-sky-500 mt-1">Penyimpanan</p>
          </div>
          <div class="bg-white rounded-lg shadow p-3 md:p-4">
            <div class="text-sky-600 text-xs md:text-sm font-medium">Akses Terakhir</div>
            <p class="text-lg md:text-xl font-bold text-sky-900 mt-2">5 menit lalu</p>
            <p class="text-xs text-sky-500 mt-1">Aktivitas</p>
          </div>
        </div>

        <div class="flex flex-col sm:flex-row flex-wrap gap-3 mb-6">
          <button id="btnTambah" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 flex items-center justify-center gap-2" aria-label="Upload Dokumen Baru">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Upload Dokumen
          </button>
          <button id="btnFolder" class="border border-sky-300 text-sky-700 px-4 py-2 rounded hover:bg-sky-50 flex items-center justify-center gap-2" aria-label="Buat Folder Baru">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m4-3H8"/></svg>
            Buat Folder
          </button>
          <input id="searchInput" type="text" placeholder="Cari dokumen..." class="flex-1 sm:flex-initial sm:ml-auto px-4 py-2 border border-sky-300 rounded focus:outline-none focus:border-sky-500" aria-label="Cari dokumen" />
        </div>

        <div id="fileGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-4">
          {{-- Skeleton Loading --}}
          <div class="bg-white rounded-lg shadow p-4 animate-pulse skeleton-card">
            <div class="flex items-center justify-between mb-3">
              <div class="h-8 w-8 bg-sky-200 rounded"></div>
              <div class="h-5 w-16 bg-sky-200 rounded"></div>
            </div>
            <div class="h-4 bg-sky-200 rounded w-3/4 mb-2"></div>
            <div class="h-3 bg-sky-200 rounded w-1/2"></div>
            <div class="flex gap-2 mt-3">
              <div class="h-7 bg-sky-200 rounded flex-1"></div>
              <div class="h-7 bg-sky-200 rounded flex-1"></div>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow p-4 animate-pulse skeleton-card">
            <div class="flex items-center justify-between mb-3">
              <div class="h-8 w-8 bg-sky-200 rounded"></div>
              <div class="h-5 w-16 bg-sky-200 rounded"></div>
            </div>
            <div class="h-4 bg-sky-200 rounded w-3/4 mb-2"></div>
            <div class="h-3 bg-sky-200 rounded w-1/2"></div>
            <div class="flex gap-2 mt-3">
              <div class="h-7 bg-sky-200 rounded flex-1"></div>
              <div class="h-7 bg-sky-200 rounded flex-1"></div>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow p-4 animate-pulse skeleton-card">
            <div class="flex items-center justify-between mb-3">
              <div class="h-8 w-8 bg-sky-200 rounded"></div>
              <div class="h-5 w-16 bg-sky-200 rounded"></div>
            </div>
            <div class="h-4 bg-sky-200 rounded w-3/4 mb-2"></div>
            <div class="h-3 bg-sky-200 rounded w-1/2"></div>
            <div class="flex gap-2 mt-3">
              <div class="h-7 bg-sky-200 rounded flex-1"></div>
              <div class="h-7 bg-sky-200 rounded flex-1"></div>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow p-4 animate-pulse skeleton-card">
            <div class="flex items-center justify-between mb-3">
              <div class="h-8 w-8 bg-sky-200 rounded"></div>
              <div class="h-5 w-16 bg-sky-200 rounded"></div>
            </div>
            <div class="h-4 bg-sky-200 rounded w-3/4 mb-2"></div>
            <div class="h-3 bg-sky-200 rounded w-1/2"></div>
            <div class="flex gap-2 mt-3">
              <div class="h-7 bg-sky-200 rounded flex-1"></div>
              <div class="h-7 bg-sky-200 rounded flex-1"></div>
            </div>
          </div>
        </div>
      </div>
    </main>

    {{-- Toast Notification --}}
    <div id="toast" class="hidden fixed top-4 right-4 z-50 transform transition-all duration-300 ease-out">
      <div class="bg-white rounded-lg shadow-2xl p-4 flex items-center gap-3 min-w-[320px] max-w-md border-l-4" id="toastContent">
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
    <div id="confirmModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center">
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
    <div id="modalBackdrop" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-200 modal-backdrop-desktop"></div>
    <div id="modalForm" class="hidden fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-2xl z-50 w-full max-w-lg mx-4 transition-all duration-200 max-h-[90vh] overflow-y-auto">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 id="modalTitle" class="text-2xl font-bold text-sky-900">Upload Dokumen</h2>
          <button id="closeModal" class="text-sky-400 hover:text-sky-600 text-2xl">×</button>
        </div>
        
        <form id="fileForm" class="space-y-4" enctype="multipart/form-data">
          <div>
            <label class="block text-sm font-medium text-sky-700 mb-2">Nama Dokumen <span class="text-red-500">*</span></label>
            <input type="text" id="formNamaFile" placeholder="Nama dokumen" required class="w-full px-4 py-2 border border-sky-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          </div>
          
          <div>
            <label class="block text-sm font-medium text-sky-700 mb-2">Kategori <span class="text-red-500">*</span></label>
            <select id="formTipeFile" required class="w-full px-4 py-2 border border-sky-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent">
              <option value="">Pilih Kategori</option>
              <option value="Surat Masuk">Surat Masuk</option>
              <option value="Surat Keluar">Surat Keluar</option>
              <option value="SK">Surat Keputusan</option>
              <option value="Laporan">Laporan</option>
              <option value="Dokumen Lainnya">Dokumen Lainnya</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-sky-700 mb-2">Deskripsi</label>
            <textarea id="formDeskripsi" rows="2" placeholder="Deskripsi dokumen (opsional)" class="w-full px-4 py-2 border border-sky-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent"></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-sky-700 mb-2">Upload File <span class="text-red-500">*</span></label>
            <div class="relative">
              <input type="file" id="formFile" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx,.xlsx,.xls" required class="w-full px-4 py-2 border border-sky-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-sky-100 file:text-sky-700 hover:file:bg-sky-200">
            </div>
            <p class="text-xs text-sky-500 mt-1">Format: PDF, PNG, JPG, DOC, DOCX, XLS, XLSX (Max 10MB)</p>
            <div id="filePreview" class="hidden mt-2 p-3 bg-sky-50 rounded-lg">
              <div class="flex items-center gap-2">
                <svg id="fileIcon" class="w-8 h-8 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <div class="flex-1 min-w-0">
                  <p id="fileName" class="text-sm font-medium text-sky-700 truncate"></p>
                  <p id="fileSize" class="text-xs text-sky-500"></p>
                </div>
                <button type="button" id="removeFile" class="text-red-500 hover:text-red-700 p-1">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
              </div>
            </div>
          </div>
          
          <div class="flex gap-3 pt-4">
            <button type="button" id="btnModalCancel" class="flex-1 px-4 py-2 border border-sky-300 text-sky-700 rounded-lg hover:bg-sky-50 transition">Batal</button>
            <button type="submit" class="flex-1 px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition">Upload</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
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
    onResize();

    sidebar.querySelectorAll('.tooltip').forEach(item=>{
      item.addEventListener('mouseenter', ()=>{
        if (sidebar.classList.contains('sidebar-collapsed') && window.innerWidth >= 768) {
          item.classList.add('show-tooltip');
        }
      });
      item.addEventListener('mouseleave', ()=>{
        item.classList.remove('show-tooltip');
      });
    });

    // CRUD untuk Arsip Digital
    const btnTambah = document.getElementById('btnTambah');
    const fileGrid = document.getElementById('fileGrid');
    const searchInput = document.getElementById('searchInput');
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
          <p class="font-semibold text-sky-900 truncate">${item.nama_dokumen || item.nama_file}</p>
          <p class="text-xs text-sky-500 mt-1">${item.ukuran} • ${item.tanggal_upload}</p>
          ${item.kategori ? `<p class="text-xs text-sky-400 mt-1">Kategori: ${item.kategori}</p>` : ''}
          <div class="flex gap-2 mt-3">
            ${viewBtn}
            <button class="inline-flex items-center gap-1 text-xs font-medium bg-sky-600 text-white px-3 py-1.5 rounded-lg hover:bg-sky-700 transition btn-edit">
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

    function formatFileSize(bytes) {
      if (bytes === 0) return '0 Bytes';
      const k = 1024;
      const sizes = ['Bytes', 'KB', 'MB', 'GB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    removeFileBtn.addEventListener('click', function() {
      formFile.value = '';
      filePreview.classList.add('hidden');
    });

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
      modalBackdrop.classList.add('hidden');
      modalForm.classList.add('hidden');
      fileForm.reset();
      filePreview.classList.add('hidden');
    }

    closeModalBtn.addEventListener('click', closeModal);
    btnModalCancel.addEventListener('click', closeModal);
    modalBackdrop.addEventListener('click', closeModal);

    btnTambah.addEventListener('click', () => {
      openModal(false);
    });

    fileForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const formNamaFile = document.getElementById('formNamaFile');
      const formTipeFile = document.getElementById('formTipeFile');
      const formDeskripsi = document.getElementById('formDeskripsi');

      if (!formNamaFile.value) {
        alert('Nama dokumen harus diisi!');
        return;
      }
      
      // Check if file is required (when adding new)
      if (!isEditMode && !formFile.files[0]) {
        alert('Silakan pilih file untuk diupload!');
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
            renderFiles();
            closeModal();
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
            renderFiles();
            closeModal();
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

    async function saveNewFile(namaFile, tipe) {
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
          alert('File berhasil diupload!');
        }
      } catch (error) {
        alert('Error: ' + error.message);
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

    searchInput.addEventListener('input', (e) => {
      const q = e.target.value.toLowerCase();
      document.querySelectorAll('#fileGrid > div').forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(q) ? '' : 'none';
      });
    });

    // Button Buat Folder
    const btnFolder = document.getElementById('btnFolder');
    btnFolder.addEventListener('click', () => {
      const folderName = prompt('Nama Folder:');
      if (folderName) {
        saveNewFile(folderName, 'FOLDER');
      }
    });

    loadData();
  </script>
</body>
</html>
