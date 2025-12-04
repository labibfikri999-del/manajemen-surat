{{-- resources/views/laporan.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Laporan — YARSI NTB</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    @media (max-width: 767.98px) { .sidebar { width: var(--sidebar-w) !important; min-width: var(--sidebar-w) !important; } }
    @media (min-width: 768px) { .sidebar { transform: translateX(0) !important; } }
    @media (max-width: 767.98px) { .sidebar { transform: translateX(-100%); } }
    .nav-item.active { background: #d1fae5; color: #047857; border-left: 3px solid #047857; }
    .nav-item:hover { background: #d1fae5; transform: translateX(2px); }
    .tooltip-text { position: absolute; left: calc(100% + 10px); top: 50%; transform: translateY(-50%); background: #065f46; color: white; padding: 0.5rem 0.75rem; border-radius: 0.375rem; white-space: nowrap; opacity: 0; pointer-events: none; transition: opacity 0.3s ease, transform 0.3s ease; font-size: 0.8125rem; font-weight: 600; letter-spacing: 0.5px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.2), 0 4px 6px -2px rgba(0,0,0,0.1); z-index: 1000; }
    .tooltip-text::before { content: ''; position: absolute; right: 100%; top: 50%; transform: translateY(-50%); border: 5px solid transparent; border-right-color: #065f46; }
    .sidebar.sidebar-collapsed .nav-item:hover .tooltip-text { opacity: 1; transform: translateY(-50%) translateX(0); }
    .tooltip-text { position: absolute; left: calc(100% + 10px); top: 50%; transform: translateY(-50%); background: #047857; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; white-space: nowrap; opacity: 0; pointer-events: none; transition: opacity 0.2s, transform 0.2s; font-size: 0.875rem; font-weight: 500; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); z-index: 1000; }`n    .tooltip-text::before { content: ''; position: absolute; right: 100%; top: 50%; transform: translateY(-50%); border: 6px solid transparent; border-right-color: #047857; }`n    .nav-item:hover .tooltip-text { opacity: 1; transform: translateY(-50%) translateX(0); }
    .tooltip.show-tooltip .tooltip-text { position: absolute; left: calc(100% + 10px); top: 50%; transform: translateY(-50%); background: #047857; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; white-space: nowrap; opacity: 0; pointer-events: none; transition: opacity 0.2s, transform 0.2s; font-size: 0.875rem; font-weight: 500; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); z-index: 1000; }`n    .tooltip-text::before { content: ''; position: absolute; right: 100%; top: 50%; transform: translateY(-50%); border: 6px solid transparent; border-right-color: #047857; }`n    .nav-item:hover .tooltip-text { opacity: 1; transform: translateY(-50%) translateX(0); }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
  </style>
</head>
<body class="bg-emerald-50">
  <div id="app" class="flex flex-col">
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
      <div class="text-sm text-emerald-600">Laporan</div>
    </header>

    <aside id="sidebar" class="sidebar sidebar-hidden-mobile border-r border-emerald-100">
      <div class="p-4">
        <button id="btnCollapse" class="hidden md:flex w-full items-center justify-center mb-4 p-2 rounded hover:bg-emerald-50 text-emerald-700">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>

        <nav class="mt-5">
          <ul class="space-y-1">
            <li><a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9.75L12 3l9 6.75V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V9.75z"/></svg><span class="nav-label text-sm font-medium">Dashboard</span><span class="tooltip-text">Dashboard</span></a></li>
            <li><a href="{{ route('surat-masuk') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8.5A2.5 2.5 0 015.5 6h13A2.5 2.5 0 0121 8.5v7A2.5 2.5 0 0118.5 18h-13A2.5 2.5 0 013 15.5v-7zM3 8.5l7 4 7-4"/></svg><span class="nav-label text-sm">Surat Masuk</span><span class="tooltip-text">Surat Masuk</span></a></li>
            <li><a href="{{ route('surat-keluar') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2 12l18-7-7 18-3-8-8-3z"/></svg><span class="nav-label text-sm">Surat Keluar</span><span class="tooltip-text">Surat Keluar</span></a></li>
            <li><a href="{{ route('arsip-digital') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7h18M8 7v-2a1 1 0 011-1h6a1 1 0 011 1v2M21 7l-1 13a2 2 0 01-2 2H6a2 2 0 01-2-2L3 7"/></svg><span class="nav-label text-sm">Arsip Digital</span><span class="tooltip-text">Arsip Digital</span></a></li>
            <li><a href="#" class="flex items-center gap-3 p-2 rounded-md nav-item active tooltip relative"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3v18h18M9 17V9M13 17V5M17 17v-4"/></svg><span class="nav-label text-sm">Laporan</span><span class="tooltip-text">Laporan</span></a></li>
            <li><a href="{{ route('data-master') }}" class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 2C7.6 2 4 3.8 4 6v12c0 2.2 3.6 4 8 4s8-1.8 8-4V6c0-2.2-3.6-4-8-4zM4 10c0 2.2 3.6 4 8 4s8-1.8 8-4"/></svg><span class="nav-label text-sm">Data Master</span><span class="tooltip-text">Data Master</span></a></li>
          </ul>
        </nav>

        <div class="sidebar-footer">
          <div class="mt-6 border-t pt-4">
            <div class="text-xs text-emerald-600 mb-2 sidebar-brand-text">Admin</div>
            <button type="button" class="logout-btn w-full flex items-center gap-3 px-3 py-2 rounded-md border border-emerald-100 hover:bg-emerald-50 transition-smooth text-emerald-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" /></svg>
              <span class="logout-label nav-label text-sm font-medium text-emerald-700">Logout</span>
            </button>
          </div>
        </div>
      </div>
    </aside>

    <div id="mobileOverlay" class="mobile-overlay hidden"></div>

    <main id="main" class="transition-smooth main-with-sidebar flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
      <div class="max-w-7xl mx-auto">
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-emerald-900">Laporan</h1>
          <p class="text-emerald-600 mt-2">Buat dan lihat laporan manajemen surat</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 mb-6">
          <button id="btnBuatLaporan" class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Laporan
          </button>
          <button id="btnCetakLaporan" class="border border-emerald-300 text-emerald-700 px-4 py-2 rounded hover:bg-emerald-50 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H9a2 2 0 00-2 2v2a2 2 0 002 2h10a2 2 0 002-2v-2a2 2 0 00-2-2h-2m-4-4V9m0 4v10"/></svg>
            Cetak
          </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
          <div class="lg:col-span-2 space-y-4 md:space-y-6">
            <div class="bg-white rounded-lg shadow p-4 md:p-6">
              <h2 class="text-base md:text-lg font-semibold text-emerald-900 mb-4">Statistik Surat Masuk & Keluar (Bulan Ini)</h2>
              <canvas id="chartSuratMonthly" height="100"></canvas>
            </div>

            <div class="bg-white rounded-lg shadow p-4 md:p-6">
              <h2 class="text-base md:text-lg font-semibold text-emerald-900 mb-4">Perbandingan Masuk vs Keluar</h2>
              <div class="space-y-4">
                <div>
                  <div class="flex justify-between mb-2">
                    <span class="text-sm text-emerald-700">Surat Masuk</span>
                    <span class="text-sm font-semibold text-emerald-900" id="statMasuk">0 surat</span>
                  </div>
                  <div class="w-full bg-emerald-200 rounded-full h-3">
                    <div class="bg-emerald-500 h-3 rounded-full" id="progressMasuk" style="width: 0%;"></div>
                  </div>
                </div>
                <div>
                  <div class="flex justify-between mb-2">
                    <span class="text-sm text-emerald-700">Surat Keluar</span>
                    <span class="text-sm font-semibold text-emerald-900" id="statKeluar">0 surat</span>
                  </div>
                  <div class="w-full bg-emerald-200 rounded-full h-3">
                    <div class="bg-green-500 h-3 rounded-full" id="progressKeluar" style="width: 0%;"></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 md:p-6">
              <h2 class="text-base md:text-lg font-semibold text-emerald-900 mb-4">Distribusi Arsip Digital</h2>
              <canvas id="chartArsipType" height="100"></canvas>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-4 md:p-6">
            <h2 class="text-base md:text-lg font-semibold text-emerald-900 mb-4">Ringkasan Statistik</h2>
            <div class="space-y-4">
              <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                <p class="text-xs text-emerald-600">Surat Masuk</p>
                <p class="text-2xl font-bold text-emerald-900" id="totalMasuk">0</p>
              </div>
              <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                <p class="text-xs text-green-600">Surat Keluar</p>
                <p class="text-2xl font-bold text-green-900" id="totalKeluar">0</p>
              </div>
              <div class="p-4 bg-purple-50 rounded-lg border border-purple-100">
                <p class="text-xs text-purple-600">Arsip Digital</p>
                <p class="text-2xl font-bold text-purple-900" id="totalArsip">0</p>
              </div>
            </div>

            <div class="mt-6 pt-6 border-t border-emerald-100">
              <h3 class="text-sm font-semibold text-emerald-900 mb-3">Laporan Terbaru</h3>
              <ul class="space-y-2 text-sm">
                <li class="p-2 bg-emerald-50 rounded border border-emerald-100 hover:bg-emerald-100 cursor-pointer">
                  <p class="font-medium text-emerald-900">Laporan Harian</p>
                  <p class="text-xs text-emerald-500 mt-1">Perbarui setiap hari</p>
                </li>
                <li class="p-2 bg-blue-50 rounded border border-blue-100 hover:bg-blue-100 cursor-pointer">
                  <p class="font-medium text-blue-900">Laporan Bulanan</p>
                  <p class="text-xs text-blue-500 mt-1">Perbarui setiap bulan</p>
                </li>
                <li class="p-2 bg-indigo-50 rounded border border-indigo-100 hover:bg-indigo-100 cursor-pointer">
                  <p class="font-medium text-indigo-900">Laporan Tahunan</p>
                  <p class="text-xs text-indigo-500 mt-1">Perbarui setiap tahun</p>
                </li>
              </ul>
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
  </div>

  <script>
    let chartSuratMonthly = null;
    let chartArsipType = null;

    async function loadStatistics() {
      try {
        const [resMasuk, resKeluar, resArsip] = await Promise.all([
          fetch('/api/surat-masuk'),
          fetch('/api/surat-keluar'),
          fetch('/api/arsip-digital')
        ]);

        const dataMasuk = await resMasuk.json();
        const dataKeluar = await resKeluar.json();
        const dataArsip = await resArsip.json();

        const totalMasuk = Array.isArray(dataMasuk) ? dataMasuk.length : 0;
        const totalKeluar = Array.isArray(dataKeluar) ? dataKeluar.length : 0;
        const totalArsip = Array.isArray(dataArsip) ? dataArsip.length : 0;

        // Update statistics
        document.getElementById('totalMasuk').textContent = totalMasuk;
        document.getElementById('totalKeluar').textContent = totalKeluar;
        document.getElementById('totalArsip').textContent = totalArsip;

        // Update comparison bars
        const total = totalMasuk + totalKeluar;
        if (total > 0) {
          const percentMasuk = (totalMasuk / total * 100).toFixed(1);
          const percentKeluar = (totalKeluar / total * 100).toFixed(1);
          
          document.getElementById('statMasuk').textContent = `${totalMasuk} surat (${percentMasuk}%)`;
          document.getElementById('statKeluar').textContent = `${totalKeluar} surat (${percentKeluar}%)`;
          document.getElementById('progressMasuk').style.width = percentMasuk + '%';
          document.getElementById('progressKeluar').style.width = percentKeluar + '%';
        }

        // Initialize charts
        initCharts(dataMasuk, dataArsip);
      } catch(e) {
        console.error('Error loading statistics:', e);
      }
    }

    function initCharts(dataMasuk, dataArsip) {
      // Chart 1: Monthly Surat Data
      const ctxMonthly = document.getElementById('chartSuratMonthly')?.getContext('2d');
      if (ctxMonthly) {
        // Group by month
        const monthData = {};
        dataMasuk.forEach(item => {
          if (item.tanggal_diterima) {
            const month = new Date(item.tanggal_diterima).toLocaleDateString('id-ID', { month: 'short' });
            monthData[month] = (monthData[month] || 0) + 1;
          }
        });

        const labels = Object.keys(monthData).length > 0 
          ? Object.keys(monthData) 
          : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        if (chartSuratMonthly) chartSuratMonthly.destroy();
        
        chartSuratMonthly = new Chart(ctxMonthly, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Surat Diterima',
              data: labels.map(m => monthData[m] || 0),
              backgroundColor: '#0ea5e9',
              borderColor: '#0284c7',
              borderWidth: 1,
              borderRadius: 4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
              legend: { display: true, position: 'top' }
            },
            scales: {
              y: { beginAtZero: true }
            }
          }
        });
      }

      // Chart 2: Arsip Digital Distribution (Pie)
      const ctxArsip = document.getElementById('chartArsipType')?.getContext('2d');
      if (ctxArsip && dataArsip.length > 0) {
        const typeCount = {};
        dataArsip.forEach(item => {
          const type = item.tipe || 'Lainnya';
          typeCount[type] = (typeCount[type] || 0) + 1;
        });

        if (chartArsipType) chartArsipType.destroy();

        chartArsipType = new Chart(ctxArsip, {
          type: 'doughnut',
          data: {
            labels: Object.keys(typeCount),
            datasets: [{
              data: Object.values(typeCount),
              backgroundColor: ['#0ea5e9', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
              borderColor: '#fff',
              borderWidth: 2
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
              legend: { display: true, position: 'bottom' }
            }
          }
        });
      }
    }

    // Print function
    document.getElementById('btnCetakLaporan')?.addEventListener('click', () => {
      const printWindow = window.open('', '', 'width=800,height=600');
      const content = document.querySelector('main').innerHTML;
      printWindow.document.write(`
        <html>
          <head>
            <title>Laporan</title>
            <script src="https://cdn.tailwindcss.com"><\/script>
          </head>
          <body class="bg-white p-8">
            <h1 class="text-2xl font-bold mb-6">Laporan Arsip Digital</h1>
            <div class="grid grid-cols-3 gap-4 mb-8">
              <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                <p class="text-xs text-emerald-600">Surat Masuk</p>
                <p class="text-3xl font-bold text-emerald-900">${document.getElementById('totalMasuk').textContent}</p>
              </div>
              <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                <p class="text-xs text-green-600">Surat Keluar</p>
                <p class="text-3xl font-bold text-green-900">${document.getElementById('totalKeluar').textContent}</p>
              </div>
              <div class="p-4 bg-purple-50 rounded-lg border border-purple-100">
                <p class="text-xs text-purple-600">Arsip Digital</p>
                <p class="text-3xl font-bold text-purple-900">${document.getElementById('totalArsip').textContent}</p>
              </div>
            </div>
            <p class="text-xs text-gray-500">Dicetak: ${new Date().toLocaleString('id-ID')}</p>
          </body>
        </html>
      `);
      printWindow.document.close();
      setTimeout(() => printWindow.print(), 500);
    });

    // Load on page ready
    document.addEventListener('DOMContentLoaded', loadStatistics);
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

    // Button Actions
    const btnBuatLaporan = document.getElementById('btnBuatLaporan');
    const btnCetakLaporan = document.getElementById('btnCetakLaporan');

    btnBuatLaporan.addEventListener('click', () => {
      showToast('Fitur buat laporan sedang dalam pengembangan', 'info');
    });

    btnCetakLaporan.addEventListener('click', () => {
      window.print();
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
      } else if (type === 'info') {
        toastContent.className = 'bg-white rounded-lg shadow-2xl p-4 flex items-center gap-3 min-w-[320px] max-w-md border-l-4 border-blue-500';
        toastIcon.innerHTML = '<svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
      } else {
        toastContent.className = 'bg-white rounded-lg shadow-2xl p-4 flex items-center gap-3 min-w-[320px] max-w-md border-l-4 border-red-500';
        toastIcon.innerHTML = '<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
      }
      
      toast.classList.remove('hidden');
      setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    document.getElementById('toastClose')?.addEventListener('click', () => {
      document.getElementById('toast').classList.add('hidden');
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
  </script>
</body>
</html>




