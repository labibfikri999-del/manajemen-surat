@extends('keuangan.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8" x-data="{ previewOpen: false, previewUrl: '', previewType: '' }">
    
    <!-- Dynamic Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Data {{ $title }}</h1>
            <p class="text-slate-500">Rekapitulasi transaksi {{ strtolower($title) }} terkini.</p>
        </div>
        <a href="{{ route('keuangan.transaksi.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-amber-200 transition-all active:scale-95 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah {{ $title }}
        </a>
    </div>

    <!-- Stats Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative overflow-hidden">
        <div class="relative z-10 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl {{ $type == 'pemasukan' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }} flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($type == 'pemasukan')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                    @endif
                </svg>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Total {{ $title }} Bulan Ini</p>
                <h2 class="text-2xl font-bold text-slate-800">Rp {{ number_format($total, 0, ',', '.') }}</h2>
            </div>
        </div>
        <!-- Decoration -->
        <div class="absolute right-0 top-0 w-32 h-32 {{ $type == 'pemasukan' ? 'bg-emerald-50' : 'bg-red-50' }} rounded-full -mr-16 -mt-16 opacity-50"></div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500">
                        <th class="p-6 font-bold">Tanggal</th>
                        <th class="p-6 font-bold">Kategori</th>
                        <th class="p-6 font-bold">Keterangan</th>
                        <th class="p-6 font-bold text-right">Jumlah</th>
                        <th class="p-6 font-bold text-center">Bukti</th>
                        <th class="p-6 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $t)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="p-6 text-slate-600 font-medium whitespace-nowrap">
                            {{ $t->transaction_date->format('d M Y') }}
                        </td>
                        <td class="p-6">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $type == 'pemasukan' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                {{ $t->category }}
                            </span>
                        </td>
                        <td class="p-6 text-slate-500 text-sm max-w-xs truncate">
                            {{ $t->description ?? '-' }}
                        </td>
                        <td class="p-6 text-right font-bold {{ $type == 'pemasukan' ? 'text-emerald-600' : 'text-slate-800' }}">
                            Rp {{ number_format($t->amount, 0, ',', '.') }}
                        </td>
                        <td class="p-6 text-center">
                            @if($t->attachment)
                                <button @click="previewUrl = '{{ asset('storage/' . $t->attachment) }}'; previewType = '{{ Str::endsWith($t->attachment, '.pdf') ? 'pdf' : 'image' }}'; previewOpen = true" class="text-amber-600 hover:text-amber-700 font-medium text-xs underline cursor-pointer">
                                    Lihat File
                                </button>
                            @else
                                <span class="text-slate-300 text-xs">-</span>
                            @endif
                        </td>
                        <td class="p-6 text-center">
                            <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('keuangan.transaksi.edit', $t->id) }}" class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-amber-50 hover:text-amber-600 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('keuangan.transaksi.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-red-50 hover:text-red-500 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-slate-400">
                            <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <p class="text-sm font-medium">Belum ada data transaksi {{ $title }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-6 border-t border-slate-50">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- Preview Modal -->
    <div x-show="previewOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background Overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="previewOpen = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Preview Dokumen</h3>
                        <button @click="previewOpen = false" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="flex justify-center bg-slate-50 rounded-lg p-2 min-h-[300px]">
                        <template x-if="previewType === 'image'">
                            <img :src="previewUrl" class="max-w-full max-h-[70vh] rounded shadow-sm object-contain">
                        </template>
                        <template x-if="previewType === 'pdf'">
                            <iframe :src="previewUrl" class="w-full h-[70vh] rounded shadow-sm" frameborder="0"></iframe>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
