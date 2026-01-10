@extends('keuangan.layouts.app')

@extends('keuangan.layouts.app')

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8" x-data="{ 
    modalOpen: false, 
    editMode: false, 
    noteId: null, 
    title: '', 
    content: '', 
    date: new Date().toISOString().split('T')[0],
    
    openAddModal() {
        this.editMode = false;
        this.title = '';
        this.content = '';
        this.date = new Date().toISOString().split('T')[0];
        this.modalOpen = true;
    },
    
    openEditModal(note) {
        this.editMode = true;
        this.noteId = note.id;
        this.title = note.title;
        this.content = note.content;
        this.date = note.date;
        this.modalOpen = true;
    }
}">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Catatan Laporan Keuangan</h1>
            <p class="text-slate-500">Penjelasan detail dan pengungkapan informasi material.</p>
        </div>
        <button @click="openAddModal()" class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-amber-200 transition-all active:scale-95 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Catatan
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2" role="alert">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Notes Breakdown -->
    <div class="grid grid-cols-1 gap-6">
        @forelse($catatan as $index => $note)
        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
            <!-- Decorative Number -->
            <div class="absolute -right-4 -top-8 text-slate-50 opacity-10 font-bold text-[120px] select-none group-hover:text-amber-50 group-hover:opacity-20 transition-all">
                {{ $loop->iteration }}
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-4">
                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wide">
                        Catatan #{{ $loop->iteration }}
                    </span>
                    <span class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ \Carbon\Carbon::parse($note->date)->isoFormat('D MMM Y') }}
                    </span>
                </div>
                
                <h2 class="text-xl font-bold text-slate-800 mb-3">{{ $note->title }}</h2>
                <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                    <p>{{ $note->content }}</p>
                </div>

                <div class="mt-6 flex items-center gap-4 pt-6 border-t border-slate-50">
                    <button @click="openEditModal({{ json_encode($note) }})" class="text-slate-400 hover:text-amber-600 text-sm font-medium transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Edit
                    </button>
                    
                    <form action="{{ route('keuangan.catatan.destroy', $note->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-slate-400 hover:text-red-500 text-sm font-medium transition-colors flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Hapus
                        </button>
                    </form>

                    <!-- User Avatar -->
                    <div class="ml-auto flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] text-slate-600 font-bold">
                            {{ substr($note->user->name ?? 'U', 0, 1) }}
                        </div>
                        <span class="text-xs text-slate-400">{{ $note->user->name ?? 'Unknown' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 bg-white rounded-3xl border border-slate-100 border-dashed">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <p class="text-slate-500 font-medium">Belum ada catatan keuangan.</p>
            <p class="text-sm text-slate-400 mt-1">Tambahkan catatan baru untuk melengkapi laporan.</p>
        </div>
        @endforelse
    </div>

    <!-- Modal Form -->
    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-bold text-slate-900" id="modal-title">
                        <span x-text="editMode ? 'Edit Catatan' : 'Tambah Catatan Baru'"></span>
                    </h3>
                    
                    <form id="noteForm" method="POST" :action="editMode ? '{{ route('keuangan.catatan.update', '__id__') }}'.replace('__id__', noteId) : '{{ route('keuangan.catatan.store') }}'" class="mt-4 space-y-4">
                        @csrf
                        <template x-if="editMode">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div>
                            <label for="date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                            <input type="date" name="date" id="date" x-model="date" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm">
                        </div>

                        <div>
                            <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Judul Catatan</label>
                            <input type="text" name="title" id="title" x-model="title" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm" placeholder="Contoh: Kebijakan Akuntansi Baru">
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-slate-700 mb-1">Isi Catatan</label>
                            <textarea name="content" id="content" x-model="content" rows="4" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm" placeholder="Tulis detail catatan disini..."></textarea>
                        </div>
                    </form>
                </div>

                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="document.getElementById('noteForm').submit()" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-amber-600 text-base font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button type="button" @click="modalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
