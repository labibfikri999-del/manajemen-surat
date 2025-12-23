{{-- resources/views/data-master.blade.php --}}
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
  <title>Data Master — YARSI NTB</title>
  <link rel="icon" type="image/png" href="{{ asset('images/Logo Yayasan Bersih.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @include('partials.styles')
  <style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
  </style>
</head>
<body class="bg-gray-50">
  <div id="app" class="flex flex-col">
    @include('partials.header')
    @include('partials.sidebar-menu')

    <main id="main" class="transition-smooth main-with-sidebar flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
      <div class="max-w-7xl mx-auto">
        @include('partials.flash-messages')
        
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-emerald-900">Data Master</h1>
          <p class="text-emerald-600 mt-2">Kelola data referensi sistem</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6">
          <div class="bg-white rounded-lg shadow p-3 md:p-4 cursor-pointer hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-emerald-600 text-xs md:text-sm">Klasifikasi</p>
                <p id="countKlasifikasi" class="text-xl md:text-2xl font-bold text-emerald-900">...</p>
              </div>
              <svg class="w-8 h-8 md:w-10 md:h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-3 md:p-4 cursor-pointer hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-emerald-600 text-xs md:text-sm">Departemen</p>
                <p id="countDepartemen" class="text-xl md:text-2xl font-bold text-emerald-900">...</p>
              </div>
              <svg class="w-8 h-8 md:w-10 md:h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-3 md:p-4 cursor-pointer hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-emerald-600 text-xs md:text-sm">Pengguna</p>
                <p id="countPengguna" class="text-xl md:text-2xl font-bold text-emerald-900">...</p>
              </div>
              <svg class="w-8 h-8 md:w-10 md:h-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 10H9M21 20.354A4 4 0 0012.646 15H11.354A4 4 0 003 20.354"/></svg>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-3 md:p-4 cursor-pointer hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-emerald-600 text-xs md:text-sm">Lampiran</p>
                <p id="countLampiran" class="text-xl md:text-2xl font-bold text-emerald-900">...</p>
              </div>
              <svg class="w-8 h-8 md:w-10 md:h-10 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </div>
          </div>
        </div>

        {{-- Tabs --}}
        <div class="bg-white rounded-lg shadow">
          <div class="flex overflow-x-auto border-b border-emerald-100 scrollbar-hide">
            <button id="tabKlasifikasi" class="tab-btn px-4 md:px-6 py-4 text-emerald-900 font-medium border-b-2 border-emerald-500 hover:bg-emerald-50 whitespace-nowrap flex-shrink-0" data-tab="klasifikasi">Klasifikasi</button>
            <button id="tabDepartemen" class="tab-btn px-4 md:px-6 py-4 text-emerald-600 font-medium hover:bg-emerald-50 whitespace-nowrap flex-shrink-0" data-tab="departemen">Departemen</button>
            <button id="tabPengguna" class="tab-btn px-4 md:px-6 py-4 text-emerald-600 font-medium hover:bg-emerald-50 whitespace-nowrap flex-shrink-0" data-tab="pengguna">Pengguna</button>
            <button id="tabLampiran" class="tab-btn px-4 md:px-6 py-4 text-emerald-600 font-medium hover:bg-emerald-50 whitespace-nowrap flex-shrink-0" data-tab="lampiran">Tipe Lampiran</button>
            <button id="tabBackup" class="tab-btn px-4 md:px-6 py-4 text-emerald-600 font-medium hover:bg-emerald-50 whitespace-nowrap flex-shrink-0" data-tab="backup">Backup / Restore</button>
          </div>

          <div id="tabContent" class="p-4 md:p-6">
            
            {{-- VIEW KLASIFIKASI --}}
            <div id="viewKlasifikasi">
              <div class="flex flex-col sm:flex-row gap-3 mb-6">
                <button id="btnTambahKlasifikasi" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center justify-center gap-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                  Tambah Klasifikasi
                </button>
                <input id="searchKlasifikasi" type="text" placeholder="Cari klasifikasi..." class="flex-1 px-4 py-2 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
              </div>
              <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                      <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">No.</th>
                      <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nama</th>
                      <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                    </tr>
                  </thead>
                  <tbody id="tableBodyKlasifikasi" class="divide-y divide-gray-100"></tbody>
                </table>
              </div>
              <div id="mobileCardKlasifikasi" class="md:hidden space-y-3"></div>
            </div>

            {{-- VIEW DEPARTEMEN --}}
            <div id="viewDepartemen" class="hidden">
              <div class="flex flex-col sm:flex-row gap-3 mb-6">
                <button id="btnTambahDepartemen" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 flex items-center justify-center gap-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                  Tambah Departemen
                </button>
                <input id="searchDepartemen" type="text" placeholder="Cari departemen..." class="flex-1 px-4 py-2 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500" />
              </div>
              <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                      <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kode</th>
                      <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nama Instansi</th>
                      <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Alamat</th>
                      <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                    </tr>
                  </thead>
                  <tbody id="tableBodyDepartemen" class="divide-y divide-gray-100"></tbody>
                </table>
              </div>
              <div id="mobileCardDepartemen" class="md:hidden space-y-3"></div>
            </div>

            {{-- VIEW PENGGUNA --}}
            <div id="viewPengguna" class="hidden">
              <div class="flex flex-col sm:flex-row gap-3 mb-6">
                <button id="btnTambahPengguna" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 flex items-center justify-center gap-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                  Tambah Pengguna
                </button>
                <input id="searchPengguna" type="text" placeholder="Cari pengguna..." class="flex-1 px-4 py-2 border border-gray-300 rounded focus:border-purple-500 focus:ring-1 focus:ring-purple-500" />
              </div>
              <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                      <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nama</th>
                      <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Email</th>
                      <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Role</th>
                      <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Instansi</th>
                      <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                    </tr>
                  </thead>
                  <tbody id="tableBodyPengguna" class="divide-y divide-gray-100"></tbody>
                </table>
              </div>
              <div id="mobileCardPengguna" class="md:hidden space-y-3"></div>
            </div>

            {{-- VIEW LAMPIRAN --}}
            <div id="viewLampiran" class="hidden">
              <div class="flex flex-col sm:flex-row gap-3 mb-6">
                <button id="btnTambahLampiran" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 flex items-center justify-center gap-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                  Tambah Tipe Lampiran
                </button>
                <input id="searchLampiran" type="text" placeholder="Cari tipe lampiran..." class="flex-1 px-4 py-2 border border-gray-300 rounded focus:border-orange-500 focus:ring-1 focus:ring-orange-500" />
              </div>
              <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                      <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">No.</th>
                      <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nama</th>
                      <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kode</th>
                      <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                    </tr>
                  </thead>
                  <tbody id="tableBodyLampiran" class="divide-y divide-gray-100"></tbody>
                </table>
              </div>
              <div id="mobileCardLampiran" class="md:hidden space-y-3"></div>
            </div>

            {{-- VIEW BACKUP --}}
            <div id="viewBackup" class="hidden space-y-6 animate-fade-in">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Database Backup -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col items-center text-center hover:shadow-md transition-shadow">
                  <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-800 mb-2">Backup Database</h3>
                  <p class="text-gray-500 text-sm mb-6">Unduh data database lengkap dalam format JSON. File ini berisi semua tabel dan relasi.</p>
                  <a href="/api/backup/db" target="_blank" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors w-full md:w-auto">
                    Unduh Database (.json)
                  </a>
                </div>

                <!-- File Backup -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col items-center text-center hover:shadow-md transition-shadow">
                  <div class="w-16 h-16 bg-yellow-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-800 mb-2">Backup File Dokumen</h3>
                  <p class="text-gray-500 text-sm mb-6">Unduh semua file dokumen yang diunggah dalam satu file arsip ZIP.</p>
                  <a href="/api/backup/files" target="_blank" class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors w-full md:w-auto">
                    Unduh File (.zip)
                  </a>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </main>

    {{-- Toast Notification --}}
    <div id="toast" class="hidden fixed bottom-5 right-5 z-[200]">
      <div id="toastContent" class="bg-white rounded-lg shadow-2xl p-4 flex items-center gap-3 min-w-[320px] max-w-md border-l-4">
        <div id="toastIcon"></div>
        <p id="toastMessage" class="text-sm font-medium text-gray-800"></p>
        <button id="toastClose" class="ml-auto text-gray-400 hover:text-gray-600">×</button>
      </div>
    </div>
    <!-- (Toast logic preserved via existing script logic, markup slightly simplified above but we reuse existing) -->
    
    {{-- Modal Form --}}
    <div id="modalBackdrop" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[110] transition-opacity duration-200"></div>
    <div id="modalForm" class="hidden fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-2xl z-[120] w-full max-w-lg mx-4 transition-all duration-200 max-h-[90vh] overflow-y-auto">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">Tambah Data</h2>
          <button id="closeModal" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
        </div>
        
        <form id="dataForm" class="space-y-4" novalidate>
          <!-- Shared Fields -->
          <div id="fieldNamaContainer">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama <span class="text-red-500">*</span></label>
            <input type="text" id="formNama" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
          </div>

          <div id="fieldKodeContainer" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-2">Kode</label>
            <input type="text" id="formKode" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
          </div>

          <!-- Departemen Specific -->
          <div id="fieldAlamatContainer" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
            <textarea id="formAlamat" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
          </div>

          <!-- User Specific -->
          <div id="fieldEmailContainer" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
            <input type="email" id="formEmail" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
          </div>

          <div id="fieldPasswordContainer" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <div class="relative">
                <input type="password" id="formPassword" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none pr-10">
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                    <!-- Eye Icon (Show) -->
                    <svg id="iconEye" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Eye Off Icon (Hide) -->
                    <svg id="iconEyeOff" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <p class="text-xs text-gray-500 mt-1" id="passHelpText">Biarkan kosong jika tidak ingin mengubah password.</p>
          </div>

          <div id="fieldRoleContainer" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
            <select id="formRole" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
              <option value="staff">Staff</option>
              <option value="direktur">Direktur</option>
              <option value="instansi">Instansi</option>
            </select>
          </div>

          <div id="fieldInstansiContainer" class="hidden">
             <label class="block text-sm font-medium text-gray-700 mb-2">Instansi Asal <span class="text-red-500">*</span></label>
             <select id="formInstansi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
               <option value="">-- Pilih Instansi --</option>
               @foreach($instansis as $ins)
                 <option value="{{ $ins->id }}">{{ $ins->nama }}</option>
               @endforeach
             </select>
          </div>

          <div id="fieldJabatanContainer" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
            <input type="text" id="formJabatan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
          </div>
          
          <div id="fieldTeleponContainer" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
            <input type="text" id="formTelepon" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
          </div>

          <div id="fieldTelegramContainer" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-2">ID Telegram</label>
            <input type="text" id="formTelegram" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Contoh: 123456789">
            <p class="text-xs text-gray-500 mt-1">
              Wajib untuk Direktur & Staff agar menerima notifikasi. 
              <br>Cara dapat ID: Chat ke Bot <b>@YarsiSuratBot</b> (nama samaran), ketik <code>/start</code>.
            </p>
          </div>

          <div class="flex gap-3 pt-4 border-t mt-4">
            <button type="button" id="btnModalCancel" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">Batal</button>
            <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    {{-- Confirm Modal --}}
    <div id="confirmModal" class="hidden fixed inset-0 z-[150] flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
      <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-sm mx-4 transform transition-all scale-100">
        <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <h3 class="text-lg leading-6 font-medium text-gray-900" id="confirmTitle">Konfirmasi</h3>
          <div class="mt-2">
            <p class="text-sm text-gray-500" id="confirmMessage">Apakah Anda yakin?</p>
          </div>
        </div>
        <div class="mt-5 sm:mt-6 flex gap-3">
          <button type="button" id="confirmCancel" class="flex-1 inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:text-sm">
            Batal
          </button>
          <button type="button" id="confirmOk" class="flex-1 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:text-sm">
            Hapus
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Toast & Confirm Logic
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
    
    if(document.getElementById('toastClose')) {
        document.getElementById('toastClose').addEventListener('click', () => { document.getElementById('toast').classList.add('hidden'); });
    }

    function showConfirm(title, message) {
      return new Promise((resolve) => {
        const confirmModal = document.getElementById('confirmModal');
        const confirmTitle = document.getElementById('confirmTitle');
        const confirmMessage = document.getElementById('confirmMessage');
        const confirmOk = document.getElementById('confirmOk');
        const confirmCancel = document.getElementById('confirmCancel');
        
        confirmTitle.textContent = title;
        confirmMessage.textContent = message;
        confirmModal.classList.remove('hidden'); // Use class manipulation
        
        const handleOk = () => { confirmModal.classList.add('hidden'); cleanup(); resolve(true); };
        const handleCancel = () => { confirmModal.classList.add('hidden'); cleanup(); resolve(false); };
        const cleanup = () => { confirmOk.removeEventListener('click', handleOk); confirmCancel.removeEventListener('click', handleCancel); };
        
        confirmOk.addEventListener('click', handleOk);
        confirmCancel.addEventListener('click', handleCancel);
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // === DATA MASTER LOGIC ===

        // State Variables
        let currentTab = 'klasifikasi';
        let dataCache = {
          klasifikasi: [],
          departemen: [],
          pengguna: [],
          lampiran: [],
          backup: []
        };
        let currentModalType = null;
        let isEditMode = false;
        let editingId = null;

        // Tab Elements
        const tabs = {
          klasifikasi: document.getElementById('tabKlasifikasi'),
          departemen: document.getElementById('tabDepartemen'),
          pengguna: document.getElementById('tabPengguna'),
          lampiran: document.getElementById('tabLampiran'),
          backup: document.getElementById('tabBackup')
        };
        const views = {
          klasifikasi: document.getElementById('viewKlasifikasi'),
          departemen: document.getElementById('viewDepartemen'),
          pengguna: document.getElementById('viewPengguna'),
          lampiran: document.getElementById('viewLampiran'),
          backup: document.getElementById('viewBackup')
        };

        // Initialize Tabs
        Object.keys(tabs).forEach(key => {
          if(tabs[key]) {
              tabs[key].addEventListener('click', () => switchTab(key));
          }
        });

        function switchTab(key) {
          currentTab = key;
          // Update Tab Styles
          Object.keys(tabs).forEach(k => {
            if(tabs[k]) {
                if(k === key) {
                  tabs[k].classList.add('border-b-2', 'border-emerald-500', 'text-emerald-900');
                  tabs[k].classList.remove('text-emerald-600');
                } else {
                  tabs[k].classList.remove('border-b-2', 'border-emerald-500', 'text-emerald-900');
                  tabs[k].classList.add('text-emerald-600');
                }
            }
          });
          // Update Views
          Object.keys(views).forEach(k => {
            if(views[k]) {
                if(k === key) views[k].classList.remove('hidden');
                else views[k].classList.add('hidden');
            }
          });
          // Load Data
          loadData(key);
        }

        // Data Loaders
        async function loadStats() {
          try {
            const res = await fetch('/api/master/stats');
            if(res.ok) {
                const data = await res.json();
                if(document.getElementById('countKlasifikasi')) document.getElementById('countKlasifikasi').textContent = data.klasifikasi;
                if(document.getElementById('countDepartemen')) document.getElementById('countDepartemen').textContent = data.departemen;
                if(document.getElementById('countPengguna')) document.getElementById('countPengguna').textContent = data.pengguna;
                if(document.getElementById('countLampiran')) document.getElementById('countLampiran').textContent = data.lampiran;
            }
          } catch(e) { console.error('Stats error:', e); }
        }

        async function loadData(type) {
          let url = '';
          if(type === 'klasifikasi') url = '/api/klasifikasi-list';
          else if(type === 'departemen') url = '/api/departemen-list';
          else if(type === 'pengguna') url = '/api/pengguna-list';
          else if(type === 'lampiran') url = '/api/lampiran-list';
          else if(type === 'backup') return; // Static view

          try {
            const res = await fetch(url + '?t=' + new Date().getTime()); // Cache busting
            if(res.ok) {
                const data = await res.json();
                dataCache[type] = data;
                renderData(type);
            }
          } catch(e) { console.error(`Load ${type} error:`, e); }
        }

        function renderData(type) {
          if (type === 'klasifikasi') renderKlasifikasi(dataCache.klasifikasi);
          if (type === 'departemen') renderDepartemen(dataCache.departemen);
          if (type === 'pengguna') renderPengguna(dataCache.pengguna);
          if (type === 'lampiran') renderLampiran(dataCache.lampiran);
          if (type === 'backup') return;
        }

        // Renders
        function renderKlasifikasi(data) {
          const tbody = document.getElementById('tableBodyKlasifikasi');
          const mobile = document.getElementById('mobileCardKlasifikasi');
          if(!tbody || !mobile) return;
          tbody.innerHTML = ''; mobile.innerHTML = '';
          
          data.forEach((item, i) => {
            const row = `
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">${i+1}</td>
                <td class="px-6 py-4 font-medium text-gray-900">${item.nama}</td>
                <td class="px-6 py-4 text-left">
                  ${btnActions(item.id, item.nama, 'klasifikasi')}
                </td>
              </tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
            
            const card = `
              <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                 <div class="mb-3"><div class="text-xs text-emerald-600 mb-1">No. ${i+1}</div><div class="text-base font-semibold">${item.nama}</div></div>
                 <div class="flex gap-2">${btnActionsMobile(item.id, 'klasifikasi')}</div>
              </div>`;
            mobile.insertAdjacentHTML('beforeend', card);
          });
          attachListeners('klasifikasi');
        }

        function renderDepartemen(data) {
          const tbody = document.getElementById('tableBodyDepartemen');
          const mobile = document.getElementById('mobileCardDepartemen');
          if(!tbody || !mobile) return;
          tbody.innerHTML = ''; mobile.innerHTML = '';

          data.forEach((item) => {
            const row = `
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">${item.kode || '-'}</td>
                <td class="px-6 py-4 font-medium text-gray-900">${item.nama}</td>
                 <td class="px-6 py-4 text-sm text-gray-600">${item.alamat || '-'}</td>
                <td class="px-6 py-4 text-left">
                  ${btnActions(item.id, item.nama, 'departemen')}
                </td>
              </tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
            
            const card = `
              <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                 <div class="mb-3">
                   <div class="text-xs text-gray-500 mb-1">${item.kode || '-'}</div>
                   <div class="text-base font-semibold text-gray-900">${item.nama}</div>
                   <div class="text-sm text-gray-600 mt-1">${item.alamat || '-'}</div>
                 </div>
                 <div class="flex gap-2">${btnActionsMobile(item.id, 'departemen')}</div>
              </div>`;
            mobile.insertAdjacentHTML('beforeend', card);
          });
          attachListeners('departemen');
        }

        function renderPengguna(data) {
          const tbody = document.getElementById('tableBodyPengguna');
          const mobile = document.getElementById('mobileCardPengguna');
          if(!tbody || !mobile) return;
          tbody.innerHTML = ''; mobile.innerHTML = '';

          data.forEach((item) => {
            const roleBadge = item.role === 'direktur' ? 'bg-purple-100 text-purple-800' :
                              item.role === 'staff' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800';
            const instansiName = item.instansi ? item.instansi.nama : '-';
            
            const row = `
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-medium text-gray-900">${item.name}</td>
                <td class="px-6 py-4 text-sm text-gray-600">${item.email}</td>
                <td class="px-6 py-4"><span class="px-2 py-1 rounded text-xs font-semibold ${roleBadge}">${item.role.toUpperCase()}</span></td>
                <td class="px-6 py-4 text-sm text-gray-600">${instansiName}</td>
                <td class="px-6 py-4 text-left">
                  ${btnActions(item.id, item.name, 'pengguna')}
                </td>
              </tr>`;
            tbody.insertAdjacentHTML('beforeend', row);

            const card = `
              <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                 <div class="mb-3">
                   <div class="flex justify-between items-start">
                     <div class="text-base font-semibold text-gray-900">${item.name}</div>
                     <span class="px-2 py-1 rounded text-xs font-semibold ${roleBadge}">${item.role.toUpperCase()}</span>
                   </div>
                   <div class="text-sm text-gray-600 mt-1">${item.email}</div>
                   <div class="text-sm text-gray-500 mt-1">${instansiName}</div>
                 </div>
                 <div class="flex gap-2">${btnActionsMobile(item.id, 'pengguna')}</div>
              </div>`;
            mobile.insertAdjacentHTML('beforeend', card);
          });
          attachListeners('pengguna');
        }

        function renderLampiran(data) {
          const tbody = document.getElementById('tableBodyLampiran');
          const mobile = document.getElementById('mobileCardLampiran');
          if(!tbody || !mobile) return;
          tbody.innerHTML = ''; mobile.innerHTML = '';

          data.forEach((item, i) => {
            const row = `
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">${i+1}</td>
                <td class="px-6 py-4 font-medium text-gray-900">${item.nama}</td>
                <td class="px-6 py-4 text-sm text-gray-600">${item.kode || '-'}</td>
                <td class="px-6 py-4 text-left">
                  ${btnActions(item.id, item.nama, 'lampiran')}
                </td>
              </tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
            
            const card = `
              <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                 <div class="mb-3">
                   <div class="text-xs text-gray-500 mb-1">No. ${i+1}</div>
                   <div class="text-base font-semibold text-gray-900">${item.nama}</div>
                   <div class="text-sm text-gray-600 mt-1">${item.kode || '-'}</div>
                 </div>
                 <div class="flex gap-2">${btnActionsMobile(item.id, 'lampiran')}</div>
              </div>`;
            mobile.insertAdjacentHTML('beforeend', card);
          });
          attachListeners('lampiran');
        }

        function btnActions(id, name, type) {
          return `
            <button class="inline-flex items-center gap-1 px-3 py-1.5 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600 transition mr-2 btn-edit" data-id="${id}" data-type="${type}">Edit</button>
            <button class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition btn-delete" data-id="${id}" data-type="${type}" data-name="${name}">Hapus</button>
          `;
        }
        function btnActionsMobile(id, type) {
          return `
            <button class="flex-1 px-3 py-2 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600 transition btn-edit" data-id="${id}" data-type="${type}">Edit</button>
            <button class="flex-1 px-3 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition btn-delete" data-id="${id}" data-type="${type}">Hapus</button>
          `;
        }

        function attachListeners(type) {
          const parent = document.getElementById(`view${type.charAt(0).toUpperCase() + type.slice(1)}`);
          if(!parent) return;
          parent.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', () => openModal(type, true, btn.dataset.id));
          });
          parent.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', async () => {
              const ok = await showConfirm('Konfirmasi', `Hapus data ${btn.dataset.name || 'ini'}?`);
              if(ok) deleteItem(type, btn.dataset.id);
            });
          });
        }

        // === MODAL LOGIC ===
        const modalBackdrop = document.getElementById('modalBackdrop');
        const modalForm = document.getElementById('modalForm');
        const dataForm = document.getElementById('dataForm');
        
        if(document.getElementById('closeModal')) document.getElementById('closeModal').addEventListener('click', closeModal);
        if(document.getElementById('btnModalCancel')) document.getElementById('btnModalCancel').addEventListener('click', closeModal);

        // Add Listeners for "Tambah" buttons
        if(document.getElementById('btnTambahKlasifikasi')) document.getElementById('btnTambahKlasifikasi').addEventListener('click', () => openModal('klasifikasi'));
        if(document.getElementById('btnTambahDepartemen')) document.getElementById('btnTambahDepartemen').addEventListener('click', () => openModal('departemen'));
        if(document.getElementById('btnTambahPengguna')) document.getElementById('btnTambahPengguna').addEventListener('click', () => openModal('pengguna'));
        if(document.getElementById('btnTambahLampiran')) document.getElementById('btnTambahLampiran').addEventListener('click', () => openModal('lampiran'));

        // Field Containers
        const fields = {
          nama: document.getElementById('fieldNamaContainer'),
          kode: document.getElementById('fieldKodeContainer'),
          alamat: document.getElementById('fieldAlamatContainer'),
          email: document.getElementById('fieldEmailContainer'),
          password: document.getElementById('fieldPasswordContainer'),
          role: document.getElementById('fieldRoleContainer'),
          instansi: document.getElementById('fieldInstansiContainer'),
          jabatan: document.getElementById('fieldJabatanContainer'),
          jabatan: document.getElementById('fieldJabatanContainer'),
          telepon: document.getElementById('fieldTeleponContainer'),
          telegram: document.getElementById('fieldTelegramContainer')
        };

        function openModal(type, isEdit = false, id = null) {
          currentModalType = type;
          isEditMode = isEdit;
          editingId = id;
          
          // Reset Form & Required Attributes
          dataForm.reset();
          if(document.getElementById('formPassword')) document.getElementById('formPassword').required = false; 
          
          if(document.getElementById('modalTitle')) document.getElementById('modalTitle').textContent = (isEdit ? 'Edit ' : 'Tambah ') + type.charAt(0).toUpperCase() + type.slice(1);
          
          // Hide all fields first
          Object.keys(fields).forEach(k => {
             if(fields[k]) fields[k].classList.add('hidden');
          });

          // Show relevant fields
          if (type === 'klasifikasi') {
            if(fields.nama) fields.nama.classList.remove('hidden');
          } 
          else if (type === 'departemen') {
            if(fields.kode) fields.kode.classList.remove('hidden');
            if(fields.nama) fields.nama.classList.remove('hidden');
            if(fields.alamat) fields.alamat.classList.remove('hidden');
          }
          else if (type === 'pengguna') {
            if(fields.nama) fields.nama.classList.remove('hidden');
            if(fields.email) fields.email.classList.remove('hidden');
            if(fields.password) fields.password.classList.remove('hidden'); // Optional if edit
            
            // Handling password hint
            const hint = document.getElementById('passHelpText');
            if(hint) hint.style.display = isEdit ? 'block' : 'none';
            if(document.getElementById('formPassword')) document.getElementById('formPassword').required = !isEdit;

            if(fields.role) fields.role.classList.remove('hidden');
            if(fields.instansi) fields.instansi.classList.remove('hidden'); // Initially show, logic handles if needed
            if(fields.jabatan) fields.jabatan.classList.remove('hidden');
            if(fields.telepon) fields.telepon.classList.remove('hidden');
            if(fields.telegram) fields.telegram.classList.remove('hidden');
            
            // Trigger role change logic manually
            handleRoleChange();
          }
          else if (type === 'lampiran') {
            if(fields.nama) fields.nama.classList.remove('hidden');
            if(fields.kode) fields.kode.classList.remove('hidden');
          }

          // If Edit, Fill Data
          if (isEdit && id) {
            const item = dataCache[type].find(d => d.id == id);
            if (item) {
              if (document.getElementById('formNama')) document.getElementById('formNama').value = item.nama || item.name || '';
              if (document.getElementById('formKode')) document.getElementById('formKode').value = item.kode || '';
              if (document.getElementById('formAlamat')) document.getElementById('formAlamat').value = item.alamat || '';
              if (document.getElementById('formEmail')) document.getElementById('formEmail').value = item.email || '';
              if (document.getElementById('formPassword')) document.getElementById('formPassword').value = item.plain_password || '';
              if (document.getElementById('formRole')) document.getElementById('formRole').value = item.role || 'staff';
              if (document.getElementById('formJabatan')) document.getElementById('formJabatan').value = item.jabatan || '';
              if (document.getElementById('formTelepon')) document.getElementById('formTelepon').value = item.telepon || '';
              if (document.getElementById('formTelegram')) document.getElementById('formTelegram').value = item.telegram_chat_id || '';
              if (document.getElementById('formInstansi') && item.instansi_id) document.getElementById('formInstansi').value = item.instansi_id;
              
              if(type === 'pengguna') handleRoleChange();
            }
          }

          if(modalBackdrop) modalBackdrop.classList.remove('hidden');
          if(modalForm) modalForm.classList.remove('hidden');
        }

        function closeModal() {
          if(modalBackdrop) modalBackdrop.classList.add('hidden');
          if(modalForm) modalForm.classList.add('hidden');
        }

        // Logic Toggle Password
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('formPassword');
        const iconEye = document.getElementById('iconEye');
        const iconEyeOff = document.getElementById('iconEyeOff');

        if(togglePassword) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                if (type === 'text') {
                     iconEye.classList.add('hidden');
                     iconEyeOff.classList.remove('hidden');
                } else {
                     iconEye.classList.remove('hidden');
                     iconEyeOff.classList.add('hidden');
                }
            });
        }

        // Role Change Logic for Pengguna
        const formRole = document.getElementById('formRole');
        if(formRole) {
            formRole.addEventListener('change', handleRoleChange);
        }
        function handleRoleChange() {
          if(!formRole) return;
          const val = formRole.value;
          const instansiContainer = document.getElementById('fieldInstansiContainer');
          if(instansiContainer) {
              if (val === 'instansi') {
                instansiContainer.classList.remove('hidden');
              } else {
                instansiContainer.classList.add('hidden');
                document.getElementById('formInstansi').value = '';
              }
          }
        }

        // Submit Logic
        if(dataForm) {
            dataForm.addEventListener('submit', async (e) => {
              e.preventDefault();// Debug log
              
              const payload = {};
              const type = currentModalType;
              
              // Collect Data based on type
              if (document.getElementById('formNama') && document.getElementById('formNama').value) payload[type === 'pengguna' ? 'name' : 'nama'] = document.getElementById('formNama').value;
              
              if (type === 'klasifikasi') {
                // Just name
              } 
              else if (type === 'departemen') {
                payload.kode = document.getElementById('formKode').value;
                payload.alamat = document.getElementById('formAlamat').value;
              }
              else if (type === 'pengguna') {
                payload.email = document.getElementById('formEmail').value;
                if(document.getElementById('formPassword').value) payload.password = document.getElementById('formPassword').value;
                payload.role = document.getElementById('formRole').value;
                payload.jabatan = document.getElementById('formJabatan').value;
                payload.telepon = document.getElementById('formTelepon').value;
                payload.telegram_chat_id = document.getElementById('formTelegram').value;
                
                // Explicitly handle instansi_id nullification
                if(payload.role === 'instansi') {
                    payload.instansi_id = document.getElementById('formInstansi').value;
                } else {
                    payload.instansi_id = null;
                }
              }
              else if (type === 'lampiran') {
                payload.kode = document.getElementById('formKode').value;
              }

              // Determine URL and Method
              let url = '';
              let method = isEditMode ? 'PUT' : 'POST';
              
              if (type === 'klasifikasi') url = isEditMode ? `/api/klasifikasi/${editingId}` : '/api/klasifikasi-store';
              else if (type === 'departemen') url = isEditMode ? `/api/departemen/${editingId}` : '/api/departemen-store';
              else if (type === 'pengguna') url = isEditMode ? `/api/pengguna/${editingId}` : '/api/pengguna-store';
              else if (type === 'lampiran') url = isEditMode ? `/api/lampiran/${editingId}` : '/api/lampiran-store';

              try {
                const csrf = document.querySelector('meta[name="csrf-token"]').content;
                const res = await fetch(url, {
                  method: method,
                  headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json' 
                  },
                  body: JSON.stringify(payload)
                });
                
                const json = await res.json();
                if (res.ok) {
                  closeModal(); // Close first!
                  showToast('Berhasil menyimpan data!', 'success');
                  loadData(type);
                  loadStats();
                } else {
                  // Better error handling
                  let msg = json.message || 'Gagal menyimpan';
                  if(json.errors) {
                    msg = Object.values(json.errors).flat().join('<br>');
                  }
                  showToast(msg, 'error');
                }
              } catch (e) {
                console.error(e);
                showToast('Terjadi kesalahan server', 'error');
              }
            });
        }

        // Delete Logic
        async function deleteItem(type, id) {
          let url = '';
          if (type === 'klasifikasi') url = `/api/klasifikasi/${id}`;
          else if (type === 'departemen') url = `/api/departemen/${id}`;
          else if (type === 'pengguna') url = `/api/pengguna/${id}`;
          else if (type === 'lampiran') url = `/api/lampiran/${id}`;

          try {
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            const res = await fetch(url, {
              method: 'DELETE',
              headers: { 'X-CSRF-TOKEN': csrf }
            });
            if (res.ok) {
              showToast('Data berhasil dihapus', 'success');
              loadData(type);
              loadStats();
            } else {
              showToast('Gagal menghapus data', 'error');
            }
          } catch (e) { console.error(e); }
        }

        // Search Logic
        ['Klasifikasi', 'Departemen', 'Pengguna', 'Lampiran'].forEach(name => {
          const input = document.getElementById('search' + name);
          const type = name.toLowerCase();
          if(input) {
            input.addEventListener('input', (e) => {
              const q = e.target.value.toLowerCase();
              const rows = document.querySelectorAll(`#view${name} tbody tr`);
              const cards = document.querySelectorAll(`#mobileCard${name} > div`);
              
              rows.forEach(row => row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none');
              cards.forEach(card => card.style.display = card.textContent.toLowerCase().includes(q) ? '' : 'none');
            });
          }
        });

        // Initial Load
        switchTab('klasifikasi');
        loadStats();
        // Realtime polling
        setInterval(loadStats, 10000); // Poll stats every 10s
    });
  </script>
  @include('partials.scripts')
</body>
</html>
