@extends('sdm.layouts.app')

@section('content')
<div x-data="{ showImportModal: false }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Hero / Header Section -->
    <div class="bg-indigo-600 rounded-2xl p-6 sm:p-10 relative overflow-hidden shadow-lg">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="text-white">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-8 h-8 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <h1 class="text-3xl font-bold tracking-tight">Data Karyawan</h1>
                </div>
                <p class="text-indigo-100 opacity-90">Total <span class="font-bold text-white">{{ $pegawais->total() }}</span> karyawan terdaftar dalam sistem.</p>
            </div>
            <div class="flex gap-3">
                <button @click="showImportModal = true" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-semibold transition-all flex items-center gap-2 shadow-md shadow-emerald-800/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Import Excel
                </button>
                <a href="{{ route('sdm.pegawai.create') }}" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-5 py-2.5 rounded-xl font-semibold backdrop-blur-sm transition-all flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Karyawan
                </a>
                <a href="{{ route('sdm.pegawai.export') }}" class="bg-indigo-500 hover:bg-indigo-400 text-white px-5 py-2.5 rounded-xl font-semibold transition-all flex items-center gap-2 shadow-md shadow-indigo-800/20">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                     Export Excel
                </a>
            </div>
        </div>
        
        <!-- Decorative bg -->
        <div class="absolute right-0 top-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
        <div class="absolute left-10 bottom-0 w-32 h-32 bg-purple-500 opacity-20 rounded-full blur-2xl"></div>
    </div>

    <!-- Filters Section -->
    
    <!-- Import Feedback -->
    @if(session('import_errors'))
    <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6 rounded-r shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-amber-700">
                    {{ session('warning') ?? session('error') ?? 'Terdapat beberapa error saat import:' }}
                </p>
                <div class="mt-2 text-sm text-amber-700 max-h-40 overflow-y-auto">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach(session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <form action="{{ route('sdm.pegawai.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <!-- Search -->
                <div class="md:col-span-5">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Cari Karyawan</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2.5 rounded-lg border-slate-200 bg-slate-50 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-all placeholder:text-slate-400" placeholder="Nama, NIP/NIK, atau Email...">
                        <div class="absolute left-3 top-3 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Status Aktif -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Status Aktif</label>
                    <select name="status" class="w-full rounded-lg border-slate-200 bg-slate-50 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <!-- Status Kerja -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Status Kerja</label>
                    <select name="status_kepegawaian" class="w-full rounded-lg border-slate-200 bg-slate-50 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        <option value="">Semua</option>
                        <option value="Tetap" {{ request('status_kepegawaian') == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                        <option value="Kontrak" {{ request('status_kepegawaian') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                    </select>
                </div>

                <!-- Reset Button -->
                <div class="md:col-span-1">
                    <a href="{{ route('sdm.pegawai.index') }}" class="flex items-center justify-center w-full py-2.5 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-800 font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                        <th class="px-6 py-4">Foto</th>
                        <th class="px-6 py-4">NIK / NIP</th>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Jabatan</th>
                        <th class="px-6 py-4">Status Kerja</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Masa Kerja</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pegawais as $pegawai)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <!-- Foto -->
                        <td class="px-6 py-4 align-middle">
                            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-sm ring-2 ring-slate-100 group-hover:ring-indigo-100 transition-all">
                                @if($pegawai->foto)
                                    <img src="{{ asset('storage/' . $pegawai->foto) }}" alt="{{ $pegawai->name }}" class="w-full h-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($pegawai->name) }}&background=6366f1&color=fff&size=128" alt="{{ $pegawai->name }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                        </td>

                        <!-- NIK/NIP -->
                        <td class="px-6 py-4 align-middle">
                            <span class="font-mono text-sm font-semibold text-slate-700 bg-slate-100 px-2 py-1 rounded">{{ $pegawai->nip }}</span>
                        </td>

                        <!-- Nama -->
                        <td class="px-6 py-4 align-middle">
                            <div>
                                <h3 class="font-bold text-slate-800 text-sm group-hover:text-indigo-600 transition-colors">{{ $pegawai->name }}</h3>
                                <div class="text-xs text-slate-500 mt-0.5 space-x-1">
                                    <span>{{ $pegawai->jenis_kelamin == 'L' ? 'Laki-laki' : ($pegawai->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</span>
                                    @if($pegawai->phone)
                                    <span class="text-slate-300">•</span>
                                    <span>{{ $pegawai->phone }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Jabatan -->
                        <td class="px-6 py-4 align-middle">
                             <div class="flex flex-col">
                                <span class="text-sm font-semibold text-slate-700">{{ $pegawai->jabatan ?? $pegawai->role }}</span>
                                <span class="text-xs text-slate-500">{{ $pegawai->unit_kerja ?? '-' }}</span>
                            </div>
                        </td>

                        <!-- Status Kerja -->
                        <td class="px-6 py-4 align-middle">
                             @php
                                $statusClass = match($pegawai->status_kepegawaian) {
                                    'Tetap' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'Kontrak' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    default => 'bg-slate-100 text-slate-700 border-slate-200'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold uppercase tracking-wide border {{ $statusClass }}">
                                {{ $pegawai->status_kepegawaian ?? '-' }}
                            </span>
                        </td>

                        <!-- Status Aktif -->
                        <td class="px-6 py-4 align-middle">
                            @if($pegawai->status == 'active')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    <span class="relative flex h-2 w-2">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                    </span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </td>

                         <!-- Masa Kerja -->
                         <td class="px-6 py-4 align-middle">
                            @if($pegawai->join_date)
                                <div class="text-xs font-medium text-slate-600">
                                    @php
                                        $join = \Carbon\Carbon::parse($pegawai->join_date);
                                        $diff = $join->diff(\Carbon\Carbon::now());
                                    @endphp
                                    <div class="font-bold text-slate-800">{{ $diff->y }} th {{ $diff->m }} bln</div>
                                    <div class="text-slate-400 text-[10px]">{{ $join->format('d/m/Y') }}</div>
                                </div>
                            @else
                                <span class="text-slate-400 text-xs">-</span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="px-6 py-4 align-middle text-center">
                             <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('sdm.pegawai.show', $pegawai->id) }}" class="p-1.5 bg-sky-50 text-sky-600 rounded-lg hover:bg-sky-100 transition-colors" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('sdm.pegawai.edit', $pegawai->id) }}" class="p-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('sdm.pegawai.destroy', $pegawai->id) }}" method="POST" onsubmit="return confirm('Hapus data pegawai ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <h3 class="text-slate-800 font-medium">Data Pegawai Tidak Ditemukan</h3>
                                <p class="text-sm mt-1">Coba sesuaikan filter pencarianmu</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($pegawais->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $pegawais->links() }}
        </div>
        @endif
    </div>

    <!-- Import Modal -->
    <div x-cloak x-show="showImportModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showImportModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showImportModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showImportModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('sdm.pegawai.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">Import Data Pegawai</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-slate-500 mb-4">Upload file Excel (.xls) hasil export atau CSV. Pastikan format kolom sesuai dengan template.</p>
                                    
                                    <div class="mt-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <label class="block text-sm font-bold text-slate-700">Pilih File</label>
                                            <a href="{{ route('sdm.pegawai.template') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                Download Template
                                            </a>
                                        </div>
                                        <input type="file" name="file" accept=".csv, .xls, .xlsx" required class="block w-full text-sm text-slate-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-full file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-emerald-50 file:text-emerald-700
                                          hover:file:bg-emerald-100
                                        "/>
                                    </div>
                                    <div class="mt-2 text-xs text-slate-400">
                                        <p>Tips: Gunakan fitur "Export Excel" untuk mendapatkan template dengan header berwarna.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">Import Data</button>
                        <button @click="showImportModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
