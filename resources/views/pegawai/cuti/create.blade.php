@extends('pegawai.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-3xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('pegawai.cuti.index') }}" class="p-3 bg-white rounded-xl shadow-sm border border-slate-100 text-slate-500 hover:text-pink-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Form Pengajuan Cuti</h1>
            <p class="text-slate-500">Silakan lengkapi formulir di bawah ini.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-8 shadow-xl shadow-pink-50 border border-pink-50">
        <form action="{{ route('pegawai.cuti.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Jenis Cuti</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="Tahunan" class="peer sr-only" checked>
                        <div class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-pink-500 peer-checked:bg-pink-50 transition-all text-center">
                            <span class="block font-bold text-slate-700 peer-checked:text-pink-700">Tahunan</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="Sakit" class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-pink-500 peer-checked:bg-pink-50 transition-all text-center">
                            <span class="block font-bold text-slate-700 peer-checked:text-pink-700">Sakit</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="Melahirkan" class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-pink-500 peer-checked:bg-pink-50 transition-all text-center">
                            <span class="block font-bold text-slate-700 peer-checked:text-pink-700">Melahirkan</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="Lainnya" class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-pink-500 peer-checked:bg-pink-50 transition-all text-center">
                            <span class="block font-bold text-slate-700 peer-checked:text-pink-700">Lainnya</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" required class="w-full rounded-xl border-slate-200 focus:border-pink-500 focus:ring-pink-500 py-3">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Selesai</label>
                    <input type="date" name="end_date" required class="w-full rounded-xl border-slate-200 focus:border-pink-500 focus:ring-pink-500 py-3">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Alasan Pengajuan</label>
                <textarea name="reason" rows="4" required class="w-full rounded-xl border-slate-200 focus:border-pink-500 focus:ring-pink-500 p-4" placeholder="Jelaskan alasan pengajuan cuti Anda..."></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-pink-200 transition-all transform hover:-translate-y-1">
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
