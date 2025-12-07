{{-- resources/views/hasil-validasi.blade.php --}}
@php
    $user = auth()->user();
    $role = $user->role ?? 'guest';
    $roleLabels = [
        'direktur' => 'Direktur',
        'staff' => 'Staff Direktur', 
        'instansi' => $user->instansi->nama ?? 'Instansi',
    ];
    
    $statusColors = [
        'disetujui' => 'bg-green-100 text-green-800',
        'ditolak' => 'bg-red-100 text-red-800',
        'diproses' => 'bg-purple-100 text-purple-800',
        'selesai' => 'bg-emerald-100 text-emerald-800',
    ];
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Hasil Validasi — YARSI NTB</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Yayasan Bersih.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('partials.styles')
</head>
<body class="bg-gray-50">
    <div id="app" class="flex flex-col">
        @include('partials.header')
        @include('partials.sidebar-menu')

        {{-- Main content --}}
        <main id="main" class="transition-smooth main-with-sidebar flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
            <div class="max-w-7xl mx-auto">
                @include('partials.flash-messages')

                {{-- Page header --}}
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-emerald-900">Hasil Validasi</h1>
                    <p class="text-emerald-600 mt-2">Lihat dokumen yang sudah divalidasi</p>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                        <div class="text-3xl font-bold text-green-600">{{ $dokumens->where('status', 'disetujui')->count() }}</div>
                        <div class="text-sm text-gray-600">Disetujui</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
                        <div class="text-3xl font-bold text-red-600">{{ $dokumens->where('status', 'ditolak')->count() }}</div>
                        <div class="text-sm text-gray-600">Ditolak</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
                        <div class="text-3xl font-bold text-purple-600">{{ $dokumens->where('status', 'diproses')->count() }}</div>
                        <div class="text-sm text-gray-600">Diproses</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-emerald-500">
                        <div class="text-3xl font-bold text-emerald-600">{{ $dokumens->where('status', 'selesai')->count() }}</div>
                        <div class="text-sm text-gray-600">Selesai</div>
                    </div>
                </div>

                {{-- Filter --}}
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <div class="flex flex-wrap gap-2">
                        <button onclick="filterStatus('all')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium bg-emerald-600 text-white" data-status="all">
                            Semua
                        </button>
                        <button onclick="filterStatus('disetujui')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200" data-status="disetujui">
                            ✓ Disetujui
                        </button>
                        <button onclick="filterStatus('ditolak')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200" data-status="ditolak">
                            ✗ Ditolak
                        </button>
                        <button onclick="filterStatus('diproses')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200" data-status="diproses">
                            ⚡ Diproses
                        </button>
                        <button onclick="filterStatus('selesai')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200" data-status="selesai">
                            ✓ Selesai
                        </button>
                    </div>
                </div>

                {{-- Dokumen List --}}
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    @if($dokumens->count() > 0)
                        <div class="divide-y divide-gray-200" id="dokumenList">
                            @foreach($dokumens as $dok)
                                <div class="dokumen-item p-4 hover:bg-gray-50" data-status="{{ $dok->status }}">
                                    <div class="flex flex-col md:flex-row md:items-start gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$dok->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($dok->status) }}
                                                </span>
                                                <span class="text-xs text-gray-500">{{ $dok->tanggal_validasi ? $dok->tanggal_validasi->format('d M Y, H:i') : '-' }}</span>
                                            </div>
                                            <h3 class="mt-2 font-semibold text-gray-900">{{ $dok->judul }}</h3>
                                            <p class="text-sm text-gray-600">{{ $dok->deskripsi }}</p>
                                            <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                                <span class="px-2 py-1 bg-gray-100 rounded text-gray-600">{{ $dok->instansi->nama ?? 'N/A' }}</span>
                                                <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded">Validator: {{ $dok->validator->name ?? '-' }}</span>
                                                @if($dok->processor)
                                                    <span class="px-2 py-1 bg-purple-50 text-purple-700 rounded">Diproses: {{ $dok->processor->name }}</span>
                                                @endif
                                            </div>
                                            @if($dok->catatan_validasi)
                                                <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                                    <p class="text-xs text-gray-500">Catatan Validasi:</p>
                                                    <p class="text-sm text-gray-700 italic">"{{ $dok->catatan_validasi }}"</p>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        @if($dok->file_path)
                                            <a href="{{ route('dokumen.download', $dok->id) }}"
                                               class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-2 shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                Download
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-2 text-gray-500">Belum ada dokumen yang divalidasi</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    @include('partials.scripts')
    <script>
        function filterStatus(status) {
            // Update buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                if (btn.dataset.status === status) {
                    btn.classList.remove('bg-gray-100', 'text-gray-700');
                    btn.classList.add('bg-emerald-600', 'text-white');
                } else {
                    btn.classList.remove('bg-emerald-600', 'text-white');
                    btn.classList.add('bg-gray-100', 'text-gray-700');
                }
            });

            // Filter items
            document.querySelectorAll('.dokumen-item').forEach(item => {
                if (status === 'all' || item.dataset.status === status) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
