@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('sdm.dashboard') }}" class="p-2 bg-white rounded-full text-slate-500 hover:text-cyan-600 shadow-sm border border-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Slip Gaji</h1>
            <p class="text-slate-500">Fitur ini sedang dalam pengembangan.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-12 shadow-sm border border-slate-100 text-center">
        <div class="w-24 h-24 bg-cyan-50 rounded-full flex items-center justify-center mx-auto mb-6 text-cyan-600">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path></svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 mb-2">Coming Soon</h2>
        <p class="text-slate-500 max-w-md mx-auto">Modul penggajian akan segera hadir dengan fitur perhitungan otomatis, pajak, dan tunjangan.</p>
    </div>
</div>
@endsection
