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
        html {
            scroll-behavior: smooth;
        }
        body {
            /* Background handled by fixed layer */
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
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
<body class="antialiased text-slate-800 min-h-screen relative flex flex-col items-center py-12 px-4">

    <!-- Fixed Background Layer (The "Unified" Canvas) -->
    <div class="fixed inset-0 w-full h-full -z-50">
        <!-- Base Gradient -->
        <div class="absolute inset-0 w-full h-full bg-[radial-gradient(circle_at_top_center,_#f0fdfa_0%,_#ccfbf1_40%,_#e0f2fe_100%)]"></div>
    </div>

    <!-- Animated Background Blobs (Fixed to viewport for consistent parallax) -->
    <!-- Animated Background Blobs (Optimization: will-change-transform) -->
    <!-- Animated Background Blobs (Optimization: will-change-transform, removed blend modes) -->
    <div class="fixed inset-0 w-full h-full -z-10 pointer-events-none overflow-hidden">
        <div class="absolute top-0 -left-10 w-96 h-96 bg-cyan-300/40 rounded-full filter blur-[60px] animate-blob will-change-transform"></div>
        <div class="absolute top-0 -right-10 w-96 h-96 bg-emerald-300/40 rounded-full filter blur-[60px] animate-blob animation-delay-2000 will-change-transform"></div>
        <div class="absolute -bottom-32 left-20 w-96 h-96 bg-lime-300/40 rounded-full filter blur-[60px] animate-blob animation-delay-4000 will-change-transform"></div>
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

            <!-- JS Parallax Removed for Performance -->
            <!-- The CSS 'animate-blob' class handles the ambient movement efficiently -->
            
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

    <!-- Chatbot Floating Widget -->
    <div id="chatbot-container" class="fixed bottom-6 right-6 z-50 animate-fade-in font-sans">
        <!-- Chatbot Toggler Button -->
        <button id="chatbot-toggle" class="w-14 h-14 rounded-full bg-emerald-600 text-white shadow-[0_0_20px_rgba(16,185,129,0.4)] flex items-center justify-center hover:bg-emerald-500 hover:scale-110 hover:shadow-[0_0_25px_rgba(16,185,129,0.6)] transition-all duration-300 relative group focus:outline-none focus:ring-4 focus:ring-emerald-300">
            <!-- Pulse ring effect behind icon -->
            <div class="absolute inset-0 rounded-full border-2 border-emerald-400 opacity-0 group-hover:animate-ping z-0"></div>
            
            <svg id="chatbot-icon-msg" class="w-6 h-6 absolute transition-all duration-300 scale-100 opacity-100 group-hover:-translate-y-0.5 z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            <svg id="chatbot-icon-close" class="w-6 h-6 absolute transition-all duration-300 scale-50 opacity-0 z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"></path></svg>
            
            <!-- Notification Badge -->
            <span class="absolute top-0 right-0 w-3.5 h-3.5 bg-red-500 border-2 border-white rounded-full z-20"></span>
        </button>

        <!-- Chatbot Window panel -->
        <div id="chatbot-panel" class="absolute bottom-20 right-0 w-[350px] sm:w-[400px] h-[550px] max-h-[80vh] bg-[#f8fafc] rounded-[24px] shadow-2xl border border-white/60 flex flex-col overflow-hidden transition-all duration-400 ease-[cubic-bezier(0.34,1.56,0.64,1)] origin-bottom-right scale-0 opacity-0 pointer-events-none ring-1 ring-black/5">
            
            <!-- Header (Glassmorphism) -->
            <div class="relative p-4 text-emerald-900 border-b border-white z-10 overflow-hidden shrink-0">
                <!-- Glass Background -->
                <div class="absolute inset-0 bg-emerald-500/10 backdrop-blur-xl z-0"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-white/60 to-white/20 z-0"></div>
                
                <div class="relative flex justify-between items-center z-10">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[15px] leading-tight text-slate-800">YARSI Assistant</h3>
                            <div class="flex items-center mt-0.5 space-x-1.5">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                <span class="text-[11px] font-medium text-slate-500">Online & Siap Membantu</span>
                            </div>
                        </div>
                    </div>
                    <button id="chatbot-reset" title="Mulai Obrolan Baru" class="p-2 rounded-xl text-slate-400 hover:text-emerald-600 hover:bg-white/50 transition-colors focus:outline-none border border-transparent hover:border-white/50 hover:shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Messages Area -->
            <div id="chatbot-messages" class="flex-1 p-5 overflow-y-auto space-y-5 scroll-smooth relative z-0">
                <!-- Welcome Message -->
                <div class="flex items-start">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center mr-3 flex-shrink-0 border border-emerald-200 mt-1 shadow-sm">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="bg-white p-3.5 rounded-2xl rounded-tl-none border border-slate-100 shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05)] text-[13px] text-slate-700 max-w-[85%] leading-relaxed">
                        Halo 👋 Saya adalah Asisten AI Pintar YARSI NTB.
                        <br><br>
                        Ada yang bisa saya bantu hari ini terkait layanan operasional atau informasi Yayasan?
                    </div>
                </div>

                <!-- Suggested Questions Chips -->
                <div id="chatbot-suggestions" class="flex flex-col gap-2 pt-2 px-11 w-full">
                    <button class="suggestion-btn text-[12px] bg-white border border-slate-200 text-slate-600 rounded-xl px-4 py-2 hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-700 transition-all shadow-sm text-left relative overflow-hidden group">
                        <span class="relative z-10">Tugas pokok direktur?</span>
                        <div class="absolute inset-0 bg-emerald-50 translate-y-full group-hover:translate-y-0 transition-transform duration-300 z-0"></div>
                    </button>
                    <button class="suggestion-btn text-[12px] bg-white border border-slate-200 text-slate-600 rounded-xl px-4 py-2 hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-700 transition-all shadow-sm text-left relative overflow-hidden group">
                        <span class="relative z-10">Bagaimana struktur organisasi Yayasan?</span>
                        <div class="absolute inset-0 bg-emerald-50 translate-y-full group-hover:translate-y-0 transition-transform duration-300 z-0"></div>
                    </button>
                    <button class="suggestion-btn text-[12px] bg-white border border-slate-200 text-slate-600 rounded-xl px-4 py-2 hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-700 transition-all shadow-sm text-left relative overflow-hidden group">
                        <span class="relative z-10">Wewenang RS Islam?</span>
                        <div class="absolute inset-0 bg-emerald-50 translate-y-full group-hover:translate-y-0 transition-transform duration-300 z-0"></div>
                    </button>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t border-slate-100 shrink-0">
                <form id="chatbot-form" class="relative group">
                    <input type="text" id="chatbot-input" class="w-full bg-slate-50 text-[13px] text-slate-700 border border-slate-200 rounded-full pl-5 pr-14 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 focus:bg-white transition-all placeholder:text-slate-400 shadow-inner group-hover:border-slate-300" placeholder="Ketik pesan..." autocomplete="off">
                    
                    <button type="submit" id="chatbot-submit" class="absolute right-1.5 top-1.5 bottom-1.5 w-10 bg-emerald-500 text-white rounded-full flex items-center justify-center hover:bg-emerald-600 transition-transform hover:scale-105 active:scale-95 focus:outline-none disabled:opacity-50 disabled:hover:scale-100 disabled:cursor-not-allowed shadow-sm group-focus-within:bg-emerald-600">
                        <svg class="w-4 h-4 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </form>
                <div class="text-center mt-3 flex justify-center items-center space-x-1">
                    <svg class="w-3 h-3 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
                    <span class="text-[10px] font-medium text-slate-400">Powered by AI Analytics</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Chatbot Logic -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('chatbot-toggle');
            const panel = document.getElementById('chatbot-panel');
            const iconMsg = document.getElementById('chatbot-icon-msg');
            const iconClose = document.getElementById('chatbot-icon-close');
            const msgsContainer = document.getElementById('chatbot-messages');
            const cForm = document.getElementById('chatbot-form');
            const cInput = document.getElementById('chatbot-input');
            const cSubmit = document.getElementById('chatbot-submit');
            const cReset = document.getElementById('chatbot-reset');
            const suggestionsContainer = document.getElementById('chatbot-suggestions');
            
            let isOpen = false;

            // Markdown Config
            marked.setOptions({ breaks: true, gfm: true });

            // Toggle Panel Logic
            toggleBtn.addEventListener('click', () => {
                isOpen = !isOpen;
                if(isOpen) {
                    panel.classList.remove('scale-0', 'opacity-0', 'pointer-events-none');
                    panel.classList.add('scale-100', 'opacity-100', 'pointer-events-auto');
                    iconMsg.style.transform = 'scale(0) rotate(-45deg)';
                    iconMsg.style.opacity = '0';
                    iconClose.style.transform = 'scale(1) rotate(0deg)';
                    iconClose.style.opacity = '1';
                    
                    // Hide notification badge
                    const badge = toggleBtn.querySelector('span.bg-red-500');
                    if(badge) badge.classList.add('hidden');
                    
                    setTimeout(() => cInput.focus(), 300);
                } else {
                    panel.classList.replace('scale-100', 'scale-0');
                    panel.classList.replace('opacity-100', 'opacity-0');
                    panel.classList.replace('pointer-events-auto', 'pointer-events-none');
                    
                    iconClose.style.transform = 'scale(0) rotate(45deg)';
                    iconClose.style.opacity = '0';
                    iconMsg.style.transform = 'scale(1) rotate(0)';
                    iconMsg.style.opacity = '1';
                }
            });

            // Focus Effects
            cInput.addEventListener('focus', () => cSubmit.parentElement.classList.add('ring-2', 'ring-emerald-500/20', 'rounded-full'));
            cInput.addEventListener('blur', () => cSubmit.parentElement.classList.remove('ring-2', 'ring-emerald-500/20', 'rounded-full'));

            function appendUserMessage(text) {
                if(suggestionsContainer) suggestionsContainer.style.display = 'none';
                
                const msgHTML = `
                <div class="flex items-end justify-end space-x-2 animate-fade-in pl-10 mb-2">
                    <div class="bg-gradient-to-tr from-emerald-600 to-teal-500 text-white p-3.5 rounded-2xl rounded-br-sm text-[13px] shadow-sm max-w-[85%] break-words leading-relaxed font-medium">
                        ${text.replace(/</g, "&lt;").replace(/>/g, "&gt;")}
                    </div>
                </div>`;
                msgsContainer.insertAdjacentHTML('beforeend', msgHTML);
                scrollToBottom();
            }

            function appendAIMessage(text) {
                // Remove citation marks e.g. 【7:0†source】
                const cleanText = text.replace(/【.*?】/g, '');
                const parsedText = marked.parse(cleanText);
                
                const msgHTML = `
                <div class="flex items-start space-x-3 animate-fade-in pr-6 mb-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-50 to-teal-50 flex items-center justify-center flex-shrink-0 border border-emerald-100 mt-0.5 shadow-sm">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div class="bg-white p-4 rounded-2xl rounded-tl-sm border border-slate-100 shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05)] text-[13px] text-slate-700 max-w-[90%] leading-relaxed prose prose-sm prose-emerald prose-p:my-1 prose-ul:my-1 prose-li:my-0.5" style="& ul { list-style-type: disc; padding-left: 1.5rem; } ol { list-style-type: decimal; padding-left: 1.5rem; }">
                        ${parsedText}
                    </div>
                </div>`;
                msgsContainer.insertAdjacentHTML('beforeend', msgHTML);
                scrollToBottom();
            }

            function showTyping() {
                const typingHTML = `
                <div id="typing-indicator" class="flex items-start space-x-3 animate-fade-in mb-2">
                    <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0 border border-emerald-100 mt-0.5 shadow-sm">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" /></svg>
                    </div>
                    <div class="bg-white px-4 py-3.5 rounded-2xl rounded-tl-sm border border-slate-100 shadow-sm flex space-x-1.5 items-center">
                        <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-bounce [animation-delay:-0.3s]"></div>
                        <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-bounce [animation-delay:-0.15s]"></div>
                        <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-bounce"></div>
                    </div>
                </div>`;
                msgsContainer.insertAdjacentHTML('beforeend', typingHTML);
                scrollToBottom();
            }

            function removeTyping() {
                const typingEl = document.getElementById('typing-indicator');
                if(typingEl) typingEl.remove();
            }

            function scrollToBottom() {
                msgsContainer.scrollTop = msgsContainer.scrollHeight;
            }

            async function sendMessage(text) {
                if(!text.trim()) return;
                
                cInput.value = '';
                cSubmit.disabled = true;
                cInput.disabled = true;
                
                appendUserMessage(text);
                showTyping();

                try {
                    const response = await fetch('/chatbot/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: text })
                    });

                    const data = await response.json();
                    
                    removeTyping();
                    cSubmit.disabled = false;
                    cInput.disabled = false;
                    cInput.focus();

                    if (!response.ok) {
                        appendAIMessage(data.error || "Terjadi kesalahan sambungan jaringan.");
                    } else {
                        appendAIMessage(data.response);
                    }
                } catch (error) {
                    removeTyping();
                    cSubmit.disabled = false;
                    cInput.disabled = false;
                    appendAIMessage("Koneksi terputus. Silakan periksa jaringan Anda dan coba lagi.");
                }
            }

            cForm.addEventListener('submit', (e) => {
                e.preventDefault();
                sendMessage(cInput.value);
            });

            document.querySelectorAll('.suggestion-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    sendMessage(this.querySelector('span').innerText);
                });
            });

            // Soft Reset
            cReset.addEventListener('click', async () => {
                if(!confirm('Mulai sesi obrolan baru?')) return;
                
                try {
                    cReset.querySelector('svg').classList.add('animate-spin');
                    await fetch('/chatbot/reset', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                    
                    const welcomeMsg = msgsContainer.firstElementChild.outerHTML;
                    msgsContainer.innerHTML = welcomeMsg;
                    if(suggestionsContainer) {
                        msgsContainer.innerHTML += suggestionsContainer.outerHTML;
                        suggestionsContainer.style.display = 'flex';
                    }
                    cReset.querySelector('svg').classList.remove('animate-spin');
                } catch(e) {
                    cReset.querySelector('svg').classList.remove('animate-spin');
                }
            });
        });
    </script>
</body>

</html>
