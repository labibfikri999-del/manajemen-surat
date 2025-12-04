{{-- resources/views/dashboard.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Dashboard — YARSI NTB</title>
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
    .sidebar-brand { padding-bottom: 1rem; border-bottom: 1px solid #e0f2fe; }
    .nav-item { display: flex; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.5rem; transition: all 0.2s; }
    .nav-item.active { background: #d1fae5; color: #047857; border-left: 3px solid #047857; }
    .nav-item:hover { background: #d1fae5; transform: translateX(2px); }
    .tooltip { position: relative; }
    .tooltip-text { 
      position: absolute; 
      left: calc(100% + 10px); 
      top: 50%; 
      transform: translateY(-50%); 
      background: #065f46; 
      color: white; 
      padding: 0.5rem 0.75rem; 
      border-radius: 0.375rem; 
      white-space: nowrap; 
      opacity: 0; 
      pointer-events: none; 
      transition: opacity 0.3s ease, transform 0.3s ease; 
      font-size: 0.8125rem;
      font-weight: 600;
      letter-spacing: 0.5px;
      box-shadow: 0 10px 15px -3px rgba(0,0,0,0.2), 0 4px 6px -2px rgba(0,0,0,0.1);
      z-index: 1000;
    }
    .tooltip-text::before {
      content: '';
      position: absolute;
      right: 100%;
      top: 50%;
      transform: translateY(-50%);
      border: 5px solid transparent;
      border-right-color: #065f46;
    }
    .sidebar.sidebar-collapsed .nav-item:hover .tooltip-text { opacity: 1; transform: translateY(-50%) translateX(0); }
    .tooltip.show-tooltip .tooltip-text { opacity: 1; }
  </style>
</head>
<body class="bg-emerald-50">
  <div id="app" class="flex flex-col">
    {{-- Header --}}
    <header class="site-header bg-white border-b border-emerald-100 flex items-center justify-between px-4 md:px-6 lg:px-8 shadow-sm">
      <div class="flex items-center gap-4">
        <button id="btnOpenMobile" class="md:hidden text-emerald-700">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="flex items-center gap-3">
          <img src="/images/logo-yarsi.svg" alt="YARSI Logo" class="w-10 h-10">
          <div class="flex flex-col">
            <span class="text-lg font-bold text-emerald-700">YARSI NTB</span>
            <span class="text-xs text-emerald-600">Sistem Arsip Digital</span>
          </div>
        </div>
      </div>
      <div class="text-sm text-emerald-600">Admin Dashboard</div>
    </header>

    {{-- Sidebar --}}
    <aside id="sidebar" class="sidebar sidebar-hidden-mobile border-r border-emerald-100">
      <div class="p-4">
        {{-- Collapse button --}}
        <button id="btnCollapse" class="hidden md:flex w-full items-center justify-center mb-4 p-2 rounded hover:bg-emerald-50 text-emerald-700">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>

        {{-- Navigation --}}
        <nav class="mt-5">
          <ul class="space-y-1">
            <li>
              <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-2 rounded-md nav-item active tooltip relative" data-label="Dashboard" title="Dashboard">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9.75L12 3l9 6.75V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V9.75z"/></svg>
                <span class="nav-label text-sm font-medium">Dashboard</span>
                <span class="tooltip-text">Dashboard</span>
              </a>
            </li>
            <li>
              <a href="{{ route('surat-masuk') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative" data-label="Surat Masuk" title="Surat Masuk">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8.5A2.5 2.5 0 015.5 6h13A2.5 2.5 0 0121 8.5v7A2.5 2.5 0 0118.5 18h-13A2.5 2.5 0 013 15.5v-7zM3 8.5l7 4 7-4"/></svg>
                <span class="nav-label text-sm">Surat Masuk</span>
                <span class="tooltip-text">Surat Masuk</span>
              </a>
            </li>
            <li>
              <a href="{{ route('surat-keluar') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative" data-label="Surat Keluar" title="Surat Keluar">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2 12l18-7-7 18-3-8-8-3z"/></svg>
                <span class="nav-label text-sm">Surat Keluar</span>
                <span class="tooltip-text">Surat Keluar</span>
              </a>
            </li>
            <li>
              <a href="{{ route('arsip-digital') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative" data-label="Arsip Digital" title="Arsip Digital">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7h18M8 7v-2a1 1 0 011-1h6a1 1 0 011 1v2M21 7l-1 13a2 2 0 01-2 2H6a2 2 0 01-2-2L3 7"/></svg>
                <span class="nav-label text-sm">Arsip Digital</span>
                <span class="tooltip-text">Arsip Digital</span>
              </a>
            </li>
            <li>
              <a href="{{ route('laporan') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative" data-label="Laporan" title="Laporan">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3v18h18M9 17V9M13 17V5M17 17v-4"/></svg>
                <span class="nav-label text-sm">Laporan</span>
                <span class="tooltip-text">Laporan</span>
              </a>
            </li>
            <li>
              <a href="{{ route('data-master') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative" data-label="Data Master" title="Data Master">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 2C7.6 2 4 3.8 4 6v12c0 2.2 3.6 4 8 4s8-1.8 8-4V6c0-2.2-3.6-4-8-4zM4 10c0 2.2 3.6 4 8 4s8-1.8 8-4"/></svg>
                <span class="nav-label text-sm">Data Master</span>
                <span class="tooltip-text">Data Master</span>
              </a>
            </li>
          </ul>
        </nav>

        {{-- Footer --}}
        <div class="sidebar-footer">
          <div class="mt-6 border-t pt-4">
            <div class="text-xs text-emerald-600 mb-2 sidebar-brand-text">Admin</div>
            <form method="POST" action="#" onsubmit="event.preventDefault(); alert('Logout (demo)')">
              <button type="submit" class="logout-btn w-full flex items-center gap-3 px-3 py-2 rounded-md border border-emerald-100 hover:bg-emerald-50 transition-smooth text-emerald-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                </svg>
                <span class="logout-label nav-label text-sm font-medium text-emerald-700">Logout</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </aside>

    {{-- Mobile overlay --}}
    <div id="mobileOverlay" class="mobile-overlay hidden"></div>

    {{-- Main content --}}
    <main id="main" class="transition-smooth main-with-sidebar flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto" style="margin-top:0">
      <div class="max-w-7xl mx-auto">
        {{-- Page header --}}
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-emerald-900">Dashboard</h1>
          <p class="text-emerald-600 mt-2">Selamat datang di Sistem Manajemen Arsip Digital YARSI NTB</p>
        </div>

        {{-- Statistics cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-emerald-600 text-sm font-medium">Surat Masuk</p>
                <p id="statSuratMasuk" class="text-3xl font-bold text-emerald-900 mt-2">0</p>
              </div>
              <svg class="w-12 h-12 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8.5A2.5 2.5 0 015.5 6h13A2.5 2.5 0 0121 8.5v7A2.5 2.5 0 0118.5 18h-13A2.5 2.5 0 013 15.5v-7zM3 8.5l7 4 7-4"/></svg>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-emerald-600 text-sm font-medium">Surat Keluar</p>
                <p id="statSuratKeluar" class="text-3xl font-bold text-emerald-900 mt-2">0</p>
              </div>
              <svg class="w-12 h-12 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12l18-7-7 18-3-8-8-3z"/></svg>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-emerald-600 text-sm font-medium">Arsip Digital</p>
                <p id="statArsipDigital" class="text-3xl font-bold text-emerald-900 mt-2">0</p>
              </div>
              <svg class="w-12 h-12 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M8 7v-2a1 1 0 011-1h6a1 1 0 011 1v2M21 7l-1 13a2 2 0 01-2 2H6a2 2 0 01-2-2L3 7"/></svg>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-emerald-600 text-sm font-medium">Pengguna Aktif</p>
                <p class="text-3xl font-bold text-emerald-900 mt-2">12</p>
              </div>
              <svg class="w-12 h-12 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 10H9M21 20.354A4 4 0 0012.646 15H11.354A4 4 0 003 20.354"/></svg>
            </div>
          </div>
        </div>

        {{-- Charts and activity --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-emerald-900 mb-4">Aktivitas Bulanan</h2>
            <div class="h-64 flex items-end justify-between gap-2">
              <div class="flex-1 bg-emerald-200 rounded" style="height: 40%;"></div>
              <div class="flex-1 bg-emerald-300 rounded" style="height: 60%;"></div>
              <div class="flex-1 bg-emerald-400 rounded" style="height: 80%;"></div>
              <div class="flex-1 bg-emerald-300 rounded" style="height: 70%;"></div>
              <div class="flex-1 bg-emerald-200 rounded" style="height: 50%;"></div>
              <div class="flex-1 bg-emerald-400 rounded" style="height: 90%;"></div>
            </div>
            <div class="flex justify-between mt-4 text-xs text-emerald-600">
              <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-emerald-900 mb-4">Aktivitas Terbaru</h2>
            <ul class="space-y-3">
              <li class="text-sm border-l-4 border-emerald-400 pl-3">
                <p class="font-medium text-emerald-900">Surat diterima</p>
                <p class="text-emerald-600 text-xs">5 menit lalu</p>
              </li>
              <li class="text-sm border-l-4 border-green-400 pl-3">
                <p class="font-medium text-emerald-900">Surat diarsipkan</p>
                <p class="text-emerald-600 text-xs">2 jam lalu</p>
              </li>
              <li class="text-sm border-l-4 border-orange-400 pl-3">
                <p class="font-medium text-emerald-900">Laporan dibuat</p>
                <p class="text-emerald-600 text-xs">1 hari lalu</p>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    const sidebar = document.getElementById('sidebar');
    const main = document.getElementById('main');
    const btnCollapse = document.getElementById('btnCollapse');
    const btnOpenMobile = document.getElementById('btnOpenMobile');
    const mobileOverlay = document.getElementById('mobileOverlay');

    let collapsed = false;
    let mobileOpen = false;

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
      mobileOverlay.classList.add('block');
      mobileOpen = true;
    }
    function closeMobile(){
      sidebar.classList.add('sidebar-hidden-mobile');
      mobileOverlay.classList.add('hidden');
      mobileOverlay.classList.remove('block');
      mobileOpen = false;
    }
    if (btnOpenMobile) btnOpenMobile.addEventListener('click', openMobile);
    if (mobileOverlay) mobileOverlay.addEventListener('click', closeMobile);
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
        mobileOverlay.classList.remove('block');
      } else {
        sidebar.classList.add('sidebar-hidden-mobile');
      }
      setMainClass();
    }
    window.addEventListener('resize', onResize);
    onResize();

    // Load Statistics
    async function loadStatistics() {
      try {
        const [suratMasuk, suratKeluar, arsipDigital] = await Promise.all([
          fetch('/api/surat-masuk').then(r => r.json()),
          fetch('/api/surat-keluar').then(r => r.json()),
          fetch('/api/arsip-digital').then(r => r.json())
        ]);

        document.getElementById('statSuratMasuk').textContent = suratMasuk.length || 0;
        document.getElementById('statSuratKeluar').textContent = suratKeluar.length || 0;
        document.getElementById('statArsipDigital').textContent = arsipDigital.length || 0;
      } catch (error) {
        console.error('Error loading statistics:', error);
      }
    }

    loadStatistics();
  </script>
</body>
</html>


