@extends('keuangan.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Catatan Laporan Keuangan</h1>
            <p class="text-slate-500">Penjelasan detail dan pengungkapan informasi material.</p>
        </div>
        <button class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-amber-200 transition-all active:scale-95 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Catatan
        </button>
    </div>

    <!-- Notes Breakdown -->
    <div class="grid grid-cols-1 gap-6">
        @foreach($catatan as $index => $note)
        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
            <!-- Decorative Number -->
            <div class="absolute -right-4 -top-8 text-slate-50 opacity-10 font-bold text-[120px] select-none">
                {{ $index + 1 }}
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-4">
                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wide">
                        Catatan #{{ $index + 1 }}
                    </span>
                    <span class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $note['date'] }}
                    </span>
                </div>
                
                <h2 class="text-xl font-bold text-slate-800 mb-3">{{ $note['title'] }}</h2>
                <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                    <p>{{ $note['content'] }}</p>
                </div>

                <div class="mt-6 flex items-center gap-4 pt-6 border-t border-slate-50">
                    <button class="text-slate-400 hover:text-amber-600 text-sm font-medium transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Edit
                    </button>
                     <button class="text-slate-400 hover:text-red-500 text-sm font-medium transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Hapus
                    </button>
                    <!-- User Avatar (Mock) -->
                    <div class="ml-auto flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] text-slate-600 font-bold">FE</div>
                        <span class="text-xs text-slate-400">Finance Exm.</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
