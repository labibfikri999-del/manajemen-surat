@extends('sdm.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeTab: 'overview' }">
    <!-- Breadcrumb -->
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-2 text-slate-500 text-sm">
            <a href="{{ route('sdm.pegawai.index') }}" class="hover:text-indigo-600 transition-colors flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Data Karyawan
            </a>
            <span>/</span>
            <span class="text-slate-800 font-semibold">Detail Karyawan</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- LEFT SIDEBAR: Profile Summary -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 text-center relative overflow-hidden">
                <!-- Decorative BG for card -->
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-br from-indigo-50 to-slate-50 z-0"></div>
                
                <div class="relative z-10">
                    <div class="w-32 h-32 mx-auto rounded-full p-1 bg-white border-2 border-indigo-100 shadow-sm mb-4">
                        @if($pegawai->foto)
                            <img src="{{ asset('storage/' . $pegawai->foto) }}" alt="{{ $pegawai->name }}" class="w-full h-full rounded-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($pegawai->name) }}&background=6366f1&color=fff&size=256" alt="{{ $pegawai->name }}" class="w-full h-full rounded-full object-cover">
                        @endif
                    </div>
                    
                    <h2 class="text-xl font-bold text-slate-800 leading-tight mb-1">{{ $pegawai->name }}</h2>
                    <p class="text-sm text-slate-500 font-medium mb-3">{{ $pegawai->role }}</p>

                    <div class="flex flex-wrap justify-center gap-2 mb-6">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $pegawai->status == 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                            {{ $pegawai->status == 'active' ? 'AKTIF' : 'NON-AKTIF' }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                            {{ $pegawai->status_kepegawaian ?? '-' }}
                        </span>
                    </div>

                    <div class="border-t border-slate-100 pt-4 text-left space-y-3">
                        <div>
                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mb-0.5">NIP</p>
                            <p class="font-mono font-semibold text-slate-700 text-sm">{{ $pegawai->nip }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mb-0.5">Masa Kerja</p>
                            @if($pegawai->join_date)
                                @php
                                    $join = \Carbon\Carbon::parse($pegawai->join_date);
                                    $diff = $join->diff(\Carbon\Carbon::now());
                                @endphp
                                <p class="font-semibold text-slate-700 text-sm">{{ $diff->y }} Tahun {{ $diff->m }} Bulan</p>
                            @else
                                <p class="text-slate-400 text-sm">-</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('sdm.pegawai.edit', $pegawai->id) }}" class="block w-full py-2.5 bg-amber-400 hover:bg-amber-500 text-amber-950 font-bold rounded-xl transition-colors shadow-sm shadow-amber-200">
                            Edit Data
                        </a>
                        <form action="{{ route('sdm.pegawai.destroy', $pegawai->id) }}" method="POST" onsubmit="return confirm('Hapus data pegawai ini?');" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold rounded-xl transition-colors">
                                Hapus Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT MAIN CONTENT: Tabs -->
        <div class="lg:col-span-3">
            <!-- Tabs Navigation -->
            <div class="bg-white rounded-t-2xl shadow-sm border-b border-slate-200 px-2 flex overflow-x-auto no-scrollbar">
                <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="px-6 py-4 border-b-2 font-bold text-sm transition-colors whitespace-nowrap flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Pribadi
                </button>
                <button @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="px-6 py-4 border-b-2 font-bold text-sm transition-colors whitespace-nowrap flex items-center gap-2">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    Kontak
                </button>
                <button @click="activeTab = 'pendidikan'" :class="activeTab === 'pendidikan' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="px-6 py-4 border-b-2 font-bold text-sm transition-colors whitespace-nowrap flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                    Pendidikan
                </button>
                <button @click="activeTab = 'keluarga'" :class="activeTab === 'keluarga' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="px-6 py-4 border-b-2 font-bold text-sm transition-colors whitespace-nowrap flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Keluarga
                </button>
                 <button @click="activeTab = 'jabatan'" :class="activeTab === 'jabatan' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="px-6 py-4 border-b-2 font-bold text-sm transition-colors whitespace-nowrap flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Jabatan & Pangkat
                </button>
            </div>

            <div class="bg-white rounded-b-2xl shadow-sm border border-t-0 border-slate-200 p-6 min-h-[400px]">
                
                 <!-- TAB: PRIBADI (Overview) -->
                 <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="flex items-center justify-between mb-2">
                         <h3 class="text-lg font-bold text-slate-800">Informasi Pribadi</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                         <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Jenis Kelamin</label>
                            <p class="text-slate-700 font-medium text-base">{{ $pegawai->jenis_kelamin == 'L' ? 'Laki-laki' : ($pegawai->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</p>
                        </div>
                         <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Email</label>
                            <p class="text-slate-700 font-medium text-base">{{ $pegawai->email ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Telepon</label>
                             <p class="text-slate-700 font-medium text-base">{{ $pegawai->phone ?? '-' }}</p>
                        </div>
                         <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Tempat Lahir</label>
                             <p class="text-slate-700 font-medium text-base">{{ $pegawai->tempat_lahir ?? '-' }}</p>
                        </div>
                        <div>
                             <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Tanggal Lahir</label>
                             <p class="text-slate-700 font-medium text-base">{{ $pegawai->tanggal_lahir ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- TAB: KONTAK -->
                <div x-show="activeTab === 'contact'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-slate-800">Informasi Kontak</h3>
                         <a href="{{ route('sdm.pegawai.edit', $pegawai->id) }}" class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition">Edit Kontak</a>
                    </div>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                             <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-400 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                             </div>
                             <div>
                                 <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Alamat Lengkap</label>
                                 <p class="text-slate-700 font-medium">{{ $pegawai->alamat_lengkap ?? 'Belum diisi' }}</p>
                             </div>
                        </div>

                         <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                             <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-400 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                             </div>
                             <div>
                                 <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Nomor Telepon</label>
                                 <p class="text-slate-700 font-medium text-lg">{{ $pegawai->phone ?? '-' }}</p>
                             </div>
                        </div>

                         <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                             <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-400 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                             </div>
                             <div>
                                 <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Alamat Email</label>
                                 <p class="text-slate-700 font-medium text-lg">{{ $pegawai->email ?? '-' }}</p>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- TAB: PENDIDIKAN -->
                <!-- TAB: PENDIDIKAN -->
                <div x-show="activeTab === 'pendidikan'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                        <h3 class="text-lg font-bold text-slate-800">Riwayat Pendidikan</h3>
                        <div class="flex space-x-2">
                             <a href="{{ route('sdm.pendidikan.create', ['pegawai_id' => $pegawai->id]) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Pendidikan
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                        @if($pegawai->pendidikans && $pegawai->pendidikans->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Jenjang</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Institusi</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Jurusan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tahun Lulus</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Dokumen</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @foreach($pegawai->pendidikans as $edu)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $edu->jenjang }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $edu->institusi }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $edu->jurusan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $edu->tahun_lulus }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($edu->dokumen_path)
                                                <a href="{{ Storage::url($edu->dokumen_path) }}" target="_blank" class="inline-flex items-center px-2.5 py-1.5 border border-slate-300 shadow-sm text-xs font-medium rounded text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                                    <svg class="mr-1.5 h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    Ijazah
                                                </a>
                                            @else
                                                <span class="text-slate-400 text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('sdm.pendidikan.edit', $edu->id) }}" class="text-amber-500 hover:text-amber-600 bg-amber-50 hover:bg-amber-100 p-2 rounded-full transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </a>
                                                <form action="{{ route('sdm.pendidikan.destroy', $edu->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pendidikan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-500 hover:text-rose-600 bg-rose-50 hover:bg-rose-100 p-2 rounded-full transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                            </div>
                            <h3 class="text-lg font-medium text-slate-900">Belum ada data pendidikan</h3>
                            <p class="mt-1 text-sm text-slate-500">Mulai dengan menambahkan riwayat pendidikan pegawai ini.</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- TAB: KELUARGA -->
                <!-- TAB: KELUARGA -->
                <div x-show="activeTab === 'keluarga'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                        <h3 class="text-lg font-bold text-slate-800">Data Keluarga</h3>
                        <div class="flex space-x-2">
                             <a href="{{ route('sdm.keluarga.create', ['pegawai_id' => $pegawai->id]) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Keluarga
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                        @if($pegawai->keluargas && $pegawai->keluargas->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Hubungan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Lahir</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Usia</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Pekerjaan</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Dokumen</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                                    @foreach($pegawai->keluargas as $fam)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 capitalize">{{ $fam->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 capitalize">
                                                {{ $fam->hubungan }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ \Carbon\Carbon::parse($fam->tgl_lahir)->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ \Carbon\Carbon::parse($fam->tgl_lahir)->age }} thn</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 capitalize">{{ $fam->pekerjaan ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($fam->dokumen_path)
                                                <a href="{{ Storage::url($fam->dokumen_path) }}" target="_blank" class="inline-flex items-center px-2.5 py-1.5 border border-slate-300 shadow-sm text-xs font-medium rounded text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                                    <svg class="mr-1.5 h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    Lihat
                                                </a>
                                            @else
                                                <span class="text-slate-400 text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('sdm.keluarga.edit', $fam->id) }}" class="text-amber-500 hover:text-amber-600 bg-amber-50 hover:bg-amber-100 p-2 rounded-full transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </a>
                                                <form action="{{ route('sdm.keluarga.destroy', $fam->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data keluarga ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-500 hover:text-rose-600 bg-rose-50 hover:bg-rose-100 p-2 rounded-full transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <h3 class="text-lg font-medium text-slate-900">Belum ada data keluarga</h3>
                            <p class="mt-1 text-sm text-slate-500">Tambahkan data keluarga pegawai ini.</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- TAB: JABATAN -->
                <div x-show="activeTab === 'jabatan'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <h3 class="text-lg font-bold text-slate-800 mb-6">Riwayat Jabatan & Pangkat</h3>

                    <!-- Riwayat Jabatan Table -->
                    <div class="mb-8">
                        <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wide mb-4 border-b border-slate-100 pb-2">Riwayat Jabatan</h4>
                         @if($pegawai->riwayatJabatans && $pegawai->riwayatJabatans->count() > 0)
                        <div class="overflow-x-auto rounded-lg border border-slate-200">
                             <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-xs">
                                    <tr>
                                        <th class="px-4 py-3">Nama Jabatan</th>
                                        <th class="px-4 py-3">Kategori</th>
                                        <th class="px-4 py-3">TMT</th>
                                        <th class="px-4 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($pegawai->riwayatJabatans as $history)
                                    <tr>
                                        <td class="px-4 py-3 font-semibold text-slate-700">{{ $history->masterJabatan->nama_jabatan ?? '-' }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $history->kategori }}</td>
                                        <td class="px-4 py-3 text-slate-500">{{ \Carbon\Carbon::parse($history->tgl_mulai)->format('d F Y') }}</td>
                                        <td class="px-4 py-3">
                                            @if($history->is_active)
                                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600">Aktif</span>
                                            @else
                                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                         <p class="text-slate-400 italic text-sm">Belum ada riwayat jabatan.</p>
                         @endif
                    </div>

                    <!-- Riwayat Pangkat Table -->
                    <div>
                         <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wide mb-4 border-b border-slate-100 pb-2">Riwayat Pangkat/Golongan</h4>
                         @if($pegawai->riwayatPangkats && $pegawai->riwayatPangkats->count() > 0)
                         <div class="overflow-x-auto rounded-lg border border-slate-200">
                             <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-xs">
                                    <tr>
                                        <th class="px-4 py-3">Golongan</th>
                                        <th class="px-4 py-3">Ruang</th>
                                        <th class="px-4 py-3">TMT</th>
                                        <th class="px-4 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($pegawai->riwayatPangkats as $pangkat)
                                    <tr>
                                        <td class="px-4 py-3 font-semibold text-slate-700">{{ $pangkat->golongan }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $pangkat->ruang }}</td>
                                        <td class="px-4 py-3 text-slate-500">{{ \Carbon\Carbon::parse($pangkat->tmt)->format('d F Y') }}</td>
                                        <td class="px-4 py-3">
                                            @if($pangkat->is_active)
                                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600">Aktif</span>
                                            @else
                                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500">Historis</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                         <p class="text-slate-400 italic text-sm">Belum ada riwayat pangkat.</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
