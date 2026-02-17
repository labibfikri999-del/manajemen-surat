@extends('sdm.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <!-- Hero / Header Section -->
    <div class="bg-indigo-600 rounded-2xl p-6 sm:p-10 relative overflow-hidden shadow-lg">
        <div class="relative z-10">
            <h1 class="text-3xl font-bold tracking-tight text-white mb-2">Laporan SDM</h1>
            <p class="text-indigo-100 opacity-90 max-w-2xl">Pusat data dan pelaporan untuk analisis kepegawaian, struktur jabatan, dan demografi karyawan secara komprehensif.</p>
        </div>
        
        <!-- Decorative bg -->
        <div class="absolute right-0 top-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
        <div class="absolute left-10 bottom-0 w-32 h-32 bg-purple-500 opacity-20 rounded-full blur-2xl"></div>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Data Karyawan -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow group overflow-hidden flex flex-col h-full">
            <div class="p-6 flex-1">
                <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Data Karyawan</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Daftar lengkap seluruh karyawan aktif dengan detail NIP, Nama, dan Status Kepegawaian.</p>
            </div>
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex gap-2">
                 <a href="{{ route('sdm.laporan.data-karyawan') }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm">
                    Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Rekapitulasi Jabatan -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow group overflow-hidden flex flex-col h-full">
            <div class="p-6 flex-1">
                <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center text-amber-600 mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Rekapitulasi Jabatan</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Ringkasan distribusi karyawan berdasarkan struktur jabatan fungsional dan struktural.</p>
            </div>
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex gap-2">
                 <a href="{{ route('sdm.laporan.rekap-jabatan') }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-amber-600 hover:border-amber-200 transition-all shadow-sm">
                    Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Rekapitulasi Golongan -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow group overflow-hidden flex flex-col h-full">
            <div class="p-6 flex-1">
                 <div class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600 mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Rekapitulasi Golongan</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Analisis status golongan dan ruang kerja karyawan dengan detail jumlah per kategori.</p>
            </div>
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex gap-2">
                 <a href="{{ route('sdm.laporan.rekap-golongan') }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-emerald-600 hover:border-emerald-200 transition-all shadow-sm">
                    Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Laporan Masa Kerja -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow group overflow-hidden flex flex-col h-full">
            <div class="p-6 flex-1">
                 <div class="w-12 h-12 bg-sky-50 rounded-lg flex items-center justify-center text-sky-600 mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Laporan Masa Kerja</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Detail masa kerja setiap karyawan, dihitung dari tanggal bergabung hingga saat ini.</p>
            </div>
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex gap-2">
                 <a href="{{ route('sdm.laporan.masa-kerja') }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-sky-600 hover:border-sky-200 transition-all shadow-sm">
                    Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Laporan Pendidikan -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow group overflow-hidden flex flex-col h-full">
             <div class="p-6 flex-1">
                 <div class="w-12 h-12 bg-pink-50 rounded-lg flex items-center justify-center text-pink-600 mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Laporan Pendidikan</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Informasi latar belakang pendidikan formal karyawan dari berbagai institusi dan jenjang.</p>
            </div>
             <div class="p-4 bg-slate-50 border-t border-slate-100 flex gap-2">
                 <a href="{{ route('sdm.laporan.pendidikan') }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-pink-600 hover:border-pink-200 transition-all shadow-sm">
                    Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Data Keluarga -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow group overflow-hidden flex flex-col h-full">
            <div class="p-6 flex-1">
                 <div class="w-12 h-12 bg-teal-50 rounded-lg flex items-center justify-center text-teal-600 mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Data Keluarga</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Catatan anggota keluarga yang menjadi tanggungan karyawan untuk keperluan asuransi dan tunjangan.</p>
            </div>
             <div class="p-4 bg-slate-50 border-t border-slate-100 flex gap-2">
                 <a href="{{ route('sdm.laporan.keluarga') }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-teal-600 hover:border-teal-200 transition-all shadow-sm">
                    Lihat Laporan
                </a>
            </div>
        </div>
        
    </div>
</div>
@endsection
