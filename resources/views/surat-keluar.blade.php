@php $user = auth()->user(); $role = $user->role ?? 'guest'; @endphp
{{-- resources/views/surat-keluar.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Surat Keluar — YARSI NTB</title>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_rsi_ntb_new.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
  
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
  
  <!-- Custom DataTable Styling to Override Default DataTables styles with Tailwind -->
  <style>
    /* DataTables Overrides */
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.25rem 2rem 0.25rem 0.5rem; outline: none;
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.25rem 0.75rem; outline: none; margin-left: 0.5rem;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #10b981; box-shadow: 0 0 0 1px #10b981;
    }
    table.dataTable.no-footer {
        border-bottom: 1px solid #e5e7eb;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.25em 0.75em; margin-left: 2px; border-radius: 0.375rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #10b981 !important; color: white !important; border: 1px solid #059669 !important; border-radius: 0.375rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #ecfdf5 !important; color: #065f46 !important; border: 1px solid #a7f3d0 !important;
    }
  </style>

  @include('partials.styles')
</head>
<body class="bg-emerald-50">
  <div id="app" class="flex flex-col">
    @include('partials.header')

    @include('partials.sidebar-menu')

    <main id="main" class="transition-smooth main-with-sidebar flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
      @include('partials.flash-messages')
      <div class="max-w-7xl mx-auto">
        <div class="mb-6">
          <h1 class="text-3xl font-bold text-emerald-900">Surat Keluar</h1>
          <p class="text-emerald-600 mt-2">Kelola semua surat yang keluar dari instansi</p>
        </div>

        {{-- Statistics Modernized --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6 mb-8">
          
          <!-- Card 1: Total -->
          <div class="group relative bg-white rounded-2xl p-5 md:p-6 border border-emerald-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(16,185,129,0.12)] transition-all duration-300 hover:-translate-y-1 overflow-hidden overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-emerald-100 to-transparent rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative flex items-center justify-between z-10">
               <div>
                 <div class="text-slate-500 text-xs md:text-sm font-semibold uppercase tracking-wider mb-1">TOTAL SURAT KELUAR</div>
                 <p id="statTotal" class="text-3xl md:text-4xl font-extrabold text-slate-800 bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-500">0</p>
                 <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Semua waktu
                 </p>
               </div>
               <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8.5A2.5 2.5 0 015.5 6h13A2.5 2.5 0 0121 8.5v7A2.5 2.5 0 0118.5 18h-13A2.5 2.5 0 013 15.5v-7zM3 8.5l7 4 7-4"/></svg>
               </div>
            </div>
          </div>

          <!-- Card 2: Pending -->
          <div class="group relative bg-white rounded-2xl p-5 md:p-6 border border-orange-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(249,115,22,0.12)] transition-all duration-300 hover:-translate-y-1 overflow-hidden overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-orange-100 to-transparent rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative flex items-center justify-between z-10">
               <div>
                 <div class="text-slate-500 text-xs md:text-sm font-semibold uppercase tracking-wider mb-1">DRAFT / PENDING</div>
                 <p id="statPending" class="text-3xl md:text-4xl font-extrabold text-slate-800 bg-clip-text text-transparent bg-gradient-to-r from-orange-500 to-red-500">0</p>
                 <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Belum Terkirim
                 </p>
               </div>
               <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center text-white shadow-lg shadow-orange-500/30">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
               </div>
            </div>
          </div>

          <!-- Card 3: Selesai -->
          <div class="group relative bg-white rounded-2xl p-5 md:p-6 border border-blue-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(59,130,246,0.12)] transition-all duration-300 hover:-translate-y-1 overflow-hidden overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-blue-100 to-transparent rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative flex items-center justify-between z-10">
               <div>
                 <div class="text-slate-500 text-xs md:text-sm font-semibold uppercase tracking-wider mb-1">SUDAH TERKIRIM</div>
                 <p id="statSent" class="text-3xl md:text-4xl font-extrabold text-slate-800 bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-indigo-500">0</p>
                 <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Selesai
                 </p>
               </div>
               <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
               </div>
            </div>
          </div>

        </div>

        <!-- Toolbar -->
        <div class="flex flex-col xl:flex-row flex-wrap gap-3 mb-6">
          <button id="btnTambah" class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-blue-500/30 hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2 font-medium" aria-label="Buat Surat Keluar Baru">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Surat Keluar
          </button>
          
          <div class="flex items-center gap-2 bg-white p-1.5 rounded-xl border border-emerald-100 shadow-sm">
             <input type="date" id="startDate" class="px-2 py-1 text-sm border-none focus:ring-0 text-gray-600 bg-transparent" title="Dari Tanggal">
             <span class="text-gray-400">-</span>
             <input type="date" id="endDate" class="px-2 py-1 text-sm border-none focus:ring-0 text-gray-600 bg-transparent" title="Sampai Tanggal">
             <button id="btnFilter" class="bg-emerald-100 text-emerald-700 px-4 py-1.5 rounded-lg hover:bg-emerald-200 transition-colors text-sm font-semibold">Filter</button>
             <button id="btnReset" class="text-gray-400 hover:text-red-500 px-2 transition-colors" title="Reset Filter">
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
             </button>
          </div>

          <button id="btnExport" class="bg-white border text-blue-600 border-blue-200 px-5 py-2.5 rounded-xl shadow-sm hover:bg-blue-50 hover:border-blue-300 transition-all duration-300 flex items-center justify-center gap-2 font-medium" aria-label="Export Excel">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export Excel
          </button>

          <!-- Search removed, handled by DataTables -->
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 overflow-hidden mb-6">
          <div class="p-4 md:p-6 overflow-x-auto">
            <table id="dataTableSuratKeluar" class="w-full text-left border-collapse">
              <thead>
                <!-- Thead color modernized -->
                <tr class="bg-emerald-50/50 text-emerald-900 border-b border-emerald-100">
                  <th class="px-4 py-3 text-xs font-semibold tracking-wider w-12">No.</th>
                  <th class="px-4 py-3 text-xs font-semibold tracking-wider w-40">Nomor Surat</th>
                  <th class="px-4 py-3 text-xs font-semibold tracking-wider w-32">Tgl Dibuat</th>
                  <th class="px-4 py-3 text-xs font-semibold tracking-wider">Tujuan</th>
                  <th class="px-4 py-3 text-xs font-semibold tracking-wider">Perihal</th>
                  <th class="px-4 py-3 text-xs font-semibold tracking-wider w-24">Status</th>
                  <th class="px-4 py-3 text-xs font-semibold tracking-wider text-center w-32">Aksi</th>
                </tr>
              </thead>
              <tbody id="tableBody" class="divide-y divide-emerald-50 text-sm">
                <!-- Data will be injected via JS. DataTables will take over -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>

    {{-- Toast Notification --}}
    <div id="toast" class="hidden fixed top-24 right-4 z-[100] transform transition-all duration-300 ease-out">
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
    <div id="confirmModal" style="display: none;" class="fixed inset-0 z-[120] flex items-center justify-center">
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
    <div id="modalBackdrop" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[110] transition-opacity duration-200 modal-backdrop-desktop"></div>
    <div id="modalForm" class="hidden fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-2xl z-[120] w-full max-w-lg mx-4 transition-all duration-200 max-h-[90vh] overflow-y-auto">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 id="modalTitle" class="text-2xl font-bold text-emerald-900">Tambah Surat Keluar</h2>
          <button id="closeModal" class="text-emerald-400 hover:text-emerald-600 text-2xl">×</button>
        </div>
        
        <form id="suratForm" class="space-y-4" enctype="multipart/form-data">
          <div>
            <label class="block text-sm font-medium text-emerald-700 mb-2">Nomor Surat <span class="text-red-500">*</span></label>
            <div class="flex gap-2">
                <input type="text" id="formNomorSurat" placeholder="SK-2025/001" required class="flex-1 w-full px-4 py-2 border border-emerald-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <button type="button" id="btnGenerateNomor" class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 transition font-medium whitespace-nowrap" title="Buat Nomor Otomatis">
                    ⚡ Auto Generate
                </button>
            </div>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-emerald-700 mb-2">Tanggal Keluar <span class="text-red-500">*</span></label>
            <input type="date" id="formTanggal" required class="w-full px-4 py-2 border border-emerald-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          
          <div>
            <label class="block text-sm font-medium text-emerald-700 mb-2">Tujuan <span class="text-red-500">*</span></label>
            <input type="text" id="formTujuan" placeholder="Nama Tujuan" required class="w-full px-4 py-2 border border-emerald-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          
          <div>
            <label class="block text-sm font-medium text-emerald-700 mb-2">Perihal <span class="text-red-500">*</span></label>
            <textarea id="formPerihal" placeholder="Perihal Surat" rows="3" required class="w-full px-4 py-2 border border-emerald-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"></textarea>
          </div>

          <div>
            <div id="panelUpload" class="block">
                <div class="relative">
                  <input type="file" id="formFile" multiple accept=".pdf,.png,.jpg,.jpeg,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.csv,.txt" class="w-full px-4 py-2 border border-emerald-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
                </div>
                <p class="text-xs text-emerald-500 mt-1">Format: PDF, PNG, JPG, DOC, DOCX (Max 10MB/file)</p>
                <div id="filePreview" class="hidden mt-2 p-3 bg-emerald-50 rounded-lg flex-col gap-2">
                  <div class="flex items-center justify-between w-full">
                     <span class="text-sm font-semibold text-emerald-700">Daftar File:</span>
                     <button type="button" id="removeFile" class="text-red-500 hover:text-red-700 text-sm font-medium">Clear All</button>
                  </div>
                  <ul id="fileName" class="text-sm text-emerald-700 list-disc pl-5"></ul>
                </div>
            </div>


          </div>
          
          <div class="flex gap-3 pt-4">
            <button type="button" id="btnModalCancel" class="flex-1 px-4 py-2 border border-emerald-300 text-emerald-700 rounded-lg hover:bg-emerald-50 transition">Batal</button>
            <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">Simpan</button>
          </div>
        </form>
        </form>
      </div>
    </div>
    <!-- End of Modal Form Add/Edit -->

    {{-- Modal Audit History --}}
    <div id="auditModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[130] transition-opacity duration-200"></div>
    <div id="auditModalForm" class="hidden fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-2xl z-[140] w-full max-w-lg mx-4 transition-all duration-200 max-h-[90vh] flex flex-col">
      <div class="p-6 border-b flex items-center justify-between bg-gray-50 rounded-t-lg">
        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Riwayat Aktivitas
        </h2>
        <button id="closeAuditModal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
      </div>
      <div class="p-6 overflow-y-auto flex-1">
        <div id="auditLoading" class="hidden flex justify-center py-4">
            <svg class="animate-spin h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>
        <div id="auditContent" class="space-y-4 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-300 before:to-transparent">
            <!-- Timeline items will be injected here -->
        </div>
      </div>
    </div>
  </div>

  {{-- Preview Modal --}}
  <div id="previewModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-[130] p-4 transition-all duration-300">
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col mx-auto transform transition-all scale-100">
          <div class="p-4 border-b flex items-center justify-between bg-gray-50 rounded-t-2xl">
              <div>
                  <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                      <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                      <span id="previewTitle">Preview Dokumen</span>
                  </h3>
              </div>
              <div class="flex items-center gap-2">
                  <a id="downloadBtn" href="#" target="_blank" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-white rounded-lg transition" title="Download / Buka di Tab Baru">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                  </a>
                  <button onclick="closePreviewModal()" class="p-2 text-gray-500 hover:text-red-600 hover:bg-white rounded-lg transition" title="Tutup">
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                  </button>
              </div>
          </div>
          <div class="flex-1 bg-gray-100 relative p-0 overflow-hidden rounded-b-2xl">
              <div id="previewLoading" class="absolute inset-0 flex items-center justify-center bg-white z-10">
                  <div class="flex flex-col items-center gap-3">
                      <svg class="animate-spin w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                      <p class="text-sm text-gray-500 font-medium">Memuat dokumen...</p>
                  </div>
              </div>
              <iframe id="previewFrame" class="w-full h-full border-0" onload="document.getElementById('previewLoading').classList.add('hidden')"></iframe>
              <div id="previewError" class="absolute inset-0 flex items-center justify-center bg-white hidden z-20">
                  <div class="text-center p-8 max-w-md">
                      <div class="bg-amber-100 text-amber-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                      </div>
                      <h4 class="text-lg font-bold text-gray-900 mb-2">Tidak dapat menampilkan preview</h4>
                      <p class="text-gray-600 mb-6">Format file ini mungkin tidak didukung untuk preview langsung oleh browser anda.</p>
                      <a id="downloadFallback" href="#" target="_blank" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                          Download File
                      </a>
                  </div>
              </div>
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

    // CRUD Functionality
    const btnTambah = document.getElementById('btnTambah');
    const tableBody = document.getElementById('tableBody');
    let allRowsData = [];

    async function loadData() {
      try {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        let url = '/api/surat-keluar';
        const params = new URLSearchParams();
        if(startDate) params.append('start_date', startDate);
        if(endDate) params.append('end_date', endDate);
        if(params.toString()) url += '?' + params.toString();

        const response = await fetch(url);
        const data = await response.json();
        allRowsData = data;
        
        // Calculate Statistics
        const total = data.length;
        // Assume 'Draft' is pending, everything else is sent/processed
        const pending = data.filter(item => item.status === 'Draft' || !item.status).length;
        const sent = data.filter(item => item.status && item.status !== 'Draft').length;

        const statTotal = document.getElementById('statTotal');
        const statPending = document.getElementById('statPending');
        const statSent = document.getElementById('statSent');

        if(statTotal) statTotal.textContent = total;
        if(statPending) statPending.textContent = pending;
        if(statSent) statSent.textContent = sent;

        renderTable();
      } catch (error) {
        console.error('Error:', error);
      }
    }

    // Filter & Export Listeners
    document.getElementById('btnFilter').addEventListener('click', loadData);
    
    document.getElementById('btnReset').addEventListener('click', () => {
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
        loadData();
    });

    document.getElementById('btnExport').addEventListener('click', () => {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        let url = '/api/surat-keluar/export/excel';
        const params = new URLSearchParams();
        if(startDate) params.append('start_date', startDate);
        if(endDate) params.append('end_date', endDate);
        if(params.toString()) url += '?' + params.toString();
        
        window.open(url, '_blank');
    });

    function renderTable() {
      // Remove skeleton rows
      const skeletonRows = tableBody.querySelectorAll('.skeleton-row');
      skeletonRows.forEach(row => row.remove());
      
      // Clear existing data rows
      const dataRows = tableBody.querySelectorAll('tr:not(.skeleton-row)');
      dataRows.forEach(row => row.remove());
      
      allRowsData.forEach((item, index) => {
        const row = document.createElement('tr');
        row.classList.add('hover:bg-emerald-50');
        row.dataset.id = item.id;
        
        // Create file link HTML
        let fileHtml = '<span class="text-gray-400">-</span>';
        let lampiransHtml = '';

        if (item.file_url) {
          lampiransHtml += `<button onclick="previewFileUrl('${item.file_url}', 'File Utama')" class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-800 bg-emerald-50 px-2 py-1 rounded text-xs">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            Utama
          </button>`;
        }

        if (item.lampirans && item.lampirans.length > 0) {
            item.lampirans.forEach((l, idx) => {
                const lpUrl = l.file_path.startsWith('http') ? l.file_path : '/storage/' + l.file_path;
                lampiransHtml += `<button onclick="previewFileUrl('${lpUrl}', '${l.file_name}')" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 bg-blue-50 px-2 py-1 rounded text-xs mt-1">
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  Lampiran ${idx + 1}
                </button>`;
            });
        }

        if (lampiransHtml) {
            fileHtml = `<div class="flex flex-col items-start gap-1">${lampiransHtml}</div>`;
        }
        
        row.innerHTML = `
          <td class="px-6 py-4 text-sm text-emerald-900">${index + 1}</td>
          <td class="px-6 py-4"><span class="font-medium text-emerald-900">${item.nomor_surat}</span></td>
          <td class="px-6 py-4"><span class="text-emerald-600">${item.tanggal_keluar}</span></td>
          <td class="px-6 py-4"><span class="text-emerald-600">${item.tujuan}</span></td>
          <td class="px-6 py-4"><span class="text-emerald-600">${item.perihal}</span></td>
          <td class="px-6 py-4">${fileHtml}</td>
          <td class="px-6 py-4"><span class="inline-block px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded">${item.status}</span></td>
          <td class="px-6 py-4 text-center">
            <div class="flex items-center justify-center gap-2">
              <button class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition btn-edit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
              </button>
              <button onclick="showAuditHistory('${item.id}')" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition btn-audit" title="Riwayat Aktivitas">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Riwayat
              </button>
              <button class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition btn-delete">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Hapus
              </button>
            </div>
          </td>
        `;
        tableBody.appendChild(row);
        setupRowButtons(row);
      });
    }

    function setupRowButtons(row) {
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
      const files = e.target.files;
      fileName.innerHTML = '';
      if (files.length > 0) {
        let hasError = false;
        Array.from(files).forEach(file => {
            if (file.size > 10 * 1024 * 1024) {
               showToast('File Terlalu Besar', `Gagal: ${file.name} melebihi 10MB`, 'error');
               hasError = true;
            } else {
               const li = document.createElement('li');
               li.textContent = file.name;
               fileName.appendChild(li);
            }
        });

        if (hasError) {
           this.value = '';
           fileName.innerHTML = '';
           filePreview.classList.add('hidden');
           filePreview.classList.remove('flex');
           return;
        }

        filePreview.classList.remove('hidden');
        filePreview.classList.add('flex');
      } else {
        filePreview.classList.add('hidden');
        filePreview.classList.remove('flex');
      }
    });

    removeFileBtn.addEventListener('click', function() {
      formFile.value = '';
      fileName.innerHTML = '';
      filePreview.classList.add('hidden');
      filePreview.classList.remove('flex');
    });

    function openModal(isEdit = false, rowId = null) {
      isEditMode = isEdit;
      editingRowId = rowId;
      const modalTitle = document.getElementById('modalTitle');
      const formNomorSurat = document.getElementById('formNomorSurat');
      const formTanggal = document.getElementById('formTanggal');
      const formTujuan = document.getElementById('formTujuan');
      const formPerihal = document.getElementById('formPerihal');

      // Reset file input
      formFile.value = '';
      filePreview.classList.add('hidden');

      if (isEdit && rowId) {
        // Use loose equality (==) because dataset.id is string while data.id might be number
        const item = allRowsData.find(d => d.id == rowId);
        if(!item) {
            console.error('Item not found:', rowId);
            return;
        }


        modalTitle.textContent = 'Edit Surat Keluar';
        formNomorSurat.value = item.nomor_surat;
        formTanggal.value = item.tanggal_keluar;
        formTujuan.value = item.tujuan;
        formPerihal.value = item.perihal;
      } else {
        modalTitle.textContent = 'Tambah Surat Keluar';
        formNomorSurat.value = '';
        formTanggal.value = new Date().toISOString().slice(0, 10);
        formTujuan.value = '';
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

    btnTambah.addEventListener('click', () => {
      openModal(false);
    });

    suratForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const formNomorSurat = document.getElementById('formNomorSurat');
      const formTanggal = document.getElementById('formTanggal');
      const formTujuan = document.getElementById('formTujuan');
      const formPerihal = document.getElementById('formPerihal');

      if (!formNomorSurat.value || !formTanggal.value || !formTujuan.value || !formPerihal.value) {
        showToast('Data Tidak Lengkap', 'Semua field wajib diisi', 'warning');
        return;
      }

      // Use FormData for file upload support
      const formData = new FormData();
      formData.append('nomor_surat', formNomorSurat.value);
      formData.append('tanggal_keluar', formTanggal.value);
      formData.append('tujuan', formTujuan.value);
      formData.append('perihal', formPerihal.value);
      
      // Add multiple files if selected
          if (formFile.files.length > 0) {
            for(let i=0; i<formFile.files.length; i++){
                formData.append('lampirans[]', formFile.files[i]);
            }
          }
      try {
        if (isEditMode && editingRowId) {
          // Edit mode - use POST with _method=PUT for FormData
          formData.append('_method', 'PUT');
          const response = await fetch(`/api/surat-keluar/${editingRowId}`, {
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
            if (index !== -1) allRowsData[index] = updatedData;
            renderTable();
            closeModal();
            showToast('Berhasil!', 'Data surat keluar berhasil diperbarui', 'success');
          } else {
            showToast('Gagal Memperbarui', updatedData.message || 'Gagal memperbarui data', 'error');
          }
        } else {
          const response = await fetch('/api/surat-keluar', {
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
            showToast('Berhasil!', 'Data surat keluar berhasil ditambahkan', 'success');
          } else {
            showToast('Gagal Menyimpan', newData.message || 'Gagal menyimpan data', 'error');
          }
        }
      } catch (error) {
        console.error('Error:', error);
        showToast('Error', error.message, 'error');
      }
    });

    function editRow(row) {
      const id = row.dataset.id;
      openModal(true, id);
    }

    async function deleteRow(row) {
      const id = parseInt(row.dataset.id);
      
      const confirmed = await showConfirm('Konfirmasi Hapus', 'Apakah Anda yakin ingin menghapus data ini? Data yang dihapus tidak dapat dikembalikan.');
      if (!confirmed) {
        return;
      }
      
      try {
        const response = await fetch(`/api/surat-keluar/${id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });
        if (response.ok) {
          allRowsData = allRowsData.filter(item => parseInt(item.id) !== id);
          renderTable();
          showToast('Berhasil!', 'Data berhasil dihapus', 'success');
        }
      } catch (error) {
        console.error('Error deleting:', error);
        showToast('Error', error.message, 'error');
      }
    }



    // Auto Generate Nomor Surat
    const btnGenerateNomor = document.getElementById('btnGenerateNomor');
    if (btnGenerateNomor) {
      btnGenerateNomor.addEventListener('click', async () => {
        try {
          // Disable button and show loading text
          const originalText = btnGenerateNomor.innerHTML;
          btnGenerateNomor.innerHTML = '<span class="animate-pulse">Loading...</span>';
          btnGenerateNomor.disabled = true;

          const response = await fetch('/api/surat-keluar/generate-nomor', {
            headers: {
              'Accept': 'application/json'
            }
          });
          
          if (response.ok) {
            const data = await response.json();
            document.getElementById('formNomorSurat').value = data.nomor_surat;
            showToast('Berhasil', 'Nomor surat berhasil digenerate', 'success');
          } else {
            showToast('Gagal', 'Gagal memuat nomor surat otomatis', 'error');
          }
        } catch (error) {
          console.error(error);
          showToast('Error', 'Terjadi kesalahan sistem', 'error');
        } finally {
          btnGenerateNomor.innerHTML = '⚡ Auto Generate';
          btnGenerateNomor.disabled = false;
        }
      });
    }

    // Button Cetak Daftar
    // Button Cetak Daftar - Removed
    // const btnCetak = document.getElementById('btnCetak');
    // if(btnCetak) {
    //   btnCetak.addEventListener('click', () => { ... });
    // }

    function previewFileUrl(url, title) {
        if (!url) return;
        let ext = url.split('.').pop().toLowerCase();
        if (url.includes('/download')) {
            ext = 'pdf'; // Dynamic generated PDF
        }
        showPreviewModal(url, title, ext);
    }

    function showPreviewModal(url, title, extension) {
        const modal = document.getElementById('previewModal');
        const frame = document.getElementById('previewFrame');
        const titleEl = document.getElementById('previewTitle');
        const loading = document.getElementById('previewLoading');
        const error = document.getElementById('previewError');
        const downloadBtn = document.getElementById('downloadBtn');
        const downloadFallback = document.getElementById('downloadFallback');

        // Hide main nav/header if needed
        const navbar = document.querySelector('header');
        if (navbar) navbar.style.display = 'none';

        titleEl.textContent = title;
        loading.classList.remove('hidden');
        error.classList.add('hidden');
        
        frame.src = url;
        downloadBtn.href = url;
        downloadFallback.href = url;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        const previewable = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'txt'];
        if (extension && !previewable.includes(extension)) {
            loading.classList.add('hidden');
            error.classList.remove('hidden');
        }
    }

    function closePreviewModal() {
        const modal = document.getElementById('previewModal');
        const frame = document.getElementById('previewFrame');
        
        // Restore navbar
        const navbar = document.querySelector('header');
        if (navbar) navbar.style.display = '';

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        frame.src = ''; 
    }

    // Close preview on outside click
    document.getElementById('previewModal')?.addEventListener('click', function(e) {
        if (e.target === this) closePreviewModal();
    });

    // --- Audit History Logic ---
    const auditModal = document.getElementById('auditModal');
    const auditModalForm = document.getElementById('auditModalForm');
    const closeAuditModalBtn = document.getElementById('closeAuditModal');
    const auditContent = document.getElementById('auditContent');
    const auditLoading = document.getElementById('auditLoading');

    window.showAuditHistory = async function(id) {
        auditModal.classList.remove('hidden');
        auditModalForm.classList.remove('hidden');
        auditContent.innerHTML = '';
        auditLoading.classList.remove('hidden');

        try {
            const response = await fetch(`/api/surat-keluar/${id}/audits`);
            if (!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();
            
            auditLoading.classList.add('hidden');
            
            if (data.length === 0) {
                auditContent.innerHTML = '<div class="text-center text-gray-500 py-4">Belum ada riwayat aktivitas.</div>';
                return;
            }

            let html = '';
            data.forEach(item => {
                const date = new Date(item.created_at).toLocaleString('id-ID', {day:'numeric', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'});
                const actionColors = {
                    created: 'bg-green-100 text-green-600',
                    updated: 'bg-blue-100 text-blue-600',
                    deleted: 'bg-red-100 text-red-600',
                    downloaded: 'bg-indigo-100 text-indigo-600'
                };
                const actionLabels = {
                    created: 'Dibuat',
                    updated: 'Diperbarui',
                    deleted: 'Dihapus',
                    downloaded: 'Diunduh'
                };
                const colorClass = actionColors[item.action] || 'bg-gray-100 text-gray-600';
                const label = actionLabels[item.action] || item.action;
                const userName = item.user ? item.user.name : 'Sistem';

                html += `
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-white text-gray-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 relative z-10">
                        <div class="w-2.5 h-2.5 rounded-full ${colorClass.split(' ')[0]}"></div>
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-4 rounded-xl shadow-sm border border-gray-100 relative">
                         <div class="flex items-center justify-between space-x-2 mb-1">
                             <div class="font-bold text-gray-800 text-sm">${userName}</div>
                             <time class="text-xs font-medium text-emerald-600">${date}</time>
                         </div>
                         <div class="text-sm text-gray-600">
                             <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium ${colorClass} mb-1">${label}</span>
                         </div>
                    </div>
                </div>
                `;
            });
            auditContent.innerHTML = html;
        } catch (error) {
            console.error(error);
            auditLoading.classList.add('hidden');
            auditContent.innerHTML = '<div class="text-center text-red-500 py-4">Gagal memuat riwayat aktivitas.</div>';
        }
    };

    function closeAuditModal() {
        auditModal.classList.add('hidden');
        auditModalForm.classList.add('hidden');
    }

    if (closeAuditModalBtn) closeAuditModalBtn.addEventListener('click', closeAuditModal);
    if (auditModal) auditModal.addEventListener('click', closeAuditModal);

    loadData();
  </script>

  @include('partials.scripts')

  <!-- Global Chatbot Widget -->
  @include('components.chatbot-widget')
</body>
</html>
