@extends('aset.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-2xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('aset.maintenance.index') }}" class="p-2 bg-white rounded-full text-slate-500 hover:text-emerald-600 shadow-sm border border-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Buat Jadwal Maintenance</h1>
            <p class="text-slate-500">Jadwalkan perbaikan atau servis aset rutin.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
        <form action="{{ route('aset.maintenance.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Aset</label>
                <select name="aset_id" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" required>
                    <option value="" disabled selected>-- Pilih Aset --</option>
                    @foreach($asets as $aset)
                        <option value="{{ $aset->id }}">{{ $aset->code }} - {{ $aset->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                 <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Masalah / Pekerjaan</label>
                 <textarea name="description" rows="3" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Contoh: Ganti Baterai UPS, Servis AC Berkala..." required></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <div>
                     <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Jadwal</label>
                     <input type="date" name="scheduled_date" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" required>
                </div>
                <div>
                     <label class="block text-sm font-bold text-slate-700 mb-2">Status Awal</label>
                     <select name="status" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="Scheduled" selected>Scheduled (Terjadwal)</option>
                        <option value="In Progress">In Progress (Sedang Dikerjakan)</option>
                        <option value="Completed">Completed (Selesai)</option>
                     </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Estimasi Biaya (Rp)</label>
                    <input type="number" name="cost" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" placeholder="0">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Vendor / Teknisi</label>
                    <input type="text" name="vendor" class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Contoh: CV. Komputer Jaya">
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-emerald-200 transition-all active:scale-95">
                    Buat Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
