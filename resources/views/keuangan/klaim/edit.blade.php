@extends('keuangan.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h1 class="text-xl font-bold text-slate-800">Edit Klaim</h1>
        </div>
        
        <form action="{{ route('keuangan.klaim.update', $claim->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Provider Asuransi</label>
                    <input type="text" name="provider" value="{{ $claim->provider }}" required class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all font-bold text-slate-800">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Pengajuan</label>
                    <input type="date" name="submitted_at" value="{{ $claim->submitted_at }}" required class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nilai Klaim (Rp)</label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-slate-400 font-bold">Rp</span>
                    <input type="text" name="amount" value="{{ number_format($claim->amount, 0, '.', ',') }}" required class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all font-bold text-slate-800 text-lg" oninput="formatCurrency(this)">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Status Saat Ini</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all text-slate-600">
                    <option value="Verifikasi" {{ $claim->status == 'Verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                    <option value="Submitted" {{ $claim->status == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="Pending" {{ $claim->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Paid" {{ $claim->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Dokumen Pendukung (Kwitansi/Surat)</label>
                @if($claim->attachment)
                    <div class="mb-2 flex items-center gap-2">
                        <a href="{{ asset('storage/' . $claim->attachment) }}" target="_blank" class="text-xs font-bold text-blue-600 underline">Lihat File Saat Ini</a>
                    </div>
                @endif
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
                    Update Klaim
                </button>
            </div>
        </form>
        </form>
    </div>

    <!-- Timeline History -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mt-6">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-800">Riwayat Status Klaim</h3>
        </div>
        <div class="p-6">
            <ol class="relative border-l border-slate-200 ml-3">
                @forelse($claim->logs as $log)
                <li class="mb-10 ml-6">
                    <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white">
                        <svg class="w-3 h-3 text-blue-800" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                    </span>
                    <h3 class="flex items-center mb-1 text-lg font-semibold text-slate-900">{{ $log->status }}</h3>
                    <time class="block mb-2 text-sm font-normal leading-none text-slate-400">{{ $log->created_at->format('d M Y, H:i') }} - oleh {{ $log->user->name ?? 'System' }}</time>
                    <p class="mb-4 text-base font-normal text-slate-500">{{ $log->notes }}</p>
                </li>
                @empty
                <li class="ml-6 text-slate-500">Belum ada riwayat status.</li>
                @endforelse
            </ol>
        </div>
    </div>
</div>
@endsection
