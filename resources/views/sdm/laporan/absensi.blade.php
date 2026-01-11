@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 print:hidden">
        <div class="flex items-center gap-4">
            <a href="{{ route('sdm.laporan.index') }}" class="p-2.5 bg-white rounded-xl text-slate-500 hover:text-cyan-600 hover:bg-cyan-50 border border-slate-200 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Laporan Eksekutif Absensi</h1>
                <p class="text-slate-500">Analisis kinerja kehadiran pegawai.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="group bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold transition-all flex items-center gap-2 shadow-lg shadow-slate-200">
                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak PDF
            </button>
        </div>
    </div>

    <!-- Filter Stats -->
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 print:hidden">
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <!-- Filter Form -->
            <div class="w-full md:w-1/3 space-y-4">
                <h3 class="font-bold text-lg text-slate-800">Filter Periode</h3>
                <form action="{{ route('sdm.laporan.absensi') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Bulan</label>
                            <select name="month" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 bg-slate-50">
                                @for($i=1; $i<=12; $i++)
                                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tahun</label>
                            <select name="year" class="w-full rounded-xl border-slate-200 text-slate-700 focus:border-cyan-500 focus:ring-cyan-500 bg-slate-50">
                                @for($y=date('Y'); $y>=date('Y')-2; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-3 rounded-xl font-bold transition-all shadow-md shadow-cyan-100">
                        Terapkan Filter
                    </button>
                </form>
            </div>

            <!-- Charts -->
            <div class="w-full md:w-2/3 grid grid-cols-1 md:grid-cols-2 gap-6 border-l border-slate-100 pl-8">
                <div>
                     <canvas id="attendanceChart" height="200"></canvas>
                </div>
                <div class="flex flex-col justify-center space-y-4">
                    <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                        <span class="text-emerald-700 font-medium">Tingkat Kehadiran</span>
                        <span class="text-2xl font-bold text-emerald-800">
                            @php
                                $total = $attendanceData->sum('total');
                                $hadir = $attendanceData->sum('hadir');
                                echo $total > 0 ? round(($hadir / $total) * 100) . '%' : '0%';
                            @endphp
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-2xl border border-yellow-100">
                        <span class="text-yellow-700 font-medium">Keterlambatan</span>
                        <span class="text-2xl font-bold text-yellow-800">{{ $attendanceData->sum('telat') }} <span class="text-sm font-normal opacity-75">Kali</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Table -->
    <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100 print:shadow-none print:border-none print:rounded-none">
        <!-- Print Header -->
        <div class="hidden print:block p-8 border-b-2 border-slate-800">
            <div class="flex items-center gap-6">
                <img src="{{ asset('images/logo_rsi_ntb_new.png') }}" class="h-20 w-auto" alt="Logo">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 uppercase tracking-widest">Laporan Kehadiran</h1>
                    <p class="text-slate-600 text-lg">Rumah Sakit Islam Siti Hajar Mataram</p>
                    <p class="text-slate-500">Periode: {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white print:bg-slate-200 print:text-slate-900">
                        <th class="px-6 py-4 font-bold text-sm uppercase tracking-wider">Pegawai</th>
                        <th class="px-6 py-4 font-bold text-sm uppercase tracking-wider">Jabatan</th>
                        <th class="px-6 py-4 font-bold text-center text-sm uppercase tracking-wider">Hadir</th>
                        <th class="px-6 py-4 font-bold text-center text-sm uppercase tracking-wider">Telat</th>
                        <th class="px-6 py-4 font-bold text-center text-sm uppercase tracking-wider">Ijin</th>
                        <th class="px-6 py-4 font-bold text-center text-sm uppercase tracking-wider">Sakit</th>
                        <th class="px-6 py-4 font-bold text-center text-sm uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($attendanceData as $index => $data)
                    <tr class="hover:bg-slate-50 {{ $index % 2 == 0 ? 'bg-white' : 'bg-slate-50/50' }} transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800 border-r border-slate-50">{{ $data['name'] }}</td>
                        <td class="px-6 py-4 text-slate-600 text-sm border-r border-slate-50">{{ $data['role'] }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs">{{ $data['hadir'] }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-slate-600">{{ $data['telat'] > 0 ? $data['telat'] : '-' }}</td>
                        <td class="px-6 py-4 text-center text-slate-600">{{ $data['ijin'] > 0 ? $data['ijin'] : '-' }}</td>
                        <td class="px-6 py-4 text-center text-slate-600">{{ $data['sakit'] > 0 ? $data['sakit'] : '-' }}</td>
                         <td class="px-6 py-4 text-center font-bold text-slate-800 bg-slate-50/50">{{ $data['total'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500 italic">
                            Tidak ada data absensi yang tersedia untuk periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="hidden print:flex justify-between items-end p-12 mt-8">
            <div class="text-sm text-slate-500">
                <p>Dicetak Otomatis oleh Sistem SDM</p>
                <p>{{ now()->translatedFormat('l, d F Y H:i') }}</p>
            </div>
            <div class="text-center">
                <p class="mb-20 font-bold text-slate-800">Mengetahui, Kepala SDM</p>
                <div class="h-px w-64 bg-slate-800"></div>
                <p class="mt-2 font-bold text-slate-800">( ................................................. )</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Telat', 'Ijin', 'Sakit'],
                datasets: [{
                    data: [
                        {{ $attendanceData->sum('hadir') }},
                        {{ $attendanceData->sum('telat') }},
                        {{ $attendanceData->sum('ijin') }},
                        {{ $attendanceData->sum('sakit') }}
                    ],
                    backgroundColor: [
                        '#10b981', // Emerald 500
                        '#eab308', // Yellow 500
                        '#3b82f6', // Blue 500
                        '#ef4444'  // Red 500
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            font: { family: "'Space Grotesk', sans-serif" }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .bg-white, .shadow-sm, .rounded-3xl, .p-8 {
            background: white !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
        }
        .print\:hidden {
            display: none !important;
        }
        .print\:block {
            display: block !important;
        }
        .print\:flex {
            display: flex !important;
        }
        .print\:shadow-none {
            box-shadow: none !important;
        }
        .print\:border-none {
            border: none !important;
        }
        .print\:rounded-none {
            border-radius: 0 !important;
        }
        .print\:bg-slate-200 {
            background-color: #e2e8f0 !important;
            -webkit-print-color-adjust: exact;
        }
        .print\:text-slate-900 {
            color: #0f172a !important;
        }
    }
</style>
@endsection
