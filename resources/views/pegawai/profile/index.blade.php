@extends('pegawai.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-5xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Data Diri</h1>
            <p class="text-slate-500">Informasi lengkap profil kepegawaian Anda.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- ID Card Design (Left Column) -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200 border border-slate-100 flex flex-col items-center text-center relative overflow-hidden">
                <div class="absolute top-0 w-full h-32 bg-gradient-to-br from-fuchsia-600 to-purple-700"></div>
                
                <div class="w-28 h-28 rounded-2xl bg-white p-1 shadow-lg relative z-10 mt-12 mb-4">
                    <div class="w-full h-full rounded-xl bg-slate-100 flex items-center justify-center text-3xl font-bold text-slate-400 overflow-hidden">
                         <!-- Placeholder for Profile Image if implemented later -->
                         {{ substr($pegawai->name, 0, 1) }}
                    </div>
                </div>

                <h2 class="text-xl font-bold text-slate-800">{{ $pegawai->name }}</h2>
                <p class="text-slate-500 text-sm mb-4">{{ $pegawai->role }}</p>

                <div class="flex flex-wrap justify-center gap-2 mb-6">
                     <span class="px-3 py-1 rounded-full text-xs font-bold bg-fuchsia-100 text-fuchsia-700">Pegawai Tetap</span>
                </div>

                <div class="w-full border-t border-slate-100 pt-6 grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Masa Kerja</p>
                        <p class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($pegawai->join_date)->diffForHumans(null, true) }}</p>
                    </div>
                    <div class="text-center border-l border-slate-100">
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Status</p>
                        <p class="font-bold text-emerald-600">Aktif</p>
                    </div>
                </div>
            </div>

             <!-- Digital Business Card -->
            <div class="bg-slate-800 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden group">
                <div class="absolute right-0 top-0 opacity-10 transform translate-x-12 -translate-y-8 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                </div>
                
                <h3 class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Nomor Induk Pegawai</h3>
                <p class="text-3xl font-mono font-bold tracking-wider mb-6">{{ $pegawai->nip }}</p>

                <div class="flex justify-between items-end">
                    <div>
                         <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Bergabung Sejak</p>
                         <p class="font-medium">{{ \Carbon\Carbon::parse($pegawai->join_date)->translatedFormat('d F Y') }}</p>
                    </div>
                    <img src="{{ asset('images/logo_rsi_ntb_new.png') }}" class="h-10 w-auto opacity-50" alt="Logo">
                </div>
            </div>
        </div>

        <!-- Detailed Info (Right Column) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Personal Information -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Informasi Pribadi</h3>
                        <p class="text-xs text-slate-500">Detail identitas resmi pegawai.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</label>
                        <p class="font-medium text-slate-700 text-lg">{{ $pegawai->name }}</p>
                    </div>
                     <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">NIK (KTP)</label>
                        <p class="font-medium text-slate-700 text-lg font-mono">{{ $pegawai->nik ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tempat, Tanggal Lahir</label>
                        <p class="font-medium text-slate-700">{{ $pegawai->place_of_birth }}, {{ \Carbon\Carbon::parse($pegawai->date_of_birth)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Jenis Kelamin</label>
                        <p class="font-medium text-slate-700">{{ $pegawai->gender }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Kontak & Alamat</h3>
                        <p class="text-xs text-slate-500">Informasi domisili dan kontak yang dapat dihubungi.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                        <p class="font-medium text-slate-700">{{ $pegawai->address }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Email</label>
                        <p class="font-medium text-slate-700">{{ $pegawai->email }}</p>
                    </div>
                     <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor Telepon / WhatsApp</label>
                        <p class="font-medium text-slate-700 font-mono">{{ $pegawai->phone }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
