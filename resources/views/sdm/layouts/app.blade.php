<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem SDM - YARSI NTB</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_rsi_ntb_new.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Alpine.js -->
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
                        cyan: {
                            50: '#ecfeff',
                            100: '#cffafe',
                            500: '#06b6d4',
                            600: '#0891b2',
                            900: '#164e63',
                        },
                        sky: {
                            500: '#0ea5e9',
                            600: '#0284c7',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(229, 231, 235, 0.5);
        }
        .nav-item-active {
            background: linear-gradient(to right, #ecfeff, #fff);
            border-right: 3px solid #06b6d4;
            color: #0891b2;
        }
    </style>
</head>
<body class="bg-cyan-50/50 text-slate-800 font-sans antialiased">
    <div x-data="{ sidebarOpen: window.innerWidth >= 768 }" class="flex h-screen overflow-hidden">
        
        <!-- Mobile Backdrop -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/50 z-20 md:hidden"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full md:translate-x-0 md:w-20'" class="glass-sidebar flex flex-col transition-all duration-300 fixed md:relative z-30 h-full shadow-xl shadow-cyan-100/20">
            <div class="h-20 flex items-center justify-between px-6 border-b border-gray-100/50">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo_rsi_ntb_new.png') }}" class="h-10 w-auto" alt="Logo">
                    <span x-show="sidebarOpen" class="ml-3 font-bold text-lg text-cyan-800 transition-opacity duration-300">Sistem SDM</span>
                </div>
                <!-- Close Button (Mobile Only) -->
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Menu -->
            <nav class="flex-1 overflow-y-auto py-6 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('sdm.dashboard') }}" class="{{ request()->routeIs('sdm.dashboard') ? 'nav-item-active' : 'text-slate-500 hover:text-cyan-600 hover:bg-cyan-50' }} flex items-center px-6 py-3.5 text-sm font-medium transition-all duration-200 group">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Dashboard</span>
                </a>

                <div class="px-6 pt-6 pb-2" x-show="sidebarOpen">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Manajemen</p>
                </div>

                <!-- Pegawai -->
                <a href="#" class="flex items-center px-6 py-3.5 text-slate-500 hover:text-cyan-600 hover:bg-cyan-50 text-sm font-medium transition-all duration-200 group">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Data Pegawai</span>
                </a>

                <!-- Jadwal Shift (Critical for Hospital) -->
                <a href="#" class="flex items-center px-6 py-3.5 text-slate-500 hover:text-cyan-600 hover:bg-cyan-50 text-sm font-medium transition-all duration-200 group">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Jadwal Shift</span>
                </a>

                <!-- Absensi -->
                <a href="#" class="flex items-center px-6 py-3.5 text-slate-500 hover:text-cyan-600 hover:bg-cyan-50 text-sm font-medium transition-all duration-200 group">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Data Absensi</span>
                </a>

                <!-- Payroll -->
                <a href="#" class="flex items-center px-6 py-3.5 text-slate-500 hover:text-cyan-600 hover:bg-cyan-50 text-sm font-medium transition-all duration-200 group">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Penggajian</span>
                </a>
                
                <div class="px-6 pt-6 pb-2" x-show="sidebarOpen">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lainnya</p>
                </div>

                <!-- Laporan -->
                <a href="#" class="flex items-center px-6 py-3.5 text-slate-500 hover:text-cyan-600 hover:bg-cyan-50 text-sm font-medium transition-all duration-200 group">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Laporan</span>
                </a>
                
                <!-- Logout -->
                <form method="POST" action="{{ route('logout', ['source' => 'sdm']) }}" class="mt-8 mb-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-6 py-3 text-slate-500 hover:bg-red-50 hover:text-red-600 text-sm font-medium transition-colors group">
                        <svg class="w-5 h-5 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span x-show="sidebarOpen" class="ml-3">Keluar</span>
                    </button>
                </form>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-gray-100/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-600 font-bold border-2 border-white shadow-sm">
                        {{ substr(Auth::user()->name ?? 'Guest', 0, 1) }}
                    </div>
                    <div x-show="sidebarOpen" class="overflow-hidden">
                        <p class="text-xs font-bold text-slate-700 truncate">{{ Auth::user()->name ?? 'Guest User' }}</p>
                        <p class="text-[10px] text-slate-500 truncate">HR Administrator</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto relative pt-16 md:pt-0">
            <!-- Header Mobile Toggle -->
            <button x-show="!sidebarOpen" @click="sidebarOpen = !sidebarOpen" class="absolute top-4 left-4 md:hidden p-2 rounded-lg bg-white shadow-sm text-slate-600 z-50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>

            @yield('content')
        </main>
    </div>
</body>
</html>
