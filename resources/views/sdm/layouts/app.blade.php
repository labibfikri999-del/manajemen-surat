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

                <!-- Data Karyawan -->
                <a href="{{ route('sdm.pegawai.index') }}" class="{{ request()->routeIs('sdm.pegawai.*') ? 'nav-item-active' : 'text-slate-500 hover:text-cyan-600 hover:bg-cyan-50' }} flex items-center px-6 py-3.5 text-sm font-medium transition-all duration-200 group">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Data Karyawan</span>
                </a>

                <!-- Pendidikan (New) -->
                <a href="{{ route('sdm.pendidikan.index') }}" class="{{ request()->routeIs('sdm.pendidikan.*') ? 'nav-item-active' : 'text-slate-500 hover:text-cyan-600 hover:bg-cyan-50' }} flex items-center px-6 py-3.5 text-sm font-medium transition-all duration-200 group">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Pendidikan</span>
                </a>

                <!-- Payroll -->
                <a href="{{ route('sdm.payroll.index') }}" class="{{ request()->routeIs('sdm.payroll.*') ? 'nav-item-active' : 'text-slate-500 hover:text-cyan-600 hover:bg-cyan-50' }} flex items-center px-6 py-3.5 text-sm font-medium transition-all duration-200 group">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 0 0118 0z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Payroll</span>
                </a>

                <!-- Jabatan Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('sdm.master-jabatan.*') || request()->routeIs('sdm.riwayat-jabatan.*') || request()->routeIs('sdm.riwayat-pangkat.*') ? 'true' : 'false' }} }">
                    <button @click="sidebarOpen ? open = !open : null" class="w-full text-slate-500 hover:text-cyan-600 hover:bg-cyan-50 flex items-center justify-between px-6 py-3.5 text-sm font-medium transition-all duration-200 group">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <span x-show="sidebarOpen" class="ml-3">Jabatan</span>
                        </div>
                        <svg x-show="sidebarOpen" :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <!-- Submenu -->
                    <div x-show="open && sidebarOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="bg-slate-50 border-l-4 border-cyan-100 ml-6 mr-4 my-1 rounded-r-lg overflow-hidden">
                        <a href="{{ route('sdm.riwayat-jabatan.index') }}" class="{{ request()->routeIs('sdm.riwayat-jabatan.*') ? 'text-cyan-600 bg-cyan-50 border-cyan-500' : 'text-slate-500 hover:text-cyan-600 hover:bg-cyan-50' }} block px-4 py-2.5 text-sm transition-colors border-l-2 border-transparent hover:border-cyan-200">
                            Riwayat Jabatan
                        </a>
                        <a href="{{ route('sdm.riwayat-pangkat.index') }}" class="{{ request()->routeIs('sdm.riwayat-pangkat.*') ? 'text-cyan-600 bg-cyan-50 border-cyan-500' : 'text-slate-500 hover:text-cyan-600 hover:bg-cyan-50' }} block px-4 py-2.5 text-sm transition-colors border-l-2 border-transparent hover:border-cyan-200">
                            Riwayat Pangkat
                        </a>
                        <a href="{{ route('sdm.monitoring-pangkat.index') }}" class="{{ request()->routeIs('sdm.monitoring-pangkat.*') ? 'text-cyan-600 bg-cyan-50 border-cyan-500' : 'text-slate-500 hover:text-cyan-600 hover:bg-cyan-50' }} block px-4 py-2.5 text-sm transition-colors border-l-2 border-transparent hover:border-cyan-200">
                            Monitoring Kenaikan
                        </a>
                        <a href="{{ route('sdm.master-jabatan.index') }}" class="{{ request()->routeIs('sdm.master-jabatan.*') ? 'text-cyan-600 bg-cyan-50 border-cyan-500' : 'text-slate-500 hover:text-cyan-600 hover:bg-cyan-50' }} block px-4 py-2.5 text-sm transition-colors border-l-2 border-transparent hover:border-cyan-200">
                            Master Jabatan
                        </a>
                    </div>
                </div>

                <!-- Keluarga (New) -->
                <a href="{{ route('sdm.keluarga.index') }}" class="{{ request()->routeIs('sdm.keluarga.*') ? 'nav-item-active' : 'text-slate-500 hover:text-cyan-600 hover:bg-cyan-50' }} flex items-center px-6 py-3.5 text-sm font-medium transition-all duration-200 group">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Keluarga</span>
                </a>
                
                <div class="px-6 pt-6 pb-2" x-show="sidebarOpen">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lainnya</p>
                </div>

                <!-- Laporan -->
                <a href="{{ route('sdm.laporan.index') }}" class="{{ request()->routeIs('sdm.laporan.*') ? 'nav-item-active' : 'text-slate-500 hover:text-cyan-600 hover:bg-cyan-50' }} flex items-center px-6 py-3.5 text-sm font-medium transition-all duration-200 group">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Laporan</span>
                </a>

                <!-- Pengaturan -->
                <a href="{{ route('sdm.settings.index') }}" class="{{ request()->routeIs('sdm.settings.*') ? 'nav-item-active' : 'text-slate-500 hover:text-cyan-600 hover:bg-cyan-50' }} flex items-center px-6 py-3.5 text-sm font-medium transition-all duration-200 group">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Pengaturan</span>
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
