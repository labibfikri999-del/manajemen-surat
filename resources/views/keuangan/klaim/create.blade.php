@extends('keuangan.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h1 class="text-xl font-bold text-slate-800">Buat Klaim Baru</h1>
        </div>
        
        <form action="{{ route('keuangan.klaim.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Provider Asuransi</label>
                    <input type="text" name="provider" required class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all font-bold text-slate-800" placeholder="Contoh: BPJS Kesehatan">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Pengajuan</label>
                    <input type="date" name="submitted_at" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nilai Klaim (Rp)</label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-slate-400 font-bold">Rp</span>
                    <input type="text" name="amount" required class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all font-bold text-slate-800 text-lg" placeholder="0" oninput="formatCurrency(this)">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Status Saat Ini</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all text-slate-600">
                    <option value="Verifikasi">Verifikasi (Sedang dicek internal)</option>
                    <option value="Submitted">Submitted (Dikirim ke pihak asuransi)</option>
                    <option value="Pending">Pending (Menunggu pembayaran)</option>
                    <option value="Paid">Paid (Sudah dibayar)</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Dokumen Pendukung (Kwitansi/Surat)</label>
                <input type="file" name="attachment" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
                <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG, PDF, DOC, XLSX, ZIP. Max: 10MB</p>
            </div>

            <script>
                function formatCurrency(input) {
                    let value = input.value.replace(/\D/g, '');
                    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    input.value = value;
                }
            </script>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('keuangan.klaim.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-200 transition-all active:scale-95">
                    Simpan Klaim
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
