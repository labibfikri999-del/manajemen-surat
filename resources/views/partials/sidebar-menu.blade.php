{{-- resources/views/partials/sidebar-menu.blade.php --}}
@php
    $user = auth()->user();
    $role = $user->role ?? 'guest';
    $currentRoute = request()->route()->getName() ?? '';
    
    // Definisi menu dengan akses role
    $menus = [
        [
            'name' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9.75L12 3l9 6.75V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V9.75z"/>',
            'roles' => ['direktur', 'staff', 'instansi'],
        ],
        [
            'name' => ($role == 'staff') ? 'Kirim Dokumen' : 'Upload Dokumen',
            'route' => 'upload-dokumen',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>',
            'roles' => ['instansi', 'staff'],
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
            'roles' => ['direktur', 'staff'],
        ],
    ];
    
    $roleLabels = [
        'direktur' => 'Direktur',
        'staff' => 'Staff Direktur', 
        'instansi' => $user->instansi->nama ?? 'Instansi',
    ];
@endphp

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
