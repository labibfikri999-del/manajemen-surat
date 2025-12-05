{{-- resources/views/partials/header.blade.php --}}
@php
    $user = auth()->user();
    $role = $user->role ?? 'guest';
@endphp
<header class="site-header bg-white border-b border-emerald-100 flex items-center justify-between px-4 md:px-6 lg:px-8 shadow-sm">
    <div class="flex items-center gap-4">
        <button id="btnOpenMobile" class="md:hidden text-emerald-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="flex items-center gap-3">
            <img src="/images/logo-yarsi.svg" alt="YARSI Logo" class="w-10 h-10" onerror="this.style.display='none'">
            <div class="flex flex-col">
                <span class="text-lg font-bold text-emerald-700">YARSI NTB</span>
                <span class="text-xs text-emerald-600">Sistem Arsip Digital</span>
            </div>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <span class="hidden sm:inline-block px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium">
            {{ ucfirst($role) }}
        </span>
        <div class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
        </div>
    </div>
</header>
