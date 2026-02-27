@extends('aset.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-4 mb-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Penggunaan Aset</h1>
            <p class="text-slate-500 text-sm mt-1">Pantau utilisasi dan durasi pemakaian aset</p>
        </div>
        <div class="flex gap-3">
            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-md transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Catat Penggunaan
            </button>
        </div>
    </div>

    <!-- Main Card Placeholder -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-sm border border-white p-12 text-center flex flex-col items-center justify-center min-h-[400px]">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 mb-6 border border-slate-100 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Data Utilisasi</h3>
        <p class="text-slate-500 max-w-md">Modul analisis Penggunaan Aset telah disiapkan. Fitur perhitungan hour-meter dan log pemakaian sedang dalam pengembangan akhir.</p>
    </div>
</div>
@endsection
