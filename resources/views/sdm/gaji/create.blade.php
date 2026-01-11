@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-4xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('sdm.gaji.index') }}" class="p-2 bg-white rounded-full text-slate-500 hover:text-cyan-600 shadow-sm border border-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Buat Slip Gaji Barus</h1>
            <p class="text-slate-500">Hitung dan buat slip gaji untuk pegawai.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
        @if(session('error'))
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('sdm.gaji.store') }}" method="POST" class="space-y-6" x-data="salaryCalcs()">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Periode -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Bulan</label>
                    <select name="month" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tahun</label>
                    <input type="number" name="year" value="{{ date('Y') }}" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                </div>
            </div>

            <!-- Pegawai Selection -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Pegawai</label>
                <select name="sdm_pegawai_id" @change="fetchSalaryData($event.target.value)" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                    <option value="" disabled selected>-- Pilih Pegawai --</option>
                    @foreach($pegawais as $pegawai)
                        <option value="{{ $pegawai->id }}">{{ $pegawai->name }} - {{ $pegawai->role }}</option>
                    @endforeach
                </select>
            </div>

            <div class="border-t border-slate-100 pt-6">
                <h3 class="font-bold text-lg text-slate-800 mb-4">Rincian Gaji</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Gaji Pokok</label>
                        <input type="number" name="basic_salary" x-model="basic_salary" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tunjangan</label>
                        <input type="number" name="allowances" x-model="allowances" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Potongan</label>
                        <input type="number" name="deductions" x-model="deductions" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500" required>
                    </div>
                </div>

                <div class="mt-6 bg-cyan-50 p-4 rounded-xl flex justify-between items-center">
                    <span class="font-bold text-cyan-800">Total Gaji Bersih (Net)</span>
                    <span class="font-bold text-2xl text-cyan-700">Rp <span x-text="formatRupiah(calculateNet())"></span></span>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-cyan-200 transition-all active:scale-95">
                    Buat Slip Gaji
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function salaryCalcs() {
        return {
            basic_salary: 0,
            allowances: 0,
            deductions: 0,
            
            async fetchSalaryData(pegawaiId) {
                if(!pegawaiId) return;
                try {
                    const response = await fetch(`/sdm/api/pegawai/${pegawaiId}/salary`);
                    const data = await response.json();
                    this.basic_salary = parseFloat(data.gaji_pokok) || 0;
                    this.allowances = parseFloat(data.tunjangan) || 0;
                    this.deductions = 0; // Reset deductions
                } catch(e) {
                    console.error('Error fetching data', e);
                }
            },
            
            calculateNet() {
                return (parseFloat(this.basic_salary || 0) + parseFloat(this.allowances || 0)) - parseFloat(this.deductions || 0);
            },
            
            formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID').format(angka);
            }
        }
    }
</script>
@endsection
