@extends('keuangan.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Ringkasan Keuangan</h1>
            <p class="text-slate-500">Laporan performa finansial bulan ini (Live Reporting).</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-xl font-medium text-sm shadow-sm hover:bg-slate-50 transition-colors">
                Download PDF
            </button>
            <button class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-amber-200 transition-all active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Catat Transaksi
            </button>
        </div>
    </div>

    <!-- Financial Highlights Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Pemasukan -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-50 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                    </div>
                    <span class="text-slate-500 text-sm font-medium">Pemasukan</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">{{ $stats['pemasukan'] }}</h2>
                <p class="text-xs text-emerald-600 mt-2 font-bold">+12.5% dari bulan lalu</p>
            </div>
        </div>

        <!-- Pengeluaran -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute right-0 top-0 w-32 h-32 bg-red-50 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                    </div>
                    <span class="text-slate-500 text-sm font-medium">Pengeluaran</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">{{ $stats['pengeluaran'] }}</h2>
                <p class="text-xs text-slate-400 mt-2">Operasional & Medis</p>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-3xl p-6 text-white shadow-lg shadow-amber-200 relative overflow-hidden group">
            <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-4 translate-y-4">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-amber-100 text-sm font-medium">Laba Bersih</span>
                </div>
                <h2 class="text-2xl font-bold">{{ $stats['laba'] }}</h2>
                <p class="text-xs text-amber-100 mt-2 opacity-90">Margin sehat bulan ini</p>
            </div>
        </div>

        <!-- Pending Claims (Piutang) -->
         <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute right-0 top-0 w-32 h-32 bg-blue-50 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <span class="text-slate-500 text-sm font-medium">Klaim Pending</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">{{ $stats['pending_claims'] }}</h2>
                <p class="text-xs text-blue-500 mt-2 font-semibold">Menunggu pencairan</p>
            </div>
        </div>
    </div>

    <!-- Charts & Widgets Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Cash Flow Chart (2/3 width) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Analisa Arus Kas</h3>
                    <p class="text-slate-500 text-sm">Pemasukan vs Pengeluaran (6 Bulan Terakhir)</p>
                </div>
                <select class="text-sm border-gray-200 rounded-lg text-slate-600 focus:ring-amber-500 focus:border-amber-500">
                    <option>Tahun Ini</option>
                    <option>Tahun Lalu</option>
                </select>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="cashFlowChart"></canvas>
            </div>
        </div>

        <!-- Budget & Claims (1/3 width) -->
        <div class="space-y-8">
            
            <!-- Insurance Claims Tracker -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Status Klaim Asuransi</h3>
                <div class="space-y-4">
                    @foreach($claims as $claim)
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                        <div>
                            <p class="font-bold text-slate-800 text-sm">{{ $claim->provider }}</p>
                            <p class="text-xs text-slate-400">{{ $claim->days }} hari yang lalu</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-slate-800 text-sm">{{ $claim->amount }}</p>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $claim->status == 'Verifikasi' ? 'bg-amber-100 text-amber-700' : ($claim->status == 'Submitted' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600') }}">
                                {{ $claim->status }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button class="w-full mt-4 py-2 text-sm text-center text-amber-600 font-bold hover:bg-amber-50 rounded-xl transition-colors">
                    Lihat Semua Klaim
                </button>
            </div>

            <!-- Departmental Budget -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Penggunaan Anggaran</h3>
                <div class="space-y-4">
                    @foreach($budgets as $budget)
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-bold text-slate-700">{{ $budget->dept }}</span>
                            <span class="{{ $budget->used > 80 ? 'text-red-500' : 'text-slate-500' }} font-medium">{{ $budget->used }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="h-2 rounded-full {{ $budget->used > 80 ? 'bg-red-500' : 'bg-emerald-500' }}" style="width: {{ $budget->used }}%"></div>
                        </div>
                        <p class="text-[10px] text-slate-400 text-right mt-1">Limit: {{ $budget->limit }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('cashFlowChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: [1200, 1900, 300, 500, 200, 3000], // Mock Data (scaled down)
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                    },
                    {
                        label: 'Pengeluaran',
                        data: [1000, 1300, 250, 400, 150, 2100], // Mock Data
                        backgroundColor: '#f59e0b',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
