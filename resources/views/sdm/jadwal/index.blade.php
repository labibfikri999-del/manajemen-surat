@extends('sdm.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Jadwal Shift</h1>
            <p class="text-slate-500">Monitoring jadwal jaga pegawai.</p>
        </div>
        <div class="flex items-center gap-3">
             <a href="{{ route('sdm.dashboard') }}" class="p-2 bg-white rounded-full text-slate-500 hover:text-cyan-600 shadow-sm border border-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-800 uppercase font-bold text-xs">
                    <tr>
                        <th class="px-6 py-4 rounded-tl-2xl">Tanggal</th>
                        <th class="px-6 py-4">Pegawai</th>
                        <th class="px-6 py-4">Shift</th>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4 rounded-tr-2xl">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($shifts as $shift)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-700">{{ \Carbon\Carbon::parse($shift->date)->isoFormat('dddd, D MMM Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="block font-bold text-slate-800">{{ $shift->pegawai->name ?? '-' }}</span>
                            <span class="text-xs text-slate-500">{{ $shift->pegawai->role ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2 py-1 rounded-lg text-xs font-bold {{ $shift->shift_name == 'Pagi' ? 'bg-orange-100 text-orange-700' : ($shift->shift_name == 'Siang' ? 'bg-sky-100 text-sky-700' : 'bg-indigo-100 text-indigo-700') }}">
                                {{ $shift->shift_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono">{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</td>
                        <td class="px-6 py-4">
                             <span class="inline-block px-2 py-1 rounded-full text-xs font-bold {{ $shift->status == 'On Duty' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $shift->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 px-6">
            {{ $shifts->links() }}
        </div>
    </div>
</div>
@endsection
