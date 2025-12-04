{{-- resources/views/data-master.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Data Master — Arsiparis</title>
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
  </style>
</head>
<body class="bg-sky-50">
  <div id="app" class="flex flex-col">
    <header class="site-header bg-white border-b border-sky-100 flex items-center justify-between px-4 md:px-6 lg:px-8 shadow-sm">
      <div class="flex items-center gap-4">
        <button id="btnOpenMobile" class="md:hidden text-sky-700" style="z-index: 200; position: relative;">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="flex items-center gap-2">
          <svg class="w-8 h-8 text-sky-600" fill="currentColor" viewBox="0 0 24 24"><path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M3 7a2 2 0 012-2h14a2 2 0 012 2m0 0V5a2 2 0 00-2-2H5a2 2 0 00-2 2v2m0 0h16"/></svg>
          <span class="text-xl font-bold text-sky-700">Arsiparis</span>
        </div>
      </div>
      <div class="text-sm text-sky-600">Data Master</div>
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
            <li><a href="{{ route('arsip-digital') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7h18M8 7v-2a1 1 0 011-1h6a1 1 0 011 1v2M21 7l-1 13a2 2 0 01-2 2H6a2 2 0 01-2-2L3 7"/></svg><span class="nav-label text-sm">Arsip Digital</span><span class="tooltip-text">Arsip Digital</span></a></li>
            <li><a href="{{ route('laporan') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3v18h18M9 17V9M13 17V5M17 17v-4"/></svg><span class="nav-label text-sm">Laporan</span><span class="tooltip-text">Laporan</span></a></li>
            <li><a href="#" class="flex items-center gap-3 p-2 rounded-md nav-item active tooltip relative"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 2C7.6 2 4 3.8 4 6v12c0 2.2 3.6 4 8 4s8-1.8 8-4V6c0-2.2-3.6-4-8-4zM4 10c0 2.2 3.6 4 8 4s8-1.8 8-4"/></svg><span class="nav-label text-sm">Data Master</span><span class="tooltip-text">Data Master</span></a></li>
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
          <h1 class="text-3xl font-bold text-sky-900">Data Master</h1>
          <p class="text-sky-600 mt-2">Kelola data referensi sistem</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6">
          <div class="bg-white rounded-lg shadow p-3 md:p-4 cursor-pointer hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sky-600 text-xs md:text-sm">Klasifikasi</p>
                <p class="text-xl md:text-2xl font-bold text-sky-900">24</p>
              </div>
              <svg class="w-8 h-8 md:w-10 md:h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-3 md:p-4 cursor-pointer hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sky-600 text-xs md:text-sm">Departemen</p>
                <p class="text-xl md:text-2xl font-bold text-sky-900">8</p>
              </div>
              <svg class="w-8 h-8 md:w-10 md:h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-3 md:p-4 cursor-pointer hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sky-600 text-xs md:text-sm">Pengguna</p>
                <p class="text-xl md:text-2xl font-bold text-sky-900">12</p>
              </div>
              <svg class="w-8 h-8 md:w-10 md:h-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 10H9M21 20.354A4 4 0 0012.646 15H11.354A4 4 0 003 20.354"/></svg>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-3 md:p-4 cursor-pointer hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sky-600 text-xs md:text-sm">Lampiran</p>
                <p class="text-xl md:text-2xl font-bold text-sky-900">156</p>
              </div>
              <svg class="w-8 h-8 md:w-10 md:h-10 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </div>
          </div>
        </div>

        {{-- Tabs --}}
        <div class="bg-white rounded-lg shadow">
          <div class="flex overflow-x-auto border-b border-sky-100 scrollbar-hide">
            <button id="tabKlasifikasi" class="tab-btn px-4 md:px-6 py-4 text-sky-900 font-medium border-b-2 border-sky-500 hover:bg-sky-50 whitespace-nowrap flex-shrink-0" data-tab="klasifikasi">Klasifikasi</button>
            <button id="tabDepartemen" class="tab-btn px-4 md:px-6 py-4 text-sky-600 font-medium hover:bg-sky-50 whitespace-nowrap flex-shrink-0" data-tab="departemen">Departemen</button>
            <button id="tabPengguna" class="tab-btn px-4 md:px-6 py-4 text-sky-600 font-medium hover:bg-sky-50 whitespace-nowrap flex-shrink-0" data-tab="pengguna">Pengguna</button>
            <button id="tabLampiran" class="tab-btn px-4 md:px-6 py-4 text-sky-600 font-medium hover:bg-sky-50 whitespace-nowrap flex-shrink-0" data-tab="lampiran">Tipe Lampiran</button>
          </div>

          <div id="tabContent" class="p-4 md:p-6">
            <div class="flex flex-col sm:flex-row gap-3 mb-6">
              <button id="btnTambahKlasifikasi" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Klasifikasi
              </button>
              <input id="searchKlasifikasi" type="text" placeholder="Cari..." class="flex-1 px-4 py-2 border border-sky-300 rounded focus:outline-none focus:border-sky-500" aria-label="Cari klasifikasi" />
            </div>

            {{-- Desktop Table View --}}
            <div class="hidden md:block overflow-x-auto">
              <table class="w-full">
                <thead class="bg-sky-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-sky-900">No.</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-sky-900">Nama</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold text-sky-900">Aksi</th>
                  </tr>
                </thead>
                <tbody id="tableBodyKlasifikasi" class="divide-y divide-sky-100">
                </tbody>
              </table>
            </div>

            {{-- Mobile Card View --}}
            <div id="mobileCardContainer" class="md:hidden space-y-3">
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
          <p id="toastMessage" class="text-sm font-medium text-gray-800"></p>
        </div>
        <button id="toastClose" class="text-gray-400 hover:text-gray-600">
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
    <div id="modalBackdrop" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-200"></div>
    <div id="modalForm" class="hidden fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-2xl z-50 w-full max-w-md mx-4 transition-all duration-200">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 id="modalTitle" class="text-2xl font-bold text-sky-900">Tambah Data</h2>
          <button id="closeModal" class="text-sky-400 hover:text-sky-600 text-2xl">×</button>
        </div>
        
        <form id="dataForm" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-sky-700 mb-2" id="formLabel">Nama <span class="text-red-500">*</span></label>
            <input type="text" id="formNama" placeholder="Masukkan nama..." required class="w-full px-4 py-2 border border-sky-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          </div>

          <div id="formKodeContainer" class="hidden">
            <label class="block text-sm font-medium text-sky-700 mb-2">Kode</label>
            <input type="text" id="formKode" placeholder="Masukkan kode..." class="w-full px-4 py-2 border border-sky-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          </div>

          <div id="formDeskripsiContainer" class="hidden">
            <label class="block text-sm font-medium text-sky-700 mb-2">Deskripsi</label>
            <textarea id="formDeskripsi" rows="2" placeholder="Masukkan deskripsi..." class="w-full px-4 py-2 border border-sky-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent"></textarea>
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
      console.log('openMobile called');
      sidebar.classList.remove('sidebar-hidden-mobile');
      mobileOverlay.classList.remove('hidden');
    }
    function closeMobile(){
      console.log('closeMobile called');
      sidebar.classList.add('sidebar-hidden-mobile');
      mobileOverlay.classList.add('hidden');
    }
    
    console.log('btnOpenMobile:', btnOpenMobile);
    console.log('mobileOverlay:', mobileOverlay);
    
    if (btnOpenMobile) {
      btnOpenMobile.addEventListener('click', function(e) {
        console.log('Button clicked!', e);
        openMobile();
      });
    }
    if (mobileOverlay) mobileOverlay.addEventListener('click', closeMobile);

    sidebar.querySelectorAll('a').forEach(a=>{
      a.addEventListener('click', function(){
        if(window.innerWidth < 768) closeMobile();
      });
    });

    // Toast notification function
    function showToast(message, type = 'success') {
      const toast = document.getElementById('toast');
      const toastMessage = document.getElementById('toastMessage');
      const toastIcon = document.getElementById('toastIcon');
      const toastContent = document.getElementById('toastContent');
      
      toastMessage.textContent = message;
      
      if (type === 'success') {
        toastContent.className = 'bg-white rounded-lg shadow-2xl p-4 flex items-center gap-3 min-w-[320px] max-w-md border-l-4 border-green-500';
        toastIcon.innerHTML = '<svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
      } else {
        toastContent.className = 'bg-white rounded-lg shadow-2xl p-4 flex items-center gap-3 min-w-[320px] max-w-md border-l-4 border-red-500';
        toastIcon.innerHTML = '<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
      }
      
      toast.classList.remove('hidden');
      setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    document.getElementById('toastClose').addEventListener('click', () => {
      document.getElementById('toast').classList.add('hidden');
    });

    // Confirmation modal function
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

    // CRUD untuk Data Master - Klasifikasi
    const btnTambahKlasifikasi = document.getElementById('btnTambahKlasifikasi');
    const tableBodyKlasifikasi = document.getElementById('tableBodyKlasifikasi');
    const searchKlasifikasi = document.getElementById('searchKlasifikasi');
    let allKlasifikasiData = [];

    async function loadKlasifikasi() {
      try {
        const response = await fetch('/api/klasifikasi-list');
        const data = await response.json();
        allKlasifikasiData = data;
        renderKlasifikasi();
      } catch (error) {
        console.error('Error:', error);
      }
    }

    function renderKlasifikasi() {
      // Render desktop table view
      tableBodyKlasifikasi.innerHTML = '';
      
      // Render mobile card view
      const mobileCardContainer = document.getElementById('mobileCardContainer');
      mobileCardContainer.innerHTML = '';
      
      allKlasifikasiData.forEach((item, index) => {
        // Desktop table row
        const row = document.createElement('tr');
        row.classList.add('hover:bg-sky-50');
        row.dataset.id = item.id;
        row.innerHTML = `
          <td class="px-6 py-4 text-sm text-sky-900">${index + 1}</td>
          <td class="px-6 py-4"><span class="text-sky-900 font-medium">${item.nama}</span></td>
          <td class="px-6 py-4 text-right">
            <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-sky-600 text-white text-sm rounded hover:bg-sky-700 transition mr-2 btn-edit" aria-label="Edit klasifikasi ${item.nama}">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              Edit
            </button>
            <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition btn-delete" aria-label="Hapus klasifikasi ${item.nama}">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              Hapus
            </button>
          </td>
        `;
        tableBodyKlasifikasi.appendChild(row);
        setupKlasifikasiButtons(row);
        
        // Mobile card
        const card = document.createElement('div');
        card.classList.add('bg-white', 'rounded-lg', 'border', 'border-sky-200', 'p-4', 'shadow-sm');
        card.dataset.id = item.id;
        card.innerHTML = `
          <div class="flex items-start justify-between mb-3">
            <div class="flex-1">
              <div class="text-xs text-sky-600 mb-1">No. ${index + 1}</div>
              <div class="text-base font-semibold text-sky-900">${item.nama}</div>
            </div>
          </div>
          <div class="flex gap-2">
            <button class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-sky-600 text-white text-sm rounded hover:bg-sky-700 transition btn-edit-mobile" aria-label="Edit klasifikasi ${item.nama}">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              Edit
            </button>
            <button class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition btn-delete-mobile" aria-label="Hapus klasifikasi ${item.nama}">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              Hapus
            </button>
          </div>
        `;
        mobileCardContainer.appendChild(card);
        setupKlasifikasiButtonsMobile(card);
      });
    }
    
    function setupKlasifikasiButtonsMobile(card) {
      const editBtn = card.querySelector('.btn-edit-mobile');
      const deleteBtn = card.querySelector('.btn-delete-mobile');
      if (editBtn) editBtn.addEventListener('click', () => editKlasifikasi(card));
      if (deleteBtn) deleteBtn.addEventListener('click', async () => {
        const confirmed = await showConfirm('Konfirmasi Hapus', 'Apakah Anda yakin ingin menghapus data ini?');
        if (confirmed) deleteKlasifikasi(card);
      });
    }

    function setupKlasifikasiButtons(row) {
      const editBtn = row.querySelector('.btn-edit');
      const deleteBtn = row.querySelector('.btn-delete');
      if (editBtn) editBtn.addEventListener('click', () => editKlasifikasi(row));
      if (deleteBtn) deleteBtn.addEventListener('click', async () => {
        const confirmed = await showConfirm('Konfirmasi Hapus', 'Apakah Anda yakin ingin menghapus data ini?');
        if (confirmed) deleteKlasifikasi(row);
      });
    }

    // Modal handling
    const modalBackdrop = document.getElementById('modalBackdrop');
    const modalForm = document.getElementById('modalForm');
    const closeModalBtn = document.getElementById('closeModal');
    const btnModalCancel = document.getElementById('btnModalCancel');
    const dataForm = document.getElementById('dataForm');
    let isEditMode = false;
    let editingDataId = null;

    function openModal(isEdit = false, dataId = null) {
      isEditMode = isEdit;
      editingDataId = dataId;
      const modalTitle = document.getElementById('modalTitle');
      const formNama = document.getElementById('formNama');

      if (isEdit && dataId) {
        const item = allKlasifikasiData.find(d => d.id === dataId);
        modalTitle.textContent = 'Edit Klasifikasi';
        formNama.value = item.nama;
      } else {
        modalTitle.textContent = 'Tambah Klasifikasi';
        formNama.value = '';
      }

      modalBackdrop.classList.remove('hidden');
      modalForm.classList.remove('hidden');
      formNama.focus();
    }

    function closeModal() {
      modalBackdrop.classList.add('hidden');
      modalForm.classList.add('hidden');
      dataForm.reset();
    }

    closeModalBtn.addEventListener('click', closeModal);
    btnModalCancel.addEventListener('click', closeModal);
    modalBackdrop.addEventListener('click', closeModal);

    btnTambahKlasifikasi.addEventListener('click', () => {
      openModal(false);
    });

    dataForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const formNama = document.getElementById('formNama');

      if (!formNama.value) {
        showToast('Nama harus diisi!', 'error');
        return;
      }

      try {
        if (isEditMode && editingDataId) {
          const response = await fetch(`/api/klasifikasi/${editingDataId}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ nama: formNama.value })
          });

          const responseText = await response.text();
          let updatedData;
          try {
            updatedData = JSON.parse(responseText);
          } catch (e) {
            console.error('Response text:', responseText);
            showToast('Error: Respons server tidak valid.', 'error');
            return;
          }

          if (response.ok) {
            const index = allKlasifikasiData.findIndex(item => item.id === editingDataId);
            if (index !== -1) allKlasifikasiData[index] = updatedData;
            renderKlasifikasi();
            closeModal();
            showToast('Data berhasil diperbarui!', 'success');
          } else {
            showToast('Error: ' + (updatedData.message || 'Gagal memperbarui data'), 'error');
          }
        } else {
          const response = await fetch('/api/klasifikasi-store', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ nama: formNama.value })
          });

          const responseText = await response.text();
          let newData;
          try {
            newData = JSON.parse(responseText);
          } catch (e) {
            console.error('Response text:', responseText);
            showToast('Error: Respons server tidak valid.', 'error');
            return;
          }

          if (response.ok) {
            allKlasifikasiData.unshift(newData);
            renderKlasifikasi();
            closeModal();
            showToast('Data berhasil disimpan!', 'success');
          } else {
            showToast('Error: ' + (newData.message || 'Gagal menyimpan data'), 'error');
          }
        }
      } catch (error) {
        console.error('Error:', error);
        showToast('Error: ' + error.message, 'error');
      }
    });

    async function saveNewKlasifikasi(nama) {
      try {
        const response = await fetch('/api/klasifikasi-store', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ nama: nama })
        });
        if (response.ok) {
          const newData = await response.json();
          allKlasifikasiData.unshift(newData);
          renderKlasifikasi();
          alert('Klasifikasi berhasil ditambah!');
        }
      } catch (error) {
        alert('Error: ' + error.message);
      }
    }

    function editKlasifikasi(row) {
      const id = row.dataset.id;
      openModal(true, id);
    }

    async function deleteKlasifikasi(row) {
      const id = parseInt(row.dataset.id);
      try {
        const response = await fetch(`/api/klasifikasi/${id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });
        if (response.ok) {
          allKlasifikasiData = allKlasifikasiData.filter(item => parseInt(item.id) !== id);
          renderKlasifikasi();
          showToast('Klasifikasi berhasil dihapus!', 'success');
        }
      } catch (error) {
        showToast('Error: ' + error.message, 'error');
      }
    }

    searchKlasifikasi.addEventListener('input', (e) => {
      const q = e.target.value.toLowerCase();
      // Search in desktop table
      document.querySelectorAll('#tableBodyKlasifikasi tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
      });
      // Search in mobile cards
      document.querySelectorAll('#mobileCardContainer > div').forEach(card => {
        card.style.display = card.textContent.toLowerCase().includes(q) ? '' : 'none';
      });
    });

    loadKlasifikasi();
  </script>
</body>
</html>
