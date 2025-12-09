@php $user = auth()->user(); $role = $user->role ?? 'guest'; @endphp
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Buat Surat Otomatis — YARSI NTB</title>
  <link rel="icon" type="image/png" href="{{ asset('images/Logo Yayasan Bersih.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @include('partials.styles')
</head>
<body class="bg-gray-50">
  <div id="app" class="flex flex-col">
    @include('partials.header')

    @include('partials.sidebar-menu')

    <main id="main" class="transition-smooth main-with-sidebar flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
        
        <div class="space-y-6 animate-fade-in-up max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between pb-6 border-b border-gray-200">
                <div>
                    <h1 class="text-3xl font-extrabold text-emerald-900 tracking-tight">Buat Surat Otomatis</h1>
                    <p class="mt-2 text-sm text-gray-600">Isi form di bawah untuk men-generate surat resmi PDF secara otomatis.</p>
                </div>
                <a href="{{ route('arsip-digital') }}" class="btn btn-secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Arsip
                </a>
            </div>

            <!-- Form Area -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <form action="{{ route('buat-surat.store') }}" method="POST" class="p-8 space-y-8">
                    @csrf
                    
                    <!-- Section 1: Informasi Dasar -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Surat</label>
                            <input type="text" name="nomor_surat" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all" placeholder="Contoh: 001/YARSI/XII/2024" required>
                            <p class="text-xs text-gray-500 mt-1">Format nomor sesuai aturan instansi.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Lampiran</label>
                            <input type="text" name="lampiran" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all" placeholder="Contoh: 1 (Satu) Berkas" value="-">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Perihal</label>
                            <input type="text" name="perihal" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all" placeholder="Contoh: Undangan Rapat Evaluasi" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tujuan (Kepada Yth.)</label>
                            <input type="text" name="tujuan" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all" placeholder="Contoh: Kepala Dinas Kesehatan" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tempat Pembuatan</label>
                            <input type="text" name="tempat" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all" value="Mataram" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Surat</label>
                            <input type="date" name="tanggal" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <!-- Section 2: Isi Surat -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Isi Surat</label>
                        <textarea name="isi" rows="10" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all" placeholder="Tuliskan isi surat secara lengkap..." required></textarea>
                        <p class="text-xs text-gray-500 mt-1">Gunakan enter untuk paragraf baru.</p>
                    </div>

                    <!-- Section 3: Penanda Tangan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Penanda Tangan</label>
                            <input type="text" name="nama_ttd" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all" placeholder="Nama Direktur / Pejabat" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jabatan</label>
                            <input type="text" name="jabatan_ttd" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all" placeholder="Contoh: Direktur" required>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-4 pt-6 mt-6 border-t border-gray-200">
                        <button type="reset" class="btn btn-secondary">Reset Form</button>
                        <button type="submit" class="btn btn-primary px-8 py-3 text-lg shadow-lg hover:shadow-emerald-500/30">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Generate PDF & Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
  </div>
  @include('partials.scripts')
</body>
</html>
