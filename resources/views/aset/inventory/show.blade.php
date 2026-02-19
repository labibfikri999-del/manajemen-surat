@extends('aset.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-6xl mx-auto space-y-8" x-data="{ tab: 'overview' }">
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
        <!-- Main Content Column -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Asset Card with Tabs -->
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100">
                <!-- Image Header -->
                <div class="relative h-64 md:h-80 bg-slate-100 group">
                    @if($aset->photo)
                        <img src="{{ asset('storage/' . $aset->photo) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $aset->name }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300 flex-col gap-2">
                             <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                             <span class="text-sm font-medium">Tidak ada foto</span>
                        </div>
                    @endif
                    
                    <!-- Overlay Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>

                    <div class="absolute bottom-6 left-6 text-white">
                        <span class="px-3 py-1 bg-white/20 backdrop-blur border border-white/30 text-xs font-bold rounded-full mb-2 inline-block">
                            {{ $aset->category }}
                        </span>
                        <h2 class="text-2xl font-bold drop-shadow-sm">{{ $aset->name }}</h2>
                    </div>

                    <div class="absolute top-4 right-4">
                         <span class="px-4 py-2 bg-white/95 backdrop-blur text-sm font-bold rounded-full shadow-lg {{ $aset->condition === 'Baik' ? 'text-emerald-600' : 'text-rose-500' }}">
                            {{ $aset->condition }}
                        </span>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <div class="flex border-b border-slate-100 overflow-x-auto">
                    <button @click="tab = 'overview'" :class="tab === 'overview' ? 'border-emerald-500 text-emerald-600 bg-emerald-50/50' : 'border-transparent text-slate-500 hover:text-slate-700'" class="flex-1 py-4 px-6 text-sm font-bold border-b-2 transition-all whitespace-nowrap">
                        Ringkasan
                    </button>
                    <button @click="tab = 'mutation'" :class="tab === 'mutation' ? 'border-blue-500 text-blue-600 bg-blue-50/50' : 'border-transparent text-slate-500 hover:text-slate-700'" class="flex-1 py-4 px-6 text-sm font-bold border-b-2 transition-all whitespace-nowrap">
                        Riwayat Mutasi
                    </button>
                    <button @click="tab = 'maintenance'" :class="tab === 'maintenance' ? 'border-orange-500 text-orange-600 bg-orange-50/50' : 'border-transparent text-slate-500 hover:text-slate-700'" class="flex-1 py-4 px-6 text-sm font-bold border-b-2 transition-all whitespace-nowrap">
                        Riwayat Maintenance
                    </button>
                </div>
                
                <!-- Tab Contents -->
                <div class="p-8 min-h-[400px]">
                    
                    <!-- OVERVIEW TAB -->
                    <div x-show="tab === 'overview'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        <h3 class="text-lg font-bold text-slate-800 mb-6">Spesifikasi & Detail</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Kode Aset</p>
                                <p class="font-mono font-medium text-slate-700 bg-slate-100 inline-block px-2 py-1 rounded">{{ $aset->code }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Merek & Model</p>
                                <p class="font-semibold text-slate-700 text-lg">{{ $aset->brand ?? '-' }} {{ $aset->model }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Lokasi Saat Ini</p>
                                <div class="flex items-center gap-2 text-slate-700">
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="font-bold text-lg">{{ $aset->location }}</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Harga Perolehan</p>
                                <p class="font-semibold text-slate-700 text-lg">Rp {{ number_format($aset->price, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Pembelian</p>
                                <p class="font-semibold text-slate-700">{{ $aset->purchase_date ? $aset->purchase_date->translatedFormat('d F Y') : '-' }}</p>
                            </div>
                        </div>

                        @if($aset->notes)
                        <div class="mt-8 pt-6 border-t border-slate-50">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Catatan Tambahan</p>
                            <div class="bg-amber-50 p-4 rounded-xl border border-amber-100 text-slate-700 leading-relaxed italic">
                                "{{ $aset->notes }}"
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- MUTATION TAB -->
                    <div x-show="tab === 'mutation'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-slate-800">Riwayat Perpindahan</h3>
                            <button class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-bold hover:bg-blue-100 transition-colors">
                                + Catat Mutasi
                            </button>
                        </div>
                        <div class="relative border-l-2 border-slate-100 ml-3 space-y-8">
                            @forelse($aset->mutations as $mutation)
                            <div class="relative pl-8">
                                <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-2 border-white {{ $mutation->type == 'Mutasi' ? 'bg-blue-500' : 'bg-purple-500' }} shadow-sm"></div>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-xs font-bold px-2 py-1 rounded {{ $mutation->type == 'Mutasi' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">{{ $mutation->type }}</span>
                                        <span class="text-xs font-mono text-slate-400">{{ $mutation->date->format('d M Y') }}</span>
                                    </div>
                                    <p class="text-slate-800 font-medium text-sm">
                                        <span class="text-slate-500">Dari</span> {{ $mutation->origin_location ?? '-' }} 
                                        <span class="text-slate-500">&rarr; Ke</span> {{ $mutation->destination_location }}
                                    </p>
                                    <p class="text-xs text-slate-500 mt-2">PJ: {{ $mutation->person_in_charge }}</p>
                                    @if($mutation->notes)
                                    <p class="text-xs text-slate-500 mt-1 italic">"{{ $mutation->notes }}"</p>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="pl-8 text-slate-400 italic">Belum ada riwayat mutasi.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- MAINTENANCE TAB -->
                    <div x-show="tab === 'maintenance'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-slate-800">Riwayat Perbaikan</h3>
                            <button class="px-4 py-2 bg-orange-50 text-orange-600 rounded-lg text-sm font-bold hover:bg-orange-100 transition-colors">
                                + Catat Perbaikan
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            @forelse($aset->maintenances as $maintenance)
                            <div class="flex items-start gap-4 p-4 rounded-xl border border-slate-100 hover:border-orange-200 transition-colors group">
                                <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <h4 class="font-bold text-slate-800">{{ $maintenance->description }}</h4>
                                        <span class="text-xs font-mono text-slate-400">{{ $maintenance->scheduled_date->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <p class="text-sm text-slate-500 mt-1">Vendor: {{ $maintenance->vendor ?? 'Internal' }}</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="text-xs font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600">{{ $maintenance->status }}</span>
                                        <span class="text-xs font-bold text-slate-400">Rp {{ number_format($maintenance->cost, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8 text-slate-400 italic">Belum ada riwayat maintenance.</div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
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

            <!-- QR Code Card -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 text-center relative overflow-hidden group">
                <h3 class="font-bold text-slate-800 mb-4">QR Code Label</h3>
                <div class="bg-white p-4 inline-block rounded-xl border border-slate-100 shadow-sm relative z-10">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $aset->code }}" alt="QR Code" class="w-32 h-32">
                </div>
                <p class="text-lg font-mono font-bold text-slate-800 mt-3 tracking-wider">{{ $aset->code }}</p>
                
                <!-- Print Button Overlay -->
                <div class="mt-4">
                    <button onclick="printQR('{{ $aset->code }}', '{{ $aset->name }}', '{{ asset('storage/' . $aset->photo) }}')" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center justify-center gap-1 mx-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Cetak Label
                    </button>
                </div>
                
                <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-emerald-50 rounded-full opacity-50 z-0"></div>
            </div>
            
            <!-- Quick Contacts / Info -->
            <div class="bg-blue-50/50 p-6 rounded-3xl border border-blue-100">
                <h3 class="font-bold text-blue-900 mb-2">Butuh Bantuan?</h3>
                <p class="text-xs text-blue-700 mb-4">Jika terdapat kendala dengan aset ini, segera hubungi tim IT atau Logistik.</p>
                <button class="w-full py-2 bg-white text-blue-600 rounded-xl text-sm font-bold shadow-sm hover:shadow-md transition-all">
                    Lapor Kerusakan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function printQR(code, name) {
    // Simple print window for QR
    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${code}`;
    const win = window.open('', '', 'width=600,height=600');
    win.document.write(`
        <html>
        <head>
            <style>
                body { font-family: sans-serif; text-align: center; padding: 40px; }
                .label { border: 2px solid #000; padding: 20px; display: inline-block; border-radius: 10px; }
                h2 { margin: 10px 0 5px; font-size: 24px; }
                p { margin: 0; font-size: 14px; color: #555; }
            </style>
        </head>
        <body>
            <div class="label">
                <img src="${qrUrl}" width="200">
                <h2>${code}</h2>
                <p>${name}</p>
                <p>Milik Perusahaan</p>
            </div>
            <script>window.print(); window.close();<\/script>
        </body>
        </html>
    `);
    win.document.close();
}
</script>
@endsection
