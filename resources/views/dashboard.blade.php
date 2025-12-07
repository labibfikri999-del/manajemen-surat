{{-- resources/views/dashboard.blade.php --}}
@php
    $user = auth()->user();
    $role = $user->role ?? 'guest';
    
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
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  {{-- Include Shared Styles --}}
  @include('partials.styles')
</head>
<body class="bg-gray-50">
  <div id="app" class="flex flex-col">
    {{-- Include Header --}}
    @include('partials.header')

    {{-- Include Sidebar --}}
    @include('partials.sidebar-menu')

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
  
  {{-- Include Shared Scripts --}}
  @include('partials.scripts')

  <script>
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
