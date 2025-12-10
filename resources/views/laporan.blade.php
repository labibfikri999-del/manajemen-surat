{{-- resources/views/laporan.blade.php --}}
@php
    $user = auth()->user();
    $role = $user->role ?? 'guest';
@endphp
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Laporan — YARSI NTB</title>
  <link rel="icon" type="image/png" href="{{ asset('images/Logo Yayasan Bersih.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @include('partials.styles')
</head>
<body class="bg-gray-50">
  <div id="app" class="flex flex-col">
    @include('partials.header')
    @include('partials.sidebar-menu')

    <main id="main" class="transition-smooth main-with-sidebar flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
      <div class="max-w-7xl mx-auto">
        @include('partials.flash-messages')
        
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
                <li onclick="window.location.href='/api/export/pdf?period=daily'" class="p-2 bg-emerald-50 rounded border border-emerald-100 hover:bg-emerald-100 cursor-pointer transition-colors">
                  <p class="font-medium text-emerald-900">Laporan Harian</p>
                  <p class="text-xs text-emerald-500 mt-1">Perbarui setiap hari</p>
                </li>
                <li onclick="window.location.href='/api/export/pdf?period=monthly'" class="p-2 bg-blue-50 rounded border border-blue-100 hover:bg-blue-100 cursor-pointer transition-colors">
                  <p class="font-medium text-blue-900">Laporan Bulanan</p>
                  <p class="text-xs text-blue-500 mt-1">Perbarui setiap bulan</p>
                </li>
                <li onclick="window.location.href='/api/export/pdf?period=yearly'" class="p-2 bg-indigo-50 rounded border border-indigo-100 hover:bg-indigo-100 cursor-pointer transition-colors">
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
        const response = await fetch('/api/laporan/stats');
        const data = await response.json();

        // Update counts
        const totalMasuk = data.surat_masuk;
        const totalKeluar = data.surat_keluar;
        const totalArsip = data.arsip_digital;

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
        } else {
             document.getElementById('statMasuk').textContent = `0 surat (0%)`;
             document.getElementById('statKeluar').textContent = `0 surat (0%)`;
             document.getElementById('progressMasuk').style.width = '0%';
             document.getElementById('progressKeluar').style.width = '0%';
        }

        // Initialize Charts with new data structure
        updateCharts(data);
      } catch(e) {
        console.error('Error loading statistics:', e);
      }
    }

    function updateCharts(data) {
       const ctxMonthly = document.getElementById('chartSuratMonthly')?.getContext('2d');
       if (ctxMonthly) {
         const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
         const valuesMasuk = data.monthly_masuk || new Array(12).fill(0);
         const valuesKeluar = data.monthly_keluar || new Array(12).fill(0);

         if (chartSuratMonthly) {
             chartSuratMonthly.data.datasets[0].data = valuesMasuk;
             // Check if dataset exists, if not add it (handling hot reload/re-init)
             if (chartSuratMonthly.data.datasets.length < 2) {
                 chartSuratMonthly.data.datasets.push({
                     label: 'Surat Keluar',
                     data: valuesKeluar,
                     backgroundColor: '#10b981',
                     borderColor: '#059669',
                     borderWidth: 1,
                     borderRadius: 4
                 });
             } else {
                 chartSuratMonthly.data.datasets[1].data = valuesKeluar;
             }
             chartSuratMonthly.update();
         } else {
             chartSuratMonthly = new Chart(ctxMonthly, {
               type: 'bar',
               data: {
                 labels: labels,
                 datasets: [
                   {
                     label: 'Surat Masuk',
                     data: valuesMasuk,
                     backgroundColor: '#0ea5e9',
                     borderColor: '#0284c7',
                     borderWidth: 1,
                     borderRadius: 4
                   },
                   {
                     label: 'Surat Keluar',
                     data: valuesKeluar,
                     backgroundColor: '#10b981', // Green
                     borderColor: '#059669',
                     borderWidth: 1,
                     borderRadius: 4
                   }
                 ]
               },
               options: {
                 responsive: true,
                 maintainAspectRatio: true,
                 plugins: { legend: { display: true, position: 'top' } },
                 scales: { y: { beginAtZero: true } }
               }
             });
         }
       }

       const ctxArsip = document.getElementById('chartArsipType')?.getContext('2d');
       if (ctxArsip) {
           const labels = data.arsip_distribution.map(d => d.label);
           const counts = data.arsip_distribution.map(d => d.count);
           
           if (chartArsipType) {
               chartArsipType.data.labels = labels;
               chartArsipType.data.datasets[0].data = counts;
               chartArsipType.update();
           } else {
               chartArsipType = new Chart(ctxArsip, {
                 type: 'doughnut',
                 data: {
                   labels: labels,
                   datasets: [{
                     data: counts,
                     backgroundColor: ['#0ea5e9', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                     borderColor: '#fff',
                     borderWidth: 2
                   }]
                 },
                 options: {
                   responsive: true,
                   maintainAspectRatio: true,
                   plugins: { legend: { display: true, position: 'bottom' } }
                 }
               });
           }
       }
    }

    // Load on page ready and poll with shorter interval for real-time feel
    document.addEventListener('DOMContentLoaded', () => {
        loadStatistics();
        setInterval(loadStatistics, 3000); // 3s polling for near real-time updates
    });

    // Button Actions
    const btnBuatLaporan = document.getElementById('btnBuatLaporan');
    if (btnBuatLaporan) {
      btnBuatLaporan.addEventListener('click', () => {
         window.location.href = '/api/export/pdf';
      });
    }

    const btnCetakLaporan = document.getElementById('btnCetakLaporan');
    if (btnCetakLaporan) {
        btnCetakLaporan.addEventListener('click', () => {
            window.location.href = '/api/export/pdf';
        });
    }

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
  </script>
  @include('partials.scripts')
</body>
</html>