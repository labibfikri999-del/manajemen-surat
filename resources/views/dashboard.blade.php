{{-- resources/views/dashboard.blade.php --}}
@php
    $user = auth()->user();
    $role = $user->role ?? 'guest';
    $currentRoute = 'dashboard';
    
    // Definisi menu dengan akses role
    $menus = [
        [
            'name' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9.75L12 3l9 6.75V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V9.75z"/>',
            'roles' => ['direktur', 'staff', 'instansi'],
        ],
        [
            'name' => 'Upload Dokumen',
            'route' => 'upload-dokumen',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>',
            'roles' => ['instansi'],
        ],
        [
            'name' => 'Tracking Dokumen',
            'route' => 'tracking-dokumen',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
            'roles' => ['instansi'],
        ],
        [
            'name' => 'Validasi Dokumen',
            'route' => 'validasi-dokumen',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'roles' => ['direktur'],
        ],
        [
            'name' => 'Proses Dokumen',
            'route' => 'proses-dokumen',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>',
            'roles' => ['staff'],
        ],
        [
            'name' => 'Hasil Validasi',
            'route' => 'hasil-validasi',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
            'roles' => ['direktur', 'staff', 'instansi'],
        ],
        [
            'name' => 'Arsip Digital',
            'route' => 'arsip-digital',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>',
            'roles' => ['direktur', 'staff'],
        ],
        [
            'name' => 'Laporan',
            'route' => 'laporan',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3v18h18M9 17V9M13 17V5M17 17v-4"/>',
            'roles' => ['direktur', 'staff', 'instansi'],
        ],
        [
            'name' => 'Data Master',
            'route' => 'data-master',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 2C7.6 2 4 3.8 4 6v12c0 2.2 3.6 4 8 4s8-1.8 8-4V6c0-2.2-3.6-4-8-4zM4 10c0 2.2 3.6 4 8 4s8-1.8 8-4"/>',
            'roles' => ['direktur'],
        ],
    ];
    
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
  <title>Dashboard — YARSI NTB</title>
  <link rel="icon" type="image/png" href="{{ asset('images/Logo Yayasan Bersih.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root{
      --header-h: 64px;
      --sidebar-w: 16rem;
      --sidebar-collapsed-w: 4.5rem;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html,body,#app { height: 100%; font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
    .transition-smooth { transition: all .22s cubic-bezier(.2,.8,.2,1); }
    html, body { overflow-x: hidden; background: #f8fafc; }
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
      overflow-x: hidden;
      background: white;
    }
    .sidebar::-webkit-scrollbar { width: 4px; }
    .sidebar::-webkit-scrollbar-track { background: transparent; }
    .sidebar::-webkit-scrollbar-thumb { background: #d1fae5; border-radius: 4px; }
    .sidebar::-webkit-scrollbar-thumb:hover { background: #a7f3d0; }
    .sidebar, .sidebar * { scrollbar-width: thin; scrollbar-color: #d1fae5 transparent; }
    .sidebar ::-webkit-scrollbar { width: 4px; height: 0; }
    .sidebar nav { overflow-x: hidden; }
    .sidebar nav::-webkit-scrollbar { height: 0; width: 4px; }
    .sidebar-collapsed { width: var(--sidebar-collapsed-w) !important; min-width: var(--sidebar-collapsed-w) !important; }
    .sidebar.sidebar-collapsed .nav-label,
    .sidebar.sidebar-collapsed .sidebar-brand-text,
    .sidebar.sidebar-collapsed .role-badge-text { display: none !important; }
    .sidebar.sidebar-collapsed .nav-item { justify-content: center; padding: 0.75rem !important; }
    .sidebar.sidebar-collapsed .nav-item-locked { justify-content: center; padding: 0.75rem !important; }
    .sidebar.sidebar-collapsed .nav-item-locked .lock-icon { display: none; }
    .sidebar.sidebar-collapsed .role-badge { padding: 0.75rem; justify-content: center; margin-bottom: 1rem; }
    .sidebar.sidebar-collapsed .role-badge > div { justify-content: center; }
    .sidebar.sidebar-collapsed .role-badge .w-12 { width: 2.5rem; height: 2.5rem; font-size: 0.875rem; }
    @media (max-width: 767.98px) { 
      .sidebar { transform: translateX(-100%); z-index: 60; }
      .sidebar-hidden-mobile { transform: translateX(-100%) !important; }
      .sidebar:not(.sidebar-hidden-mobile) { transform: translateX(0) !important; }
      .sidebar { width: var(--sidebar-w) !important; min-width: var(--sidebar-w) !important; } 
    }
    @media (min-width: 768px) { .sidebar { transform: translateX(0) !important; } }
    .main-with-sidebar { margin-left: var(--sidebar-w); transition: margin-left .25s ease; }
    .main-with-sidebar-collapsed { margin-left: var(--sidebar-collapsed-w); }
    @media (max-width: 767.98px) { 
      .main-with-sidebar { margin-left: 0 !important; }
      .main-with-sidebar-collapsed { margin-left: 0 !important; }
    }
    .mobile-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,.5); z-index: 55; }
    
    /* Modern nav-item styling */
    .nav-item { 
      display: flex; 
      align-items: center; 
      gap: 0.875rem; 
      padding: 0.75rem 1rem; 
      border-radius: 0.75rem; 
      transition: all 0.2s cubic-bezier(.4,0,.2,1); 
      color: #374151; 
      font-weight: 500;
      margin: 0.25rem 0;
    }
    .nav-item.active { 
      background: linear-gradient(135deg, #d1fae5 0%, #ecfdf5 100%); 
      color: #059669; 
      border-left: 4px solid #10b981; 
      font-weight: 600; 
      box-shadow: 0 2px 8px rgba(16, 185, 129, 0.15);
    }
    .nav-item:hover:not(.active) { 
      background: #ecfdf5; 
      transform: translateX(4px); 
      color: #059669;
    }
    .nav-item-locked { 
      display: flex; 
      align-items: center; 
      gap: 0.75rem; 
      padding: 0.75rem 1rem; 
      border-radius: 0.75rem; 
      background: #f9fafb; 
      cursor: not-allowed; 
      opacity: 0.6;
      margin: 0.25rem 0;
    }
    .nav-item-locked:hover { background: #f3f4f6; }
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
      transition: opacity 0.3s ease; 
      font-size: 0.8125rem;
      font-weight: 600;
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
    .tooltip-text.locked { background: #6b7280; }
    .tooltip-text.locked::before { border-right-color: #6b7280; }
    .sidebar.sidebar-collapsed .nav-item:hover .tooltip-text,
    .sidebar.sidebar-collapsed .nav-item-locked:hover .tooltip-text { opacity: 1; }
    
    /* Floating collapse button */
    .btn-collapse-outer {
      position: fixed;
      top: calc(var(--header-h) + 1rem);
      left: calc(var(--sidebar-w) - 14px);
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: white;
      border: 1px solid #d1fae5;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      z-index: 60;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.25s ease;
    }
    .btn-collapse-outer:hover {
      background: #ecfdf5;
      box-shadow: 0 4px 12px rgba(16,185,129,0.2);
    }
    .btn-collapse-outer svg {
      width: 16px;
      height: 16px;
      color: #059669;
      transition: transform 0.25s ease;
    }
    .sidebar-collapsed-state .btn-collapse-outer {
      left: calc(var(--sidebar-collapsed-w) - 14px);
    }
    .sidebar-collapsed-state .btn-collapse-outer svg {
      transform: rotate(180deg);
    }
    @media (max-width: 767.98px) {
      .btn-collapse-outer { display: none !important; }
    }
    
    /* Animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.3s ease-out; }
  </style>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
  <div id="app" class="flex flex-col">
    {{-- Header --}}
    <header class="site-header bg-white/80 border-b border-gray-200 flex items-center justify-between px-4 md:px-6 lg:px-8 shadow-sm backdrop-blur-xl">
      <div class="flex items-center gap-4">
        <button id="btnOpenMobile" class="md:hidden text-gray-700 hover:text-emerald-600 transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="flex items-center gap-3">
          <img src="/images/Logo Yayasan Bersih.png" alt="Yayasan Bersih Logo" class="w-11 h-11 object-contain" onerror="this.style.display='none'">
          <div class="flex flex-col">
            <span class="text-lg font-bold text-emerald-700">YARSI NTB</span>
            <span class="text-xs text-emerald-600">Sistem Arsip Digital</span>
          </div>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <span class="hidden sm:inline-block px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium">
          {{ ucfirst($role) }}
        </span>
        <div class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
          {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
        </div>
      </div>
    </header>

    {{-- Sidebar --}}
    <aside id="sidebar" class="sidebar sidebar-hidden-mobile border-r border-emerald-100">
      <div class="p-4 flex flex-col h-full">
        {{-- User Profile Card --}}
        <div class="role-badge mb-6 p-4 bg-white rounded-xl border border-emerald-100 shadow-sm hover:shadow-md transition-shadow">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-lg shrink-0 shadow-sm">
              {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            </div>
            <div class="role-badge-text min-w-0 flex-1">
              <div class="font-semibold text-gray-800 truncate text-sm">{{ $user->name ?? 'User' }}</div>
              <div class="text-xs text-emerald-600 font-medium mt-0.5">{{ $roleLabels[$role] ?? 'User' }}</div>
            </div>
          </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto overflow-x-hidden">
          <ul class="space-y-2">
            @foreach($menus as $menu)
              @php
                  $hasAccess = in_array($role, $menu['roles']);
                  $isActive = $currentRoute === $menu['route'];
              @endphp
              <li>
                @if($hasAccess)
                  {{-- Menu bisa diakses --}}
                  <a href="{{ route($menu['route']) }}" 
                     class="nav-item tooltip {{ $isActive ? 'active' : '' }}" 
                     title="{{ $menu['name'] }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                      {!! $menu['icon'] !!}
                    </svg>
                    <span class="nav-label text-sm">{{ $menu['name'] }}</span>
                    <span class="tooltip-text">{{ $menu['name'] }}</span>
                  </a>
                @else
                  {{-- Menu TERKUNCI --}}
                  <div class="nav-item-locked tooltip" 
                       title="🔒 Akses terbatas untuk {{ implode(', ', array_map(fn($r) => ucfirst($r), $menu['roles'])) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                      {!! $menu['icon'] !!}
                    </svg>
                    <span class="nav-label text-sm text-gray-400">{{ $menu['name'] }}</span>
                    {{-- Lock Icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 ml-auto shrink-0 lock-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span class="tooltip-text locked">🔒 Khusus {{ implode('/', array_map(fn($r) => ucfirst($r), $menu['roles'])) }}</span>
                  </div>
                @endif
              </li>
            @endforeach
          </ul>
        </nav>

        {{-- Footer --}}
        <div class="sidebar-footer mt-auto border-t pt-4">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-md bg-red-50 border border-red-200 hover:bg-red-100 transition-smooth text-red-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
              </svg>
              <span class="nav-label text-sm font-medium">Logout</span>
            </button>
          </form>
        </div>
      </div>
    </aside>

    {{-- Mobile overlay --}}
    <div id="mobileOverlay" class="mobile-overlay hidden"></div>

    {{-- Floating collapse button (outside sidebar) --}}
    <button id="btnCollapse" class="btn-collapse-outer hidden md:flex" title="Toggle Sidebar">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>

    {{-- Main content --}}
    <main id="main" class="transition-smooth main-with-sidebar flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto" style="margin-top:0">
      <div class="max-w-7xl mx-auto">
        {{-- Flash Messages --}}
        @if(session('success'))
          <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg flex items-center gap-2 animate-fade-in">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-green-700 hover:text-green-900">&times;</button>
          </div>
        @endif
        
        @if(session('error'))
          <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg flex items-center gap-2 animate-fade-in">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-red-700 hover:text-red-900">&times;</button>
          </div>
        @endif

        {{-- Page header --}}
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard</h1>
          <p class="text-gray-600 mt-2">Selamat datang! Anda login sebagai <span class="px-3 py-1 bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-lg text-emerald-700 font-semibold border border-emerald-200">{{ $roleLabels[$role] ?? ucfirst($role) }}</span></p>
        </div>

        {{-- Statistics cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="bg-white rounded-2xl shadow-lg shadow-emerald-100/50 p-6 border border-gray-100 hover:shadow-xl hover:shadow-emerald-100 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Surat Masuk</p>
                <p id="statSuratMasuk" class="text-4xl font-bold text-gray-900 mt-3">0</p>
              </div>
              <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-4 shadow-lg shadow-emerald-500/30">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8.5A2.5 2.5 0 015.5 6h13A2.5 2.5 0 0121 8.5v7A2.5 2.5 0 0118.5 18h-13A2.5 2.5 0 013 15.5v-7zM3 8.5l7 4 7-4"/></svg>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-2xl shadow-lg shadow-blue-100/50 p-6 border border-gray-100 hover:shadow-xl hover:shadow-blue-100 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Surat Keluar</p>
                <p id="statSuratKeluar" class="text-4xl font-bold text-gray-900 mt-3">0</p>
              </div>
              <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-4 shadow-lg shadow-blue-500/30">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12l18-7-7 18-3-8-8-3z"/></svg>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-2xl shadow-lg shadow-purple-100/50 p-6 border border-gray-100 hover:shadow-xl hover:shadow-purple-100 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Arsip Digital</p>
                <p id="statArsipDigital" class="text-4xl font-bold text-gray-900 mt-3">0</p>
              </div>
              <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-4 shadow-lg shadow-purple-500/30">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M8 7v-2a1 1 0 011-1h6a1 1 0 011 1v2M21 7l-1 13a2 2 0 01-2 2H6a2 2 0 01-2-2L3 7"/></svg>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-2xl shadow-lg shadow-amber-100/50 p-6 border border-gray-100 hover:shadow-xl hover:shadow-amber-100 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Pengguna Aktif</p>
                <p id="statPenggunaAktif" class="text-4xl font-bold text-gray-900 mt-3">0</p>
              </div>
              <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-4 shadow-lg shadow-amber-500/30">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 10H9M21 20.354A4 4 0 0012.646 15H11.354A4 4 0 003 20.354"/></svg>
              </div>
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
        document.body.classList.add('sidebar-collapsed-state');
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
          document.body.classList.add('sidebar-collapsed-state');
        } else {
          sidebar.classList.remove('sidebar-collapsed');
          document.body.classList.remove('sidebar-collapsed-state');
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
        const [suratMasuk, suratKeluar, arsipDigital, penggunaAktif] = await Promise.all([
          fetch('/api/surat-masuk').then(r => r.json()),
          fetch('/api/surat-keluar').then(r => r.json()),
          fetch('/api/arsip-digital').then(r => r.json()),
          fetch('/api/pengguna-aktif').then(r => r.json())
        ]);

        document.getElementById('statSuratMasuk').textContent = suratMasuk.count || 0;
        document.getElementById('statSuratKeluar').textContent = suratKeluar.count || 0;
        document.getElementById('statArsipDigital').textContent = arsipDigital.count || 0;
        document.getElementById('statPenggunaAktif').textContent = penggunaAktif.count || 0;
      } catch (error) {
        console.error('Error loading statistics:', error);
      }
    }

    loadStatistics();
  </script>
</body>
</html>


