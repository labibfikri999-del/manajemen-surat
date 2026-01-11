@extends('aset.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-2xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('aset.mutation.index') }}" class="p-2 bg-white rounded-full text-slate-500 hover:text-emerald-600 shadow-sm border border-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Catat Mutasi / Peminjaman</h1>
            <p class="text-slate-500">Pindahkan aset atau catat peminjaman.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
        <form action="{{ route('aset.mutation.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Aset</label>
                <select name="aset_id" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" required>
                    <option value="" disabled selected>-- Pilih Aset --</option>
                    @foreach($asets as $aset)
                        <option value="{{ $aset->id }}">{{ $aset->code }} - {{ $aset->name }} ({{ $aset->location }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-6">
                 <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Jenis Aktivitas</label>
                    <select name="type" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" required>
                        <option value="Mutasi">Mutasi (Pindah Tetap)</option>
                        <option value="Peminjaman">Peminjaman (Sementara)</option>
                        <option value="Pengembalian">Pengembalian</option>
                    </select>
                </div>
                <div>
                     <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal</label>
                     <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Lokasi Tujuan / Peminjam</label>
                <input type="text" name="destination_location" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Contoh: Ruang Rapat Lt. 1" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Penanggung Jawab (PIC)</label>
                <input type="text" name="person_in_charge" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Nama Staff / Peminjam" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Catatan (Opsional)</label>
                <textarea name="notes" rows="3" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Alasan perpindahan atau keterangan lain..."></textarea>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-emerald-200 transition-all active:scale-95">
                    Simpan Mutasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
