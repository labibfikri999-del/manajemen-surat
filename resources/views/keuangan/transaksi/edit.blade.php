@extends('keuangan.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Edit Transaksi</h1>
                <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG, PDF, DOC, XLSX, ZIP. Max: 10MB</p>
            </div>
            <a href="{{ route('keuangan.dashboard') }}" class="text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
        </div>
        
        <form action="{{ route('keuangan.transaksi.update', $transaction->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Type Selection -->
            <div class="grid grid-cols-2 gap-4">
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="pemasukan" class="peer sr-only" {{ $transaction->type == 'pemasukan' ? 'checked' : '' }}>
                    <div class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 flex flex-col items-center justify-center gap-2 hover:bg-slate-50 transition-all">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                        </div>
                        <span class="font-bold text-slate-700 peer-checked:text-emerald-700">Pemasukan</span>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="pengeluaran" class="peer sr-only" {{ $transaction->type == 'pengeluaran' ? 'checked' : '' }}>
                    <div class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-red-500 peer-checked:bg-red-50 flex flex-col items-center justify-center gap-2 hover:bg-slate-50 transition-all">
                        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                        </div>
                        <span class="font-bold text-slate-700 peer-checked:text-red-700">Pengeluaran</span>
                    </div>
                </label>
            </div>

            <!-- Amount & Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-slate-400 font-bold">Rp</span>
                        <input type="text" 
                               name="amount" 
                               required 
                               value="{{ number_format($transaction->amount, 0, '.', ',') }}"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-amber-500 focus:ring focus:ring-amber-200 transition-all font-bold text-slate-800 text-lg" 
                               placeholder="0"
                               oninput="formatCurrency(this)">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Transaksi</label>
                    <input type="date" name="transaction_date" required value="{{ $transaction->transaction_date->format('Y-m-d') }}" class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-amber-500 focus:ring focus:ring-amber-200 transition-all">
                </div>
            </div>

             <script>
                function formatCurrency(input) {
                    let value = input.value.replace(/\D/g, '');
                    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    input.value = value;
                }
            </script>

            <!-- Category & Description -->
             <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                <select name="category" class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-amber-500 focus:ring focus:ring-amber-200 transition-all text-slate-600">
                    <option value="{{ $transaction->category }}" selected>{{ $transaction->category }}</option>
                    <option disabled>──────────</option>
                    <option value="Rawat Inap">Rawat Inap</option>
                    <option value="Rawat Jalan">Rawat Jalan</option>
                    <option value="Obat & Farmasi">Obat & Farmasi</option>
                    <option value="Laboratorium">Laboratorium</option>
                    <option value="Gaji & Honor">Gaji & Honor</option>
                    <option value="Operasional (Listrik/Air)">Operasional (Listrik/Air)</option>
                    <option value="Pemeliharaan Gedung">Pemeliharaan Gedung</option>
                    <option value="Peralatan Medis">Peralatan Medis</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

             <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Keterangan (Opsional)</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-amber-500 focus:ring focus:ring-amber-200 transition-all">{{ $transaction->description }}</textarea>
            </div>

            <!-- Attachment -->
             <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Bukti Transaksi (Struk/Invoice)</label>
                @if($transaction->attachment)
                    <div class="mb-2 text-xs text-amber-600">File saat ini: <a href="{{ asset('storage/' . $transaction->attachment) }}" target="_blank" class="underline">Lihat Bukti</a></div>
                @endif
                <input type="file" name="attachment" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-all">
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('keuangan.dashboard') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-amber-200 transition-all active:scale-95">
                    Update Transaksi
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
