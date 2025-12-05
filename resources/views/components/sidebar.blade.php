{{-- resources/views/components/sidebar.blade.php --}}
@php
    $user = auth()->user();
    $role = $user->role ?? 'guest';
    $currentRoute = request()->route()->getName();
    
    // Definisi menu dengan akses role
    $menus = [
        [
            'name' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9.75L12 3l9 6.75V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V9.75z"/>',
            'roles' => ['direktur', 'staff', 'instansi'], // Semua bisa akses
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
    
    // Icon gembok untuk menu terkunci
    $lockIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 ml-auto shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>';
    
    // Role label
    $roleLabels = [
        'direktur' => 'Direktur',
        'staff' => 'Staff Direktur',
        'instansi' => $user->instansi->nama ?? 'Instansi',
    ];
@endphp

{{-- Sidebar --}}
<aside id="sidebar" class="sidebar sidebar-hidden-mobile border-r border-emerald-100">
    <div class="p-4 flex flex-col h-full">
        {{-- Collapse button --}}
        <button id="btnCollapse" class="hidden md:flex w-full items-center justify-center mb-4 p-2 rounded hover:bg-emerald-50 text-emerald-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>

        {{-- Role Badge --}}
        <div class="mb-4 p-3 bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-lg border border-emerald-200">
            <div class="text-xs text-emerald-500 uppercase tracking-wide">Login sebagai</div>
            <div class="font-semibold text-emerald-700">{{ $roleLabels[$role] ?? 'User' }}</div>
            <div class="text-xs text-emerald-600 truncate">Pengguna</div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto">
            <ul class="space-y-1">
                @foreach($menus as $menu)
                    @php
                        $hasAccess = in_array($role, $menu['roles']);
                        $isActive = $currentRoute === $menu['route'];
                    @endphp
                    <li>
                        @if($hasAccess)
                            {{-- Menu bisa diakses --}}
                            <a href="{{ route($menu['route']) }}" 
                               class="flex items-center gap-3 p-2 rounded-md nav-item tooltip relative {{ $isActive ? 'active' : '' }}" 
                               data-label="{{ $menu['name'] }}" 
                               title="{{ $menu['name'] }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    {!! $menu['icon'] !!}
                                </svg>
                                <span class="nav-label text-sm font-medium">{{ $menu['name'] }}</span>
                                <span class="tooltip-text">{{ $menu['name'] }}</span>
                            </a>
                        @else
                            {{-- Menu TERKUNCI - tidak bisa diakses --}}
                            <div class="flex items-center gap-3 p-2 rounded-md nav-item-locked tooltip relative cursor-not-allowed opacity-50" 
                                 title="Tidak memiliki akses - Khusus {{ implode(', ', array_map(fn($r) => ucfirst($r), $menu['roles'])) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    {!! $menu['icon'] !!}
                                </svg>
                                <span class="nav-label text-sm font-medium text-gray-400">{{ $menu['name'] }}</span>
                                {!! $lockIcon !!}
                                <span class="tooltip-text bg-gray-600">🔒 Akses terbatas</span>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>

        {{-- Footer --}}
        <div class="sidebar-footer mt-auto">
            <div class="border-t pt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn w-full flex items-center gap-3 px-3 py-2 rounded-md border border-red-200 bg-red-50 hover:bg-red-100 transition-smooth text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                        </svg>
                        <span class="logout-label nav-label text-sm font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

{{-- Mobile overlay --}}
<div id="mobileOverlay" class="mobile-overlay hidden"></div>
