@extends('aset.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center bg-blue-500 p-6 rounded-2xl shadow-md text-white">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Scan QR Code Aset</h1>
            <p class="text-blue-100 text-sm mt-1">Pindai QR code untuk mengakses informasi aset</p>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-sm border border-blue-400 transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Hidupkan Kamera
        </button>
    </div>

    <!-- Instructions banner -->
    <div class="bg-white p-4 rounded-xl border border-blue-100 shadow-sm flex items-center gap-3">
        <div class="bg-blue-100 text-blue-600 p-1 rounded-full">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <p class="text-sm font-bold text-blue-800">Akses dengan scan qr code</p>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Scanner Area -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col h-[500px]">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Scanner Area</h2>
            <div class="flex-1 bg-slate-900 rounded-xl relative overflow-hidden flex items-center justify-center border-2 border-dashed border-slate-700">
                <!-- Camera Placeholder -->
                <div class="w-64 h-64 border-2 border-blue-500 rounded-3xl relative">
                    <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 border-blue-500 rounded-tl-xl"></div>
                    <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 border-blue-500 rounded-tr-xl"></div>
                    <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 border-blue-500 rounded-bl-xl"></div>
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 border-blue-500 rounded-br-xl"></div>
                    
                    <!-- Scan Line Animation -->
                    <div class="w-full h-1 bg-blue-400 absolute top-0 left-0 opacity-50 shadow-[0_0_10px_2px_#60a5fa] animate-[scan_2s_ease-in-out_infinite]"></div>
                </div>
            </div>
            <p class="text-center text-slate-500 font-medium mt-6">Arahkan kamera ke QR code aset</p>
        </div>

        <!-- Manual Input Area -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-6">Input Manual</h2>
            
            <form>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-600 mb-2">Kode Aset</label>
                    <input type="text" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400 text-sm" placeholder="Masukkan kode aset">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-bold transition-colors shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Cari Aset
                </button>
            </form>

            <div class="mt-8 border-t border-slate-100 pt-6">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Riwayat Scan</h3>
                <div class="text-center py-10 text-slate-400 text-sm">
                    Belum ada riwayat scan hari ini.
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes scan {
    0% { top: 0%; opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { top: 100%; opacity: 0; }
}
</style>
@endsection
