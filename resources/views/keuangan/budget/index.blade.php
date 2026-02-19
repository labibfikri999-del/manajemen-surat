@extends('keuangan.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Kelola Anggaran</h1>
            <p class="text-slate-500">Atur batas anggaran untuk setiap departemen.</p>
        </div>
        <a href="{{ route('keuangan.dashboard') }}" class="text-slate-500 hover:text-slate-700 font-medium">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($budgets as $budget)
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-amber-50 rounded-full -mr-12 -mt-12 opacity-50 transition-transform group-hover:scale-110"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-slate-800">{{ $budget->department }}</h3>
                </div>

                <form action="{{ route('keuangan.budget.update', $budget->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <label class="block text-xs font-bold text-slate-400 mb-1">Batas Anggaran</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-slate-400 font-bold text-sm">Rp</span>
                        <input type="text" 
                               name="limit_amount" 
                               value="{{ number_format($budget->limit_amount, 0, ',', ',') }}" 
                               class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 font-bold focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all"
                               oninput="formatCurrency(this)">
                    </div>
                    
                    <button type="submit" class="mt-4 w-full py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-bold transition-colors shadow-lg shadow-amber-200 active:scale-95">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    function formatCurrency(input) {
        let value = input.value.replace(/\D/g, '');
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        input.value = value;
    }
</script>
@endsection
