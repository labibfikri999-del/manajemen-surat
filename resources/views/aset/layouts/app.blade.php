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
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(229, 231, 235, 0.5);
        }
        .nav-item-active {
            background: linear-gradient(to right, #ecfdf5, #fff);
            border-right: 3px solid #10b981;
            color: #059669;
        }
    </style>
</head>
<body class="bg-gray-50 text-slate-800 font-sans antialiased">
    <div x-data="{ sidebarOpen: window.innerWidth >= 768 }" class="flex h-screen overflow-hidden">
        
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
                <a href="#" class="nav-item-active flex items-center px-6 py-3 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Overview</span>
                </a>

                <!-- Inventaris -->
                <a href="#" class="flex items-center px-6 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Inventaris Aset</span>
                </a>

                <!-- Mutasi -->
                <a href="#" class="flex items-center px-6 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Mutasi & Peminjaman</span>
                </a>

                <!-- Maintenance -->
                <a href="#" class="flex items-center px-6 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Maintenance</span>
                </a>

                <!-- Laporan -->
                <a href="#" class="flex items-center px-6 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3">Laporan</span>
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
        <main class="flex-1 overflow-y-auto bg-gray-50 p-4 md:p-8 relative pt-16 md:pt-8">
            <!-- Header Mobile Toggle -->
            <button x-show="!sidebarOpen" @click="sidebarOpen = !sidebarOpen" class="absolute top-4 left-4 md:hidden p-2 rounded-lg bg-white shadow-sm text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>

            @yield('content')
        </main>
    </div>
</body>
</html>
