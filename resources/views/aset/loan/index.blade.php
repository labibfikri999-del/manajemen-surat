@extends('aset.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-4 mb-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Peminjaman Aset</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola data sirkulasi dan peminjaman inventaris</p>
        </div>
        <div class="flex gap-3">
            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-md transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Peminjaman
            </button>
        </div>
    </div>

    <!-- Main Card Placeholder -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-sm border border-white p-12 text-center flex flex-col items-center justify-center min-h-[400px]">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 mb-6 border border-slate-100 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Transaksi Peminjaman</h3>
        <p class="text-slate-500 max-w-md">Modul manajemen Peminjaman telah terpasang. Tampilan sirkulasi dan approval sedang dalam tahap penyesuaian akhir.</p>
    </div>
</div>
@endsection
