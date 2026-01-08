<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PORTAL - YARSI NTB</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_rsi_ntb_new.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Space Grotesk"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6', // Teal
                            600: '#0d9488',
                        },
                        accent: {
                            500: '#84cc16', // Lime
                            600: '#65a30d',
                        }
                    },
                    animation: {
                        'float': 'float 8s cubic-bezier(0.45, 0, 0.55, 1) infinite',
                        'float-delayed': 'float 8s cubic-bezier(0.45, 0, 0.55, 1) 4s infinite',
                        'blob': 'blob 10s infinite',
                        'shine': 'shine 3s linear infinite',
                        'fade-in': 'fadeIn 1.5s ease-out forwards',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        shine: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(100%)' }
                        },
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            /* Background handled by fixed layer */
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }
        .energy-gradient {
            background: linear-gradient(135deg, #06b6d4 0%, #10b981 50%, #84cc16 100%);
        }
        .text-gradient {
            background: linear-gradient(to right, #0e7490, #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .card-hover:hover {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="antialiased text-slate-800 min-h-screen relative flex flex-col items-center justify-center py-12 px-4">

    <!-- Fixed Background Layer (The "Unified" Canvas) -->
    <div class="fixed inset-0 w-full h-full -z-50">
        <!-- Base Gradient -->
        <div class="absolute inset-0 w-full h-full bg-[radial-gradient(circle_at_top_center,_#f0fdfa_0%,_#ccfbf1_40%,_#e0f2fe_100%)]"></div>
    </div>

    <!-- Animated Background Blobs (Fixed to viewport for consistent parallax) -->
    <div class="fixed inset-0 w-full h-full -z-10 pointer-events-none overflow-hidden">
        <div class="absolute top-0 -left-10 w-96 h-96 bg-cyan-300 rounded-full mix-blend-multiply filter blur-[128px] opacity-70 animate-blob"></div>
        <div class="absolute top-0 -right-10 w-96 h-96 bg-emerald-300 rounded-full mix-blend-multiply filter blur-[128px] opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-20 w-96 h-96 bg-lime-300 rounded-full mix-blend-multiply filter blur-[128px] opacity-70 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Main Container -->
    <main class="w-full max-w-5xl animate-fade-in">
        
        <!-- Header -->
        <div class="text-center mb-16 relative">
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-white rounded-full filter blur-[100px] opacity-40 -z-10"></div>
            
            <!-- Left Decoration (CSS Aurora) -->
            <div data-value="-2" class="parallax absolute -left-20 top-1/2 transform -translate-y-1/2 w-64 h-96 bg-gradient-to-r from-cyan-400 to-teal-300 rounded-full mix-blend-multiply filter blur-[80px] opacity-60 animate-blob hidden lg:block pointer-events-none"></div>

            <!-- Right Decoration (CSS Aurora) -->
            <div data-value="2" class="parallax absolute -right-20 top-1/2 transform -translate-y-1/2 w-64 h-96 bg-gradient-to-l from-blue-400 to-indigo-300 rounded-full mix-blend-multiply filter blur-[80px] opacity-60 animate-blob animation-delay-2000 hidden lg:block pointer-events-none"></div>

            <script>
                document.addEventListener("mousemove", parallax);
                function parallax(e) {
                    document.querySelectorAll(".parallax").forEach(function(move){
                        var moving_value = move.getAttribute("data-value");
                        var x = (e.clientX * moving_value) / 250;
                        var y = (e.clientY * moving_value) / 250;
                        move.style.transform = "translateX(" + x + "px) translateY(" + y + "px)";
                    });
                }
            </script>
            
            <img src="{{ asset('images/logo_rsi_ntb_new.png') }}" alt="Logo" class="h-48 md:h-64 w-auto mx-auto mb-10 animate-float drop-shadow-2xl filter brightness-110">
            
            <h1 class="text-4xl md:text-6xl font-bold tracking-tight mb-2">
                <span class="text-gradient">Portal Terintegrasi</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-600 font-medium max-w-2xl mx-auto leading-relaxed">
                <span class="font-bold text-emerald-600">YARSI NTB</span> - Satu Akses untuk Semua Aktivitas
            </p>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            
            <!-- Card 1: Sistem Surat (Primary) - Standardized Size -->
            <a href="{{ route('login') }}" class="glass-panel rounded-3xl p-6 relative overflow-hidden group card-hover text-center border-2 border-transparent hover:border-emerald-200 flex flex-col justify-center items-center">
                <!-- Shine Effect -->
                <div class="absolute inset-0 -skew-x-12 opacity-0 group-hover:opacity-100 transition-opacity duration-500 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent w-full h-full animate-shine"></div>
                </div>

                <div class="w-14 h-14 mx-auto rounded-2xl energy-gradient flex items-center justify-center text-white mb-4 shadow-lg shadow-emerald-200 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                
                <h2 class="text-lg font-bold text-slate-800 mb-1 group-hover:text-emerald-600 transition-colors">Sistem Surat</h2>
                <p class="text-slate-500 text-xs mb-4">Administrasi & Disposisi</p>
                
                <span class="relative overflow-hidden inline-block px-5 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-md group-hover:shadow-emerald-200">
                    <span class="relative z-10">Akses Masuk →</span>
                    <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-transparent via-white/50 to-transparent -translate-x-full group-hover:animate-[shine_1.5s_infinite] z-0"></div>
                </span>
            </a>

            <!-- Card 2: SDM (Renamed from HRD) -->
            <a href="{{ route('sdm.login') }}" class="glass-panel rounded-3xl p-6 relative overflow-hidden group card-hover text-center border-2 border-transparent hover:border-cyan-200 flex flex-col justify-center items-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-white mb-4 shadow-lg shadow-cyan-200 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h2 class="text-lg font-bold text-slate-800 mb-1 group-hover:text-cyan-600 transition-colors">Sistem SDM</h2>
                <p class="text-slate-500 text-xs mb-4 text-balance">Sumber Daya Manusia</p>
                
                <span class="relative overflow-hidden inline-block px-5 py-1.5 rounded-full bg-cyan-100 text-cyan-700 text-xs font-bold group-hover:bg-cyan-600 group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-md group-hover:shadow-cyan-200">
                    <span class="relative z-10">Akses Masuk →</span>
                    <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-transparent via-white/50 to-transparent -translate-x-full group-hover:animate-[shine_1.5s_infinite] z-0"></div>
                </span>
            </a>

            <!-- Card 3: Keuangan (New) -->
            <a href="{{ route('keuangan.login') }}" class="glass-panel rounded-3xl p-6 relative overflow-hidden group card-hover text-center border-2 border-transparent hover:border-amber-200 flex flex-col justify-center items-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white mb-4 shadow-lg shadow-amber-200 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-lg font-bold text-slate-800 mb-1 group-hover:text-amber-600 transition-colors">Keuangan</h2>
                <p class="text-slate-500 text-xs mb-4 text-balance">Manajemen Finansial</p>
                
                <span class="relative overflow-hidden inline-block px-5 py-1.5 rounded-full bg-amber-100 text-amber-700 text-xs font-bold group-hover:bg-amber-600 group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-md group-hover:shadow-amber-200">
                    <span class="relative z-10">Akses Masuk →</span>
                    <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-transparent via-white/50 to-transparent -translate-x-full group-hover:animate-[shine_1.5s_infinite] z-0"></div>
                </span>
            </a>

            <!-- Card 4: Pegawai (New) -->
            <a href="{{ route('pegawai.login') }}" class="glass-panel rounded-3xl p-6 relative overflow-hidden group card-hover text-center border-2 border-transparent hover:border-fuchsia-200 flex flex-col justify-center items-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center text-white mb-4 shadow-lg shadow-purple-200 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.95 2-2.122 2H8.878c-1.173 0-2.122-1.116-2.122-2"></path></svg>
                </div>
                <h2 class="text-lg font-bold text-slate-800 mb-1 group-hover:text-purple-600 transition-colors">Pegawai</h2>
                <p class="text-slate-500 text-xs mb-4 text-balance">Portal Layanan Staff</p>
                
                <span class="relative overflow-hidden inline-block px-5 py-1.5 rounded-full bg-purple-100 text-purple-700 text-xs font-bold group-hover:bg-purple-600 group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-md group-hover:shadow-purple-200">
                    <span class="relative z-10">Akses Masuk →</span>
                    <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-transparent via-white/50 to-transparent -translate-x-full group-hover:animate-[shine_1.5s_infinite] z-0"></div>
                </span>
            </a>

            <!-- Card 5: Aset (Existing) -->
            <a href="{{ route('aset.login') }}" class="glass-panel rounded-3xl p-6 relative overflow-hidden group card-hover text-center border-2 border-transparent hover:border-lime-200 flex flex-col justify-center items-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-lime-400 to-green-500 flex items-center justify-center text-white mb-4 shadow-lg shadow-lime-200 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h2 class="text-lg font-bold text-slate-800 mb-1 group-hover:text-lime-600 transition-colors">Sistem Aset</h2>
                <p class="text-slate-500 text-xs mb-4 text-balance">Inventaris & Logistik</p>
                
                <span class="relative overflow-hidden inline-block px-5 py-1.5 rounded-full bg-lime-100 text-lime-700 text-xs font-bold group-hover:bg-lime-600 group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-md group-hover:shadow-lime-200">
                    <span class="relative z-10">Akses Masuk →</span>
                    <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-transparent via-white/50 to-transparent -translate-x-full group-hover:animate-[shine_1.5s_infinite] z-0"></div>
                </span>
            </a>

        </div>

        <!-- Bottom Note -->
        <div class="text-center mt-12 animate-float-delayed">
            <p class="text-sm font-semibold text-slate-400 bg-white/50 inline-block px-4 py-2 rounded-full backdrop-blur-sm border border-white/60">
                🚀 Efisiensi Kerja dalam Satu Genggaman
            </p>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-12 text-center text-slate-400 text-xs font-medium">
        &copy; Copyright © 2026 - YARSI NTB
    </footer>

</body>
</html>
