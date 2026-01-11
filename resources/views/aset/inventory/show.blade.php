@extends('aset.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-6xl mx-auto space-y-8">
    <!-- Breadcrumb & Back -->
    <div class="flex items-center gap-4">
        <a href="{{ route('aset.inventory.index') }}" class="p-2 bg-white rounded-full text-slate-500 hover:text-emerald-600 shadow-sm border border-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                <span>Inventaris</span>
                <span class="text-slate-300">/</span>
                <span class="font-medium text-emerald-600">{{ $aset->code }}</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-800">{{ $aset->name }}</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Info Column -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Asset Card -->
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100">
                <div class="relative h-64 md:h-80 bg-slate-100">
                    @if($aset->photo)
                        <img src="{{ asset('storage/' . $aset->photo) }}" class="w-full h-full object-cover" alt="{{ $aset->name }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300 flex-col gap-2">
                             <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                             <span class="text-sm font-medium">Tidak ada foto</span>
                        </div>
                    @endif
                    <div class="absolute top-4 right-4">
                         <span class="px-4 py-2 bg-white/95 backdrop-blur text-sm font-bold rounded-full shadow-lg {{ $aset->condition === 'Baik' ? 'text-emerald-600' : 'text-rose-500' }}">
                            {{ $aset->condition }}
                        </span>
                    </div>
                </div>
                
                <div class="p-8">
                    <h2 class="text-xl font-bold text-slate-800 mb-6">Informasi Detail</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Merek & Model</p>
                            <p class="font-semibold text-slate-700">{{ $aset->brand ?? '-' }} {{ $aset->model }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Kategori</p>
                            <p class="font-semibold text-slate-700">{{ $aset->category }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Lokasi Saat Ini</p>
                            <div class="flex items-center gap-2 text-slate-700">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="font-semibold">{{ $aset->location }}</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Harga Perolehan</p>
                            <p class="font-semibold text-slate-700">Rp {{ number_format($aset->price, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Pembelian</p>
                            <p class="font-semibold text-slate-700">{{ $aset->purchase_date ? $aset->purchase_date->translatedFormat('d F Y') : '-' }}</p>
                        </div>
                    </div>

                    @if($aset->notes)
                    <div class="mt-8 pt-6 border-t border-slate-50">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Catatan</p>
                        <p class="text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100">{{ $aset->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Maintenance History -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Riwayat Perbaikan</h3>
                    <button class="text-sm font-bold text-emerald-600 hover:text-emerald-700">Catat Perbaikan +</button>
                </div>

                <div class="space-y-6">
                    @forelse($aset->maintenances as $maintenance)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-2 h-2 rounded-full bg-slate-300 mt-2"></div>
                            <div class="w-0.5 flex-1 bg-slate-100 my-1"></div>
                        </div>
                        <div class="pb-6">
                            <p class="text-xs font-bold text-slate-500 mb-1">{{ $maintenance->scheduled_date->translatedFormat('d M Y') }}</p>
                            <p class="font-bold text-slate-800">{{ $maintenance->description }}</p>
                            <p class="text-sm text-slate-500">Vendor: {{ $maintenance->vendor ?? 'Internal' }} • Biaya: Rp {{ number_format($maintenance->cost, 0, ',', '.') }}</p>
                            <span class="inline-block mt-2 px-2 py-0.5 rounded text-xs font-bold bg-slate-100 text-slate-600">{{ $maintenance->status }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-slate-400">
                        <p>Belum ada riwayat perbaikan.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Action Buttons -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 space-y-3">
                <a href="{{ route('aset.inventory.edit', $aset->id) }}" class="block w-full text-center py-3 rounded-xl border border-slate-200 font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                    Edit Data Aset
                </a>
                <button onclick="if(confirm('Hapus aset ini?')) document.getElementById('delete-form-{{ $aset->id }}').submit()" class="block w-full text-center py-3 rounded-xl border border-rose-100 text-rose-600 font-bold hover:bg-rose-50 transition-colors">
                    Hapus Aset
                </button>
                <form id="delete-form-{{ $aset->id }}" action="{{ route('aset.inventory.destroy', $aset->id) }}" method="POST" class="hidden">
                    @csrf @method('DELETE')
                </form>
            </div>

            <!-- Mutation History Logs -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-800 mb-4">Riwayat Perpindahan</h3>
                <div class="space-y-4">
                    @forelse($aset->mutations as $mutation)
                    <div class="text-sm">
                        <div class="flex justify-between text-slate-500 mb-1">
                            <span>{{ $mutation->date->format('d/m/Y') }}</span>
                            <span class="{{ $mutation->type == 'Mutasi' ? 'text-amber-500' : 'text-blue-500' }} font-bold">{{ $mutation->type }}</span>
                        </div>
                        <p class="font-medium text-slate-800">{{ $mutation->origin_location }} &rarr; {{ $mutation->destination_location }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Oleh: {{ $mutation->person_in_charge }}</p>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400 italic text-center">Belum ada riwayat mutasi.</p>
                    @endforelse
                </div>
                <div class="mt-4 pt-4 border-t border-slate-50">
                    <button class="w-full py-2 bg-emerald-50 text-emerald-700 font-bold rounded-xl hover:bg-emerald-100 transition-colors text-sm">
                        Catat Mutasi / Pindah
                    </button>
                </div>
            </div>

            <!-- QR Code Placeholder -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 text-center">
                <div class="bg-white p-4 inline-block rounded-xl border border-slate-100 mb-2">
                     <!-- Real QR would generate here -->
                    <svg class="w-24 h-24 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 6h6v6H6V6zm13 0h-3v3h3V6zm-3 12h3v3h-3v-3zm-6 3v3H6v-3h4zm3-6v3h3v-3h-3z"></path></svg>
                </div>
                <p class="text-sm font-bold text-slate-800">{{ $aset->code }}</p>
                <button class="text-xs text-emerald-600 font-bold mt-2 hover:underline">Download QR Label</button>
            </div>
        </div>
    </div>
</div>
@endsection
