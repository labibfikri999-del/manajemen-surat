<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Kepegawaian') - YARSI NTB</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_rsi_ntb_new.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800 antialiased">
@php
    $role = auth()->user()->role ?? 'pegawai';
    $menus = [
        ['label' => 'Dashboard', 'route' => 'kepegawaian.dashboard', 'roles' => ['pegawai', 'staff_kepegawaian', 'staff', 'sekjen', 'direktur'], 'icon' => 'M4 6a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm9 0a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2h-3a2 2 0 01-2-2V6zM4 15a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H6a2 2 0 01-2-2v-3zm9 0a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2h-3a2 2 0 01-2-2v-3z'],
        ['label' => 'Upload Dokumen', 'route' => 'kepegawaian.upload', 'roles' => ['pegawai', 'staff_kepegawaian', 'staff'], 'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 4v12m0-12l-4 4m4-4l4 4'],
        ['label' => 'Verifikasi Staff', 'route' => 'kepegawaian.verifikasi', 'roles' => ['staff_kepegawaian', 'staff'], 'icon' => 'M9 12l2 2 4-4M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z'],
        ['label' => 'Persetujuan Sekjen', 'route' => 'kepegawaian.persetujuan', 'roles' => ['sekjen', 'direktur'], 'icon' => 'M9 12h6m-6 4h6M8 4h8l4 4v10a2 2 0 01-2 2H8a2 2 0 01-2-2V6a2 2 0 012-2z'],
        ['label' => 'Akun Pegawai', 'route' => 'kepegawaian.akun', 'roles' => ['staff_kepegawaian', 'staff'], 'icon' => 'M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 118 0 4 4 0 01-8 0z'],
        ['label' => 'Reset Password', 'route' => 'kepegawaian.reset-password', 'roles' => ['staff_kepegawaian', 'staff'], 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
    ];
    $menus = array_values(array_filter($menus, fn ($menu) => in_array($role, $menu['roles'])));
@endphp

<aside id="kepegawaian-sidebar" class="fixed left-0 top-0 z-40 h-screen w-72 -translate-x-full border-r border-slate-200 bg-white transition-transform lg:translate-x-0" aria-label="Sidebar kepegawaian">
    <div class="flex h-full flex-col">
        <div class="flex min-h-20 items-center justify-between gap-3 border-b border-slate-100 px-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo_rsi_ntb_new.png') }}" class="h-11 w-auto" alt="Logo">
                <div>
                    <p class="text-sm font-bold leading-tight text-brand-900">Kepegawaian</p>
                    <p class="text-xs text-slate-500">YARSI NTB</p>
                </div>
            </div>
            <button type="button" data-drawer-hide="kepegawaian-sidebar" aria-controls="kepegawaian-sidebar" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-900 lg:hidden">
                <span class="sr-only">Tutup menu</span>
                <svg class="h-5 w-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="flex-1 space-y-1 px-4 py-5">
            @foreach($menus as $menu)
                @php($isActive = request()->routeIs($menu['route']))
                <a href="{{ route($menu['route']) }}" class="{{ $isActive ? 'border-brand-100 bg-brand-50 text-brand-700' : 'border-transparent text-slate-600 hover:border-slate-200 hover:bg-slate-50 hover:text-slate-950' }} flex items-center gap-3 rounded-lg border px-3 py-3 text-sm font-semibold transition">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $menu['icon'] }}" />
                    </svg>
                    <span>{{ $menu['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="border-t border-slate-100 p-4">
            <div class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-600 font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-bold text-slate-800">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="truncate text-xs text-slate-500">{{ auth()->user()->role ?? 'pegawai' }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>

<div class="lg:pl-72">
    <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="flex min-h-16 items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex min-w-0 items-center gap-3">
                <button type="button" data-drawer-target="kepegawaian-sidebar" data-drawer-show="kepegawaian-sidebar" aria-controls="kepegawaian-sidebar" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-950 lg:hidden">
                    <span class="sr-only">Buka menu</span>
                    <svg class="h-5 w-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-wide text-brand-700">@yield('eyebrow', 'Sistem Kepegawaian')</p>
                    <h1 class="truncate text-xl font-bold text-slate-950">@yield('page-title', 'Dashboard')</h1>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if(in_array($role, ['pegawai', 'staff_kepegawaian', 'staff']))
                    <a href="{{ route('kepegawaian.upload') }}" class="hidden items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 focus:outline-none focus:ring-4 focus:ring-cyan-100 sm:inline-flex">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0-12l-4 4m4-4l4 4M4 20h16"/>
                        </svg>
                        Upload
                    </a>
                @endif
                <form method="POST" action="{{ route('logout', ['source' => 'kepegawaian']) }}">
                    @csrf
                    <button class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-100">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    <main class="px-4 py-6 sm:px-6 lg:px-8">
        @if(session('success') || session('error') || session('info'))
            <div class="mb-5 rounded-lg border px-4 py-3 text-sm font-semibold {{ session('error') ? 'border-red-200 bg-red-50 text-red-700' : (session('info') ? 'border-cyan-200 bg-cyan-50 text-cyan-800' : 'border-emerald-200 bg-emerald-50 text-emerald-800') }}" role="alert">
                {{ session('success') ?? session('error') ?? session('info') }}
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>
</div>
</body>
</html>
