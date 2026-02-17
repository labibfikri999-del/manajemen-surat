@extends('sdm.layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">Buat Payroll Baru</h1>
        <a href="{{ route('sdm.payroll.index') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('sdm.payroll.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Periode -->
                <div>
                     <label for="month" class="block text-sm font-medium text-slate-700 mb-1">Bulan</label>
                    <select name="month" id="month" class="w-full form-select rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ date('m') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label for="year" class="block text-sm font-medium text-slate-700 mb-1">Tahun</label>
                    <input type="number" name="year" id="year" value="{{ date('Y') }}" class="w-full form-input rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <!-- Tanggal Pembayaran -->
            <div>
                 <label for="payment_date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Pembayaran</label>
                <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" class="w-full form-input rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>

            <!-- Pilih Karyawan -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Karyawan</label>
                <div class="border border-slate-200 rounded-lg max-h-60 overflow-y-auto divide-y divide-slate-100">
                    <div class="p-3 bg-slate-50 flex items-center justify-between sticky top-0 border-b border-slate-200">
                        <span class="text-xs font-bold text-slate-500 uppercase">Nama Karyawan</span>
                         <label class="inline-flex items-center">
                            <input type="checkbox" id="checkAll" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-xs text-slate-600">Pilih Semua</span>
                        </label>
                    </div>
                    @foreach($pegawais as $pegawai)
                    <div class="p-3 flex items-center hover:bg-slate-50 transition">
                         <input type="checkbox" name="pegawai_ids[]" value="{{ $pegawai->id }}" class="pegawai-checkbox rounded border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <div class="ml-3">
                            <div class="text-sm font-medium text-slate-800">{{ $pegawai->name }}</div>
                            <div class="text-xs text-slate-500">{{ $pegawai->nip }} - {{ $pegawai->position ?? 'Jabatan Tidak Diketahui' }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @error('pegawai_ids')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                 <a href="{{ route('sdm.payroll.index') }}" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 font-medium text-sm">Batal</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium text-sm shadow-sm transition">
                    Generate Payroll
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('checkAll').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('.pegawai-checkbox');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });
</script>
@endsection
