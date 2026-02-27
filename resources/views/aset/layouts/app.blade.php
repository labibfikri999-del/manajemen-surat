<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Aset - YARSI NTB</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_rsi_ntb_new.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Alpine.js for interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Space Grotesk"', 'sans-serif'],
                    },
                    colors: {
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Advanced Glassmorphism - Optimized */
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-right: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 1px 0 20px rgba(0, 0, 0, 0.02);
            will-change: transform;
        }
        
        /* Modern Gradient Nav Item */
        .nav-item-active {
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
            border-right: 3px solid #10b981;
            color: #059669;
            font-weight: 600;
        }

        /* Ambient Glow Background - Static / Optimized */
        .ambient-glow {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.1;
            z-index: 0;
            pointer-events: none;
            /* Removed heavy keyframe animation causing GPU lag on some devices */
        }
        .glow-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: #10b981; }
        .glow-2 { bottom: -20%; right: -10%; width: 60vw; height: 60vw; background: #0ea5e9; }

        /* Staggered Page Load Animation */
        .animate-fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        
        /* Custom Scrollbar for sleekness */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden selection:bg-emerald-200 selection:text-emerald-900">
    <!-- Ambient Glow Lights -->
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>

    <div x-data="{ sidebarOpen: window.innerWidth >= 768 }" class="flex h-screen overflow-hidden relative z-10">
        
        <!-- Mobile Backdrop -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/50 z-20 md:hidden"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full md:translate-x-0 md:w-20'" class="glass-sidebar flex flex-col transition-all duration-300 fixed md:relative z-30 h-full">
            <!-- Brand -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-gray-100">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo_rsi_ntb_new.png') }}" class="h-10 w-auto" alt="Logo">
                    <span x-show="sidebarOpen" class="ml-3 font-bold text-lg text-emerald-800 transition-opacity duration-300">Sistem Aset</span>
                </div>
                <!-- Close Button (Mobile Only) -->
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Menu -->
            <nav class="flex-1 overflow-y-auto py-4 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('aset.dashboard') }}" class="{{ request()->routeIs('aset.dashboard') ? 'nav-item-active' : '' }} flex items-center px-6 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Dashboard</span>
                </a>

                <!-- Data Aset -->
                <a href="{{ route('aset.inventory.index') }}" class="{{ request()->routeIs('aset.inventory*') ? 'nav-item-active' : '' }} flex items-center px-6 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Data Aset</span>
                </a>

                <!-- Unit Kerja -->
                <a href="{{ route('aset.unit.index') }}" class="{{ request()->routeIs('aset.unit*') ? 'nav-item-active' : '' }} flex items-center px-6 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Unit Kerja</span>
                </a>

                <!-- Kategori -->
                <a href="{{ route('aset.category.index') }}" class="{{ request()->routeIs('aset.category*') ? 'nav-item-active' : '' }} flex items-center px-6 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Kategori</span>
                </a>

                <!-- Peminjaman -->
                <a href="{{ route('aset.loan.index') }}" class="{{ request()->routeIs('aset.loan*') ? 'nav-item-active' : '' }} flex items-center px-6 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Peminjaman</span>
                </a>

                <!-- Pemeliharaan -->
                <a href="{{ route('aset.maintenance.index') }}" class="{{ request()->routeIs('aset.maintenance*') ? 'nav-item-active' : '' }} flex items-center px-6 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Pemeliharaan</span>
                </a>

                <!-- Laporan Kerusakan -->
                <a href="{{ route('aset.damage.index') }}" class="{{ request()->routeIs('aset.damage*') ? 'nav-item-active' : '' }} flex items-center px-6 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Laporan Kerusakan</span>
                </a>

                <!-- Mutasi -->
                <a href="{{ route('aset.mutation.index') }}" class="{{ request()->routeIs('aset.mutation*') ? 'nav-item-active' : '' }} flex items-center px-6 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Mutasi</span>
                </a>

                <!-- Penggunaan -->
                <a href="{{ route('aset.usage.index') }}" class="{{ request()->routeIs('aset.usage*') ? 'nav-item-active' : '' }} flex items-center px-6 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Penggunaan</span>
                </a>

                <!-- Audit -->
                <a href="{{ route('aset.audit.index') }}" class="{{ request()->routeIs('aset.audit*') ? 'nav-item-active' : '' }} flex items-center px-6 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Audit</span>
                </a>

                <!-- Laporan -->
                <a href="{{ route('aset.report.index') }}" class="{{ request()->routeIs('aset.report*') ? 'nav-item-active' : '' }} flex items-center px-6 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Laporan</span>
                </a>

                <!-- Scan QR -->
                <a href="{{ route('aset.scan_qr') }}" class="{{ request()->routeIs('aset.scan_qr') ? 'nav-item-active' : '' }} flex items-center px-6 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Scan QR</span>
                </a>

                <!-- Pengaturan -->
                <a href="{{ route('aset.settings') }}" class="{{ request()->routeIs('aset.settings*') ? 'nav-item-active' : '' }} flex items-center px-6 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Pengaturan</span>
                </a>
                
                <!-- Logout -->
                <form method="POST" action="{{ route('logout', ['source' => 'aset']) }}" class="mt-8 mb-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-6 py-3 text-slate-500 hover:bg-red-50 hover:text-red-600 text-sm font-medium transition-colors group">
                        <svg class="w-5 h-5 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span x-show="sidebarOpen" class="ml-3">Keluar</span>
                    </button>
                </form>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold">
                        {{ substr(Auth::user()->name ?? 'Guest', 0, 1) }}
                    </div>
                    <div x-show="sidebarOpen" class="overflow-hidden">
                        <p class="text-xs font-bold text-slate-700 truncate">{{ Auth::user()->name ?? 'Guest User' }}</p>
                        <p class="text-[10px] text-slate-500 truncate">Administrator</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-transparent p-4 md:p-8 relative pt-16 md:pt-8 w-full">
            <!-- Header Mobile Toggle -->
            <button x-show="!sidebarOpen" @click="sidebarOpen = !sidebarOpen" class="absolute top-4 left-4 md:hidden p-2 rounded-lg bg-white shadow-sm text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>

            @yield('content')
        </main>
    </div>
</body>
</html>
