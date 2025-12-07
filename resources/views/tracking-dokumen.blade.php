{{-- resources/views/tracking-dokumen.blade.php --}}
@php
    $user = auth()->user();
    $role = $user->role ?? 'guest';
    $roleLabels = [
        'direktur' => 'Direktur',
        'staff' => 'Staff Direktur', 
        'instansi' => $user->instansi->nama ?? 'Instansi',
    ];
    
    $statusColors = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'review' => 'bg-blue-100 text-blue-800',
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
    <title>Tracking Dokumen — YARSI NTB</title>
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
                <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-emerald-900">Tracking Dokumen</h1>
                        <p class="text-emerald-600 mt-2">Pantau status dokumen yang sudah diupload</p>
                    </div>
                    <a href="{{ route('upload-dokumen') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Upload Baru
                    </a>
                </div>

                {{-- Status Summary --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="text-3xl font-bold text-yellow-600">{{ $dokumens->where('status', 'pending')->count() }}</div>
                        <div class="text-sm text-gray-600">Menunggu</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="text-3xl font-bold text-green-600">{{ $dokumens->where('status', 'disetujui')->count() }}</div>
                        <div class="text-sm text-gray-600">Disetujui</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="text-3xl font-bold text-red-600">{{ $dokumens->where('status', 'ditolak')->count() }}</div>
                        <div class="text-sm text-gray-600">Ditolak</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="text-3xl font-bold text-emerald-600">{{ $dokumens->where('status', 'selesai')->count() }}</div>
                        <div class="text-sm text-gray-600">Selesai</div>
                    </div>
                </div>

                {{-- Dokumen List --}}
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Daftar Dokumen</h2>
                    </div>
                    
                    @if($dokumens->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($dokumens as $dok)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $dok->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-3">
                                                <div class="font-medium text-gray-900">{{ $dok->judul }}</div>
                                                <div class="text-xs text-gray-500">{{ Str::limit($dok->deskripsi, 50) }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $dok->jenis)) }}</td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$dok->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($dok->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                @if($dok->catatan_validasi)
                                                    {{ Str::limit($dok->catatan_validasi, 50) }}
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                <div class="flex flex-wrap gap-2 items-center">
                                                    @if($dok->file_path)
                                                        <a href="/storage/{{ $dok->file_path }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-600 text-white rounded-lg text-sm font-semibold shadow hover:bg-emerald-700 transition">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                            Lihat File
                                                        </a>
                                                    @endif
                                                    @if($dok->balasan_file)
                                                        <a href="/api/dokumen/{{ $dok->id }}/download-balasan" class="inline-flex items-center gap-2 px-4 py-1.5 bg-gray-100 text-emerald-700 rounded-lg text-sm font-semibold shadow hover:bg-gray-200 transition" download>
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                            Download Balasan
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-2 text-gray-500">Belum ada dokumen yang diupload</p>
                            <a href="{{ route('upload-dokumen') }}" class="mt-4 inline-block px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                                Upload Dokumen Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    @include('partials.scripts')
    <script>
        // Auto-refresh logic for real-time updates
        setInterval(async () => {
            // Check if any modal is open (prevent reload while user is working)
            // Tracking page doesn't have modals, but good to be safe/consistent
            const modals = document.querySelectorAll('[id$="Modal"]');
            const isModalOpen = Array.from(modals).some(m => !m.classList.contains('hidden'));
            if (isModalOpen) return;

            try {
                // Fetch current page content silently
                const response = await fetch(window.location.href);
                if (!response.ok) return;
                
                const text = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(text, 'text/html');
                
                // Compare only the main content logic (exclude tokens/scripts)
                const currentContent = document.querySelector('div.max-w-7xl').innerHTML;
                const newContent = doc.querySelector('div.max-w-7xl').innerHTML;
                
                // Simple comparison - if content changed (e.g. status update, new item), reload
                // Note: time difference (e.g. '1 min ago') will also trigger this, which is acceptable
                if (currentContent !== newContent) {
                    // Create toast if not exists
                    if (typeof showToast === 'undefined') {
                        // Minimal toast fallback
                        const toast = document.createElement('div');
                        toast.className = 'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded shadow z-50';
                        toast.innerText = 'Data diperbarui ...';
                        document.body.appendChild(toast);
                    } else {
                        showToast('Data baru terdeteksi, memuat ulang...', 'info');
                    }
                    
                    setTimeout(() => window.location.reload(), 1000);
                }
            } catch (e) {
                console.error('Auto-refresh check failed', e);
            }
        }, 10000); // Check every 10 seconds
    </script>
</body>
</html>
