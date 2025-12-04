{{-- resources/views/surat-masuk.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Surat Masuk — Arsiparis</title>
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
    .nav-item.active { background: #f0f9ff; color: #0369a1; }
    .nav-item:hover { background: #f0f9ff; }
    .tooltip-text { position: absolute; left: 100%; top: 50%; transform: translateY(-50%); background: #0c4a6e; color: white; padding: 0.5rem 0.75rem; border-radius: 0.375rem; white-space: nowrap; opacity: 0; pointer-events: none; transition: opacity .2s; margin-left: 0.5rem; }
    .tooltip.show-tooltip .tooltip-text { opacity: 1; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    @media (min-width: 768px) {
      .modal-backdrop-desktop { left: var(--sidebar-w) !important; }
    }
  </style>
</head>
<body class="bg-sky-50">
  <div id="app" class="flex flex-col">
    {{-- Header --}}
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
      <div class="text-sm text-sky-600">Surat Masuk</div>
    </header>

    {{-- Sidebar --}}
    <aside id="sidebar" class="sidebar sidebar-hidden-mobile border-r border-sky-100">
      <div class="p-4">
        <button id="btnCollapse" class="hidden md:flex w-full items-center justify-center mb-4 p-2 rounded hover:bg-sky-50 text-sky-700">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>

        <nav class="mt-5">
          <ul class="space-y-1">
            <li>
              <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9.75L12 3l9 6.75V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V9.75z"/></svg>
                <span class="nav-label text-sm font-medium">Dashboard</span>
                <span class="tooltip-text">Dashboard</span>
              </a>
            </li>
            <li>
              <a href="#" class="flex items-center gap-3 p-2 rounded-md nav-item active tooltip relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8.5A2.5 2.5 0 015.5 6h13A2.5 2.5 0 0121 8.5v7A2.5 2.5 0 0118.5 18h-13A2.5 2.5 0 013 15.5v-7zM3 8.5l7 4 7-4"/></svg>
                <span class="nav-label text-sm">Surat Masuk</span>
                <span class="tooltip-text">Surat Masuk</span>
              </a>
            </li>
            <li>
              <a href="{{ route('surat-keluar') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2 12l18-7-7 18-3-8-8-3z"/></svg>
                <span class="nav-label text-sm">Surat Keluar</span>
                <span class="tooltip-text">Surat Keluar</span>
              </a>
            </li>
            <li>
              <a href="{{ route('arsip-digital') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7h18M8 7v-2a1 1 0 011-1h6a1 1 0 011 1v2M21 7l-1 13a2 2 0 01-2 2H6a2 2 0 01-2-2L3 7"/></svg>
                <span class="nav-label text-sm">Arsip Digital</span>
                <span class="tooltip-text">Arsip Digital</span>
              </a>
            </li>
            <li>
              <a href="{{ route('laporan') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3v18h18M9 17V9M13 17V5M17 17v-4"/></svg>
                <span class="nav-label text-sm">Laporan</span>
                <span class="tooltip-text">Laporan</span>
              </a>
            </li>
            <li>
              <a href="{{ route('data-master') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 2C7.6 2 4 3.8 4 6v12c0 2.2 3.6 4 8 4s8-1.8 8-4V6c0-2.2-3.6-4-8-4zM4 10c0 2.2 3.6 4 8 4s8-1.8 8-4"/></svg>
                <span class="nav-label text-sm">Data Master</span>
                <span class="tooltip-text">Data Master</span>
              </a>
            </li>
          </ul>
        </nav>

        <div class="sidebar-footer">
          <div class="mt-6 border-t pt-4">
            <div class="text-xs text-sky-600 mb-2 sidebar-brand-text">Admin</div>
            <form method="POST" action="#" onsubmit="event.preventDefault(); alert('Logout (demo)')">
              <button type="submit" class="logout-btn w-full flex items-center gap-3 px-3 py-2 rounded-md border border-sky-100 hover:bg-sky-50 transition-smooth text-sky-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                </svg>
                <span class="logout-label nav-label text-sm font-medium text-sky-700">Logout</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </aside>

    {{-- Mobile overlay --}}
    <div id="mobileOverlay" class="mobile-overlay hidden"></div>

    {{-- Main content --}}
    <main id="main" class="transition-smooth main-with-sidebar flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
      <div class="max-w-7xl mx-auto">
        {{-- Page header --}}
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-sky-900">Surat Masuk</h1>
          <p class="text-sky-600 mt-2">Kelola semua surat yang masuk ke instansi</p>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 mb-6">
          <div class="bg-white rounded-lg shadow p-3 md:p-4">
            <div class="text-sky-600 text-xs md:text-sm font-medium">Total Surat Masuk</div>
            <p class="text-2xl md:text-3xl font-bold text-sky-900 mt-2">124</p>
            <p class="text-xs text-sky-500 mt-1">Tahun ini</p>
          </div>
          <div class="bg-white rounded-lg shadow p-3 md:p-4">
            <div class="text-sky-600 text-xs md:text-sm font-medium">Belum Ditindaklanjuti</div>
            <p class="text-2xl md:text-3xl font-bold text-orange-600 mt-2">12</p>
            <p class="text-xs text-sky-500 mt-1">Perlu perhatian</p>
          </div>
          <div class="bg-white rounded-lg shadow p-3 md:p-4">
            <div class="text-sky-600 text-xs md:text-sm font-medium">Sudah Ditindaklanjuti</div>
            <p class="text-2xl md:text-3xl font-bold text-green-600 mt-2">112</p>
            <p class="text-xs text-sky-500 mt-1">Selesai</p>
          </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row flex-wrap gap-3 mb-6">
          <button id="btnTambah" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 flex items-center justify-center gap-2" aria-label="Tambah Surat Masuk Baru">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Surat Masuk
          </button>
          <button id="btnCetak" class="border border-sky-300 text-sky-700 px-4 py-2 rounded hover:bg-sky-50 flex items-center justify-center gap-2" aria-label="Cetak Daftar Surat Masuk">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H9m4 0h4m-2-2v2m0 0v2m0-6V9m0 4h.01"/></svg>
            Cetak Daftar
          </button>
          <button id="btnImpor" class="border border-sky-300 text-sky-700 px-4 py-2 rounded hover:bg-sky-50 flex items-center justify-center gap-2" aria-label="Impor File Surat Masuk">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Impor File
          </button>
          <input type="text" id="searchInput" placeholder="Cari surat..." class="flex-1 sm:flex-initial sm:ml-auto px-4 py-2 border border-sky-300 rounded focus:outline-none focus:border-sky-500" aria-label="Cari surat masuk" />
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-sky-50 border-b border-sky-100">
                <tr>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-sky-900">No.</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-sky-900">Nomor Surat</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-sky-900">Tanggal Diterima</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-sky-900">Pengirim</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-sky-900">Perihal</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-sky-900">File</th>
                  <th class="px-6 py-3 text-left text-sm font-semibold text-sky-900">Status</th>
                  <th class="px-6 py-3 text-right text-sm font-semibold text-sky-900">Aksi</th>
                </tr>
              </thead>
              <tbody id="tableBody" class="divide-y divide-sky-100">
                {{-- Skeleton Loading --}}
                <tr class="animate-pulse skeleton-row">
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-8"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-32"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-24"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-40"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-48"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-16"></div></td>
                  <td class="px-6 py-4"><div class="h-5 bg-sky-200 rounded w-16"></div></td>
                  <td class="px-6 py-4"><div class="h-8 bg-sky-200 rounded w-32 ml-auto"></div></td>
                </tr>
                <tr class="animate-pulse skeleton-row">
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-8"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-32"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-24"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-40"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-48"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-16"></div></td>
                  <td class="px-6 py-4"><div class="h-5 bg-sky-200 rounded w-16"></div></td>
                  <td class="px-6 py-4"><div class="h-8 bg-sky-200 rounded w-32 ml-auto"></div></td>
                </tr>
                <tr class="animate-pulse skeleton-row">
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-8"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-32"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-24"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-40"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-48"></div></td>
                  <td class="px-6 py-4"><div class="h-4 bg-sky-200 rounded w-16"></div></td>
                  <td class="px-6 py-4"><div class="h-5 bg-sky-200 rounded w-16"></div></td>
                  <td class="px-6 py-4"><div class="h-8 bg-sky-200 rounded w-32 ml-auto"></div></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-between mt-6">
          <p class="text-sm text-sky-600">Menampilkan 1 dari 124 surat masuk</p>
          <div class="flex gap-2">
            <button class="px-3 py-1 border border-sky-300 rounded text-sky-600 hover:bg-sky-50">← Sebelumnya</button>
            <button class="px-3 py-1 border border-sky-300 rounded text-sky-600 hover:bg-sky-50">Selanjutnya →</button>
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
    <div id="modalBackdrop" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-200 modal-backdrop-desktop"></div>
    <div id="modalForm" class="hidden fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-2xl z-50 w-full max-w-lg mx-4 transition-all duration-200 max-h-[90vh] overflow-y-auto">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 id="modalTitle" class="text-2xl font-bold text-sky-900">Tambah Surat Masuk</h2>
          <button id="closeModal" class="text-sky-400 hover:text-sky-600 text-2xl">×</button>
        </div>
        
        <form id="suratForm" class="space-y-4" enctype="multipart/form-data">
          <div>
            <label class="block text-sm font-medium text-sky-700 mb-2">Nomor Surat <span class="text-red-500">*</span></label>
            <input type="text" id="formNomorSurat" placeholder="SM-2025/001" required class="w-full px-4 py-2 border border-sky-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          </div>
          
          <div>
            <label class="block text-sm font-medium text-sky-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
            <input type="date" id="formTanggal" required class="w-full px-4 py-2 border border-sky-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          </div>
          
          <div>
            <label class="block text-sm font-medium text-sky-700 mb-2">Pengirim <span class="text-red-500">*</span></label>
            <input type="text" id="formPengirim" placeholder="Nama Pengirim" required class="w-full px-4 py-2 border border-sky-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          </div>
          
          <div>
            <label class="block text-sm font-medium text-sky-700 mb-2">Perihal <span class="text-red-500">*</span></label>
            <textarea id="formPerihal" placeholder="Perihal Surat" rows="3" required class="w-full px-4 py-2 border border-sky-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent"></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-sky-700 mb-2">File Surat (PDF)</label>
            <div class="relative">
              <input type="file" id="formFile" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx" class="w-full px-4 py-2 border border-sky-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-sky-100 file:text-sky-700 hover:file:bg-sky-200">
            </div>
            <p class="text-xs text-sky-500 mt-1">Format: PDF, PNG, JPG, DOC, DOCX (Max 5MB)</p>
            <div id="filePreview" class="hidden mt-2 p-2 bg-sky-50 rounded-lg items-center gap-2">
              <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              <span id="fileName" class="text-sm text-sky-700 truncate"></span>
              <button type="button" id="removeFile" class="ml-auto text-red-500 hover:text-red-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>
          </div>
          
          <div class="flex gap-3 pt-4">
            <button type="button" id="btnModalCancel" class="flex-1 px-4 py-2 border border-sky-300 text-sky-700 rounded-lg hover:bg-sky-50 transition">Batal</button>
            <button type="submit" class="flex-1 px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition">Simpan</button>
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
    // Tampilkan sidebar di desktop saat load pertama
    if(window.innerWidth >= 768) {
      sidebar.classList.remove('sidebar-hidden-mobile');
      mobileOverlay.classList.add('hidden');
    }

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
        const data = await response.json();
        allRowsData = data;
        renderTable();
      } catch (error) {
        console.error('Error loading data:', error);
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
      
      allRowsData.forEach((item, index) => {
        const row = document.createElement('tr');
        row.classList.add('hover:bg-sky-50');
        row.dataset.id = item.id;
        
        // Create file link HTML
        let fileHtml = '<span class="text-gray-400">-</span>';
        if (item.file_url) {
          fileHtml = `<a href="${item.file_url}" target="_blank" class="inline-flex items-center gap-1 text-sky-600 hover:text-sky-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span class="text-sm">Lihat</span>
          </a>`;
        }
        
        row.innerHTML = `
          <td class="px-6 py-4 text-sm text-sky-900">${index + 1}</td>
          <td class="px-6 py-4"><span class="font-medium text-sky-900">${item.nomor_surat}</span></td>
          <td class="px-6 py-4"><span class="text-sky-600">${item.tanggal_diterima}</span></td>
          <td class="px-6 py-4"><span class="text-sky-600">${item.pengirim}</span></td>
          <td class="px-6 py-4"><span class="text-sky-600">${item.perihal}</span></td>
          <td class="px-6 py-4">${fileHtml}</td>
          <td class="px-6 py-4"><span class="inline-block px-3 py-1 text-xs font-medium bg-sky-100 text-sky-700 rounded">Aktif</span></td>
          <td class="px-6 py-4 text-right">
            <button class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-white bg-sky-600 rounded-lg hover:bg-sky-700 transition btn-edit" aria-label="Edit surat">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              Edit
            </button>
            <button class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition btn-delete" aria-label="Hapus surat">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              Hapus
            </button>
          </td>
        `;
        tableBody.appendChild(row);
        setupRowActions(row);
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
        const item = allRowsData.find(d => d.id === rowId);
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

    // Simpan row baru (deprecated, kept for reference)
    async function saveNewRow(row) {
      const inputs = row.querySelectorAll('input');
      if (!inputs[0].value || !inputs[1].value || !inputs[2].value) {
        showToast('Data Tidak Lengkap', 'Semua field harus diisi', 'warning');
        return;
      }
      
      try {
        const response = await fetch('/api/surat-masuk', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            nomor_surat: inputs[0].value,
            tanggal_diterima: inputs[1].value,
            pengirim: inputs[2].value,
            perihal: inputs[3].value
          })
        });
        
        const responseText = await response.text();
        let newData;
        try {
          newData = JSON.parse(responseText);
        } catch (e) {
          console.error('Response text:', responseText);
          alert('Error: Respons server tidak valid. Cek console untuk detail.');
          return;
        }
        
        if (response.ok) {
          allRowsData.unshift(newData);
          renderTable();
          showToast('Berhasil!', 'Data berhasil disimpan', 'success');
        } else {
          const errorMsg = newData.message || newData.errors || 'Gagal menyimpan data';
          showToast('Gagal Menyimpan', typeof errorMsg === 'object' ? JSON.stringify(errorMsg) : errorMsg, 'error');
        }
      } catch (error) {
        console.error('Error saving:', error);
        showToast('Error', error.message, 'error');
      }
    }

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

    // Simpan row
    function saveRow(row) {
      const inputs = row.querySelectorAll('input');
      if (!inputs[1].value || !inputs[2].value) {
        showToast('Data Tidak Lengkap', 'Nomor surat dan tanggal harus diisi', 'warning');
        return;
      }
      
      row.classList.remove('editing-row');
      const cells = row.querySelectorAll('td');
      cells[1].innerHTML = `<span class="font-medium text-sky-900">${inputs[0].value}</span>`;
      cells[2].innerHTML = `<span class="text-sky-600">${inputs[1].value}</span>`;
      cells[3].innerHTML = `<span class="text-sky-600">${inputs[2].value}</span>`;
      cells[4].innerHTML = `<span class="text-sky-600">${inputs[3].value}</span>`;
      cells[6].innerHTML = `
        <button class="text-sky-600 hover:text-sky-900 text-sm mr-3 btn-edit">Edit</button>
        <button class="text-red-600 hover:text-red-900 text-sm btn-delete">Hapus</button>
      `;
      
      setupRowButtons(row);
    }

    // Edit row
    function editRow(row) {
      const cells = row.querySelectorAll('td');
      const nomorSurat = cells[1].textContent;
      const tanggal = cells[2].textContent;
      const pengirim = cells[3].textContent;
      const perihal = cells[4].textContent;
      
      row.classList.add('editing-row');
      cells[1].innerHTML = `<input type="text" class="border border-sky-300 rounded px-2 py-1 w-full" value="${nomorSurat}">`;
      cells[2].innerHTML = `<input type="date" class="border border-sky-300 rounded px-2 py-1 w-full" value="${tanggal}">`;
      cells[3].innerHTML = `<input type="text" class="border border-sky-300 rounded px-2 py-1 w-full" value="${pengirim}">`;
      cells[4].innerHTML = `<input type="text" class="border border-sky-300 rounded px-2 py-1 w-full" value="${perihal}">`;
      cells[6].innerHTML = `
        <button class="text-green-600 hover:text-green-900 text-sm mr-3 btn-save">Simpan</button>
        <button class="text-red-600 hover:text-red-900 text-sm btn-cancel">Batal</button>
      `;
      
      row.querySelector('.btn-save').addEventListener('click', () => saveRow(row));
      row.querySelector('.btn-cancel').addEventListener('click', () => location.reload());
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

    // Initialize: Load data on page ready
    document.addEventListener('DOMContentLoaded', () => {
      loadData();
    });
  </script>
</body>
</html>
