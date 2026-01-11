@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Data Pegawai</h1>
            <p class="text-slate-500">Kelola data seluruh pegawai rumah sakit.</p>
        </div>
        <a href="{{ route('sdm.pegawai.create') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-cyan-200 transition-all active:scale-95 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Pegawai
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <form action="{{ route('sdm.pegawai.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <div class="relative md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" placeholder="Cari nama, NIP, atau email...">
                <div class="absolute left-3 top-3 text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <select name="role" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500">
                <option value="">-- Semua Jabatan --</option>
                <option value="Dokter Umum" {{ request('role') == 'Dokter Umum' ? 'selected' : '' }}>Dokter Umum</option>
                <option value="Dokter Spesialis" {{ request('role') == 'Dokter Spesialis' ? 'selected' : '' }}>Dokter Spesialis</option>
                <option value="Dokter Gigi" {{ request('role') == 'Dokter Gigi' ? 'selected' : '' }}>Dokter Gigi</option>
                <option value="Perawat Senior" {{ request('role') == 'Perawat Senior' ? 'selected' : '' }}>Perawat Senior</option>
                <option value="Perawat" {{ request('role') == 'Perawat' ? 'selected' : '' }}>Perawat</option>
                <option value="Apoteker" {{ request('role') == 'Apoteker' ? 'selected' : '' }}>Apoteker</option>
                <option value="Staff Admin" {{ request('role') == 'Staff Admin' ? 'selected' : '' }}>Staff Admin</option>
                <option value="Cleaning Service" {{ request('role') == 'Cleaning Service' ? 'selected' : '' }}>Cleaning Service</option>
            </select>

            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold transition-all">
                Filter
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-bold text-slate-700">Pegawai</th>
                        <th class="px-6 py-4 font-bold text-slate-700">Jabatan</th>
                        <th class="px-6 py-4 font-bold text-slate-700">Kontak</th>
                        <th class="px-6 py-4 font-bold text-slate-700">Status</th>
                        <th class="px-6 py-4 font-bold text-slate-700 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($pegawais as $pegawai)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-600 font-bold text-lg">
                                    {{ substr($pegawai->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800">{{ $pegawai->name }}</h3>
                                    <p class="text-xs text-slate-500">NIP: {{ $pegawai->nip }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $pegawai->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            <div class="flex flex-col">
                                <span>{{ $pegawai->email ?? '-' }}</span>
                                <span class="text-xs text-slate-400">{{ $pegawai->phone ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($pegawai->status == 'active')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-50 text-slate-600 border border-slate-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('sdm.pegawai.edit', $pegawai->id) }}" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-cyan-600 hover:border-cyan-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('sdm.pegawai.destroy', $pegawai->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pegawai ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-red-600 hover:border-red-200 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <p>Belum ada data pegawai.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($pegawais->hasPages())
        <div class="p-6 border-t border-slate-100">
            {{ $pegawais->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
