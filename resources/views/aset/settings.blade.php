@extends('aset.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center bg-blue-500 p-6 rounded-t-2xl shadow-md text-white">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Pengaturan Sistem</h1>
            <p class="text-blue-100 text-sm mt-1">Kelola konfigurasi dan preferensi sistem</p>
        </div>
        <div class="flex items-center gap-4">
             <div class="text-right">
                 <p class="text-xs font-bold text-white">{{ Auth::user()->name ?? 'Administrator' }}</p>
                 <p class="text-[10px] text-blue-200">admin</p>
             </div>
             <div class="w-10 h-10 rounded-full bg-blue-400 flex items-center justify-center">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
             </div>
        </div>
    </div>

    <!-- Tabs Container -->
    <div class="bg-white px-6 border-b border-slate-100 flex gap-8">
        <a href="#" class="py-4 text-sm font-bold text-blue-600 border-b-2 border-blue-600 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            Umum
        </a>
        <a href="#" class="py-4 text-sm font-bold text-slate-500 hover:text-slate-800 flex items-center gap-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Pengguna
        </a>
        <a href="#" class="py-4 text-sm font-bold text-slate-500 hover:text-slate-800 flex items-center gap-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
            Backup & Restore
        </a>
        <a href="#" class="py-4 text-sm font-bold text-slate-500 hover:text-slate-800 flex items-center gap-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Sistem
        </a>
    </div>

    <!-- Main Content Area -->
    <div class="bg-white rounded-b-2xl shadow-sm border border-slate-100 p-8 space-y-8">
        
        <!-- Informasi Sistem -->
        <div>
            <h2 class="text-base font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Informasi Sistem</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 p-4 rounded-xl">
                    <p class="text-[11px] font-bold text-blue-400 uppercase tracking-wider mb-1">Versi</p>
                    <p class="text-lg font-bold text-blue-800">1.0.0</p>
                </div>
                <div class="bg-emerald-50 p-4 rounded-xl">
                    <p class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider mb-1">Status</p>
                    <p class="text-lg font-bold text-emerald-600">Aktif</p>
                </div>
                <div class="bg-amber-50 p-4 rounded-xl">
                    <p class="text-[11px] font-bold text-amber-500 uppercase tracking-wider mb-1">Total Pengguna</p>
                    <p class="text-lg font-bold text-amber-700">5</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-xl">
                    <p class="text-[11px] font-bold text-purple-400 uppercase tracking-wider mb-1">Total Aset</p>
                    <p class="text-lg font-bold text-purple-800">8</p>
                </div>
            </div>
        </div>

        <!-- Form Pengaturan -->
        <div>
             <h2 class="text-base font-bold text-slate-800 mb-6 border-b border-slate-100 pb-2">Pengaturan Umum</h2>
             
             <form class="space-y-6 max-w-4xl">
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <div>
                         <label class="block text-sm font-semibold text-slate-600 mb-2">Nama Institusi</label>
                         <input type="text" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400 text-sm h-11" placeholder="Masukkan nama institusi" value="Institut Kesehatan">
                     </div>
                     <div>
                         <label class="block text-sm font-semibold text-slate-600 mb-2">Zona Waktu</label>
                         <select class="w-full rounded-lg border-slate-200 focus:border-blue-500 text-slate-600 text-sm h-11">
                             <option value="Asia/Jakarta">Asia/Jakarta (WIB)</option>
                             <option value="Asia/Makassar">Asia/Makassar (WITA)</option>
                             <option value="Asia/Jayapura">Asia/Jayapura (WIT)</option>
                         </select>
                     </div>
                 </div>

                 <div>
                     <label class="block text-sm font-semibold text-slate-600 mb-2">Format Tanggal</label>
                     <select class="w-full rounded-lg border-slate-200 focus:border-blue-500 text-slate-600 text-sm h-11">
                         <option value="DD/MM/YYYY">DD/MM/YYYY</option>
                         <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                         <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                     </select>
                 </div>

                 <!-- Toggle Switch Mode Pemeliharaan -->
                 <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-center justify-between mt-8">
                     <div>
                         <p class="text-sm font-bold text-slate-800">Mode Pemeliharaan</p>
                         <p class="text-xs text-slate-500 mt-1">Sistem tidak dapat diakses oleh pengguna biasa saat mode ini aktif.</p>
                     </div>
                     
                     <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                 </div>

                 <div class="pt-6 flex justify-end gap-3 border-t border-slate-100">
                     <button type="button" class="px-6 py-2.5 rounded-lg border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-colors">Batal</button>
                     <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2.5 rounded-lg font-bold shadow-md shadow-blue-200 transition-all">
                         Simpan Perubahan
                     </button>
                 </div>
             </form>
        </div>
    </div>
</div>
@endsection
