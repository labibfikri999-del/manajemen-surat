{{-- resources/views/partials/sidebar-menu.blade.php --}}
@php
    $user = auth()->user();
    $role = $user->role ?? 'guest';
    $currentRoute = request()->route()?->getName() ?? '';

    $menus = collect(config('surat_navigation.items', []))
        ->filter(fn ($menu) => in_array($role, $menu['roles'] ?? [], true))
        ->map(function ($menu) use ($role) {
            if ($role === 'staff' && isset($menu['staff_name'])) {
                $menu['name'] = $menu['staff_name'];
            }

            return $menu;
        })
        ->values();
    
    $roleLabels = [
        'direktur' => 'Sekjen',
        'staff' => 'Staff Sekjen', 
        'instansi' => $user->instansi->nama ?? 'Unit Usaha',
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
                        $isActive = $currentRoute === $menu['route'];
                        $badgeValue = null;

                        if (($menu['badge'] ?? null) === 'countValidasi' && isset($countValidasi)) {
                            $badgeValue = $countValidasi;
                        } elseif (($menu['badge'] ?? null) === 'countProses' && isset($countProses)) {
                            $badgeValue = $countProses;
                        } elseif (($menu['badge'] ?? null) === 'countSuratMasuk' && isset($countSuratMasuk)) {
                            $badgeValue = $countSuratMasuk;
                        }
                    @endphp
                    <li>
                        <a href="{{ $menu['route'] === 'arsip-digital' ? '/arsip-dokumen' : str_replace('/public', '', route($menu['route'])) }}"
                           class="nav-item tooltip {{ $isActive ? 'active' : '' }}"
                           title="{{ $menu['name'] }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                {!! $menu['icon'] !!}
                            </svg>
                            <span class="nav-label text-sm">{{ $menu['name'] }}</span>

                            @if($badgeValue && $badgeValue > 0)
                                <span id="{{ ($menu['badge'] ?? '') === 'countSuratMasuk' ? 'sidebar-badge-surat-masuk' : '' }}" class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full animate-pulse">
                                    {{ $badgeValue }}
                                </span>
                            @endif

                            <span class="tooltip-text">{{ $menu['name'] }}</span>
                        </a>
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
<button id="btnCollapse" class="btn-collapse-outer hidden md:flex" title="Buka/Tutup Sidebar">
    <svg fill="none" class="w-6 h-6 icon-menu" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    <svg fill="none" class="w-6 h-6 icon-arrow" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
</button>
