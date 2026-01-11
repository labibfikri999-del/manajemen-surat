@extends('pegawai.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    
    <!-- Alerts -->
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    {{ session('error') }}
                </p>
            </div>
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-emerald-700">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Halo, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-slate-500">Selamat bekerja, semoga harimu menyenangkan.</p>
        </div>
        <div class="flex items-center gap-3">
             <span class="px-4 py-2 bg-white rounded-full text-sm font-medium text-slate-600 shadow-sm border border-slate-100">
                📅 {{ now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    <!-- Top Widgets Configuration -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Attendance Card (Main Focus) -->
        <div class="lg:col-span-1 bg-gradient-to-br from-fuchsia-600 to-purple-700 rounded-3xl p-6 text-white shadow-xl shadow-fuchsia-200 relative overflow-hidden flex flex-col justify-between h-64">
            <div class="absolute right-0 top-0 opacity-10 transform translate-x-12 -translate-y-8">
                <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            
            <div class="relative z-10">
                <h3 class="text-fuchsia-100 font-medium mb-1">Status Absensi</h3>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full {{ $attendanceData['status'] !== 'Absen' ? 'bg-emerald-400 animate-pulse' : 'bg-slate-400' }}"></span>
                    <h2 class="text-3xl font-bold">{{ $attendanceData['status'] }}</h2>
                </div>
            </div>

            <div class="relative z-10 space-y-4">
                <div class="flex justify-between items-end border-b border-white/20 pb-4">
                    <div>
                        <p class="text-xs text-fuchsia-200 uppercase tracking-widest mb-1">Check In</p>
                        <p class="text-2xl font-bold font-mono">{{ $attendanceData['check_in'] }}</p>
                    </div>
                    <div>
                         @if($attendanceData['check_out'] !== '-')
                            <p class="text-xs text-fuchsia-200 uppercase tracking-widest mb-1 text-right">Check Out</p>
                            <p class="text-xl font-bold font-mono">{{ $attendanceData['check_out'] }}</p>
                        @else
                            <p class="text-xs text-fuchsia-200 uppercase tracking-widest mb-1 text-right">Durasi</p>
                            <p class="text-xl font-bold font-mono">{{ $attendanceData['working_hours'] }}</p>
                        @endif
                    </div>
                </div>
                
                @if($attendanceData['can_checkout'])
                    <form action="{{ route('pegawai.attendance.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-white text-fuchsia-700 font-bold rounded-xl shadow-lg hover:bg-fuchsia-50 active:scale-95 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Check Out
                        </button>
                    </form>
                @else
                    <button disabled class="w-full py-3 bg-white/20 text-white/50 font-bold rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $attendanceData['status'] === 'Absen' ? 'Belum Ada Jadwal' : 'Selesai' }}
                    </button>
                @endif
            </div>
        </div>

        <!-- Leave Balance & Quick Actions -->
        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Leave Quota -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between h-64">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-pink-100 flex items-center justify-center text-pink-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-slate-500 text-sm font-medium">Sisa Cuti Tahunan</span>
                    </div>
                </div>
                
                <div class="text-center my-4">
                    <span class="text-5xl font-bold text-slate-800">{{ $leave['annual']['remaining'] }}</span>
                    <span class="text-slate-400 text-sm">/ {{ $leave['annual']['total'] }} Hari</span>
                </div>

                <div class="w-full bg-slate-100 rounded-full h-3 mb-4">
                    <div class="bg-pink-500 h-3 rounded-full" style="width: {{ ($leave['annual']['used'] / $leave['annual']['total']) * 100 }}%"></div>
                </div>

                <button class="w-full py-2.5 text-pink-600 font-bold bg-pink-50 rounded-xl hover:bg-pink-100 transition-colors text-sm">
                    Ajukan Cuti Baru
                </button>
            </div>

            <!-- Payslip Preview -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between h-64">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <span class="text-slate-500 text-sm font-medium">Slip Gaji Terakhir</span>
                    </div>
                </div>

                 @if(count($payslips) > 0)
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                        <p class="text-xs text-slate-400 mb-1">Periode</p>
                        <p class="text-slate-700 font-bold">{{ $payslips[0]->month }}</p>
                        
                        <div class="border-t border-slate-200 my-2"></div>
                        
                        <div class="flex justify-between items-center">
                            <p class="text-lg font-bold text-purple-700 max-w-[60%] truncate">{{ $payslips[0]->amount }}</p>
                            <span class="text-[10px] font-bold bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full">{{ $payslips[0]->status }}</span>
                        </div>
                    </div>
                @endif

                <button class="w-full py-2.5 text-purple-600 font-bold bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors text-sm mt-auto">
                    Lihat Semua Slip
                </button>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Announcements & History -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Company Announcements -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                Pengumuman Internal
            </h3>
            
            <div class="space-y-4">
                @foreach($announcements as $info)
                <div class="group cursor-pointer">
                    <div class="flex justify-between items-center mb-1">
                         <h4 class="font-bold text-slate-700 group-hover:text-fuchsia-600 transition-colors">{{ $info->title }}</h4>
                         <span class="text-xs text-slate-400">{{ $info->date }}</span>
                    </div>
                    <p class="text-sm text-slate-500 line-clamp-2">{{ $info->content }}</p>
                    <div class="border-b border-slate-100 mt-3"></div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Payslip History List -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Riwayat Gaji</h3>
            <div class="space-y-3">
                 @foreach($payslips as $slip)
                 <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                     <div class="flex items-center gap-3">
                         <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                         </div>
                         <div>
                             <p class="font-bold text-slate-700 text-sm">{{ $slip->month }}</p>
                             <p class="text-xs text-slate-400">Diterima: {{ $slip->date }}</p>
                         </div>
                     </div>
                     <span class="font-bold text-slate-700">{{ $slip->amount }}</span>
                 </div>
                 @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
