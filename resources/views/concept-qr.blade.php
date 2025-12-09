@extends('layouts.app')

@section('content')
<div class="container mx-auto p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Konsep Tanda Tangan Digital QR Code</h1>
        <p class="text-gray-600">Berikut adalah simulasi bagaimana surat akan terlihat setelah ditandatangani.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- 1. Contoh Dokumen -->
        <div class="bg-white p-8 rounded-lg shadow-lg border border-gray-200 relative min-h-[600px]">
            <!-- Kop Surat -->
            <div class="text-center border-b-2 border-gray-800 pb-4 mb-6">
                <img src="{{ asset('images/Logo Yayasan Bersih.png') }}" class="h-16 mx-auto mb-2" alt="Logo">
                <h2 class="text-xl font-bold uppercase">Yayasan Rumah Sakit Islam NTB</h2>
                <p class="text-sm">Jalan Kesehatan No. 1, Mataram, Nusa Tenggara Barat</p>
            </div>

            <!-- Isi Surat Dummy -->
            <div class="text-sm leading-relaxed space-y-4 mb-12 text-justify">
                <div class="flex justify-between">
                    <span>Nomor: 123/YARSI/XII/2025</span>
                    <span>7 Desember 2025</span>
                </div>
                <p>Kepada Yth,<br>Seluruh Staff<br>di Tempat</p>
                <p>Dengan hormat,</p>
                <p>Sehubungan dengan implementasi sistem manajemen surat digital, kami ingin menginformasikan bahwa mulai tanggal 1 Januari 2026, seluruh dokumen resmi akan menggunakan tanda tangan digital berbasis QR Code untuk keamanan dan kemudahan verifikasi.</p>
                <p>Dokumen ini adalah contoh mockup visual. QR Code di bawah ini menggantikan tanda tangan basah konvensional. Siapapun dapat memindai kode tersebut untuk memvalidasi keaslian surat ini langsung ke server pusat.</p>
                <p>Demikian pemberitahuan ini kami sampaikan.</p>
            </div>

            <!-- Area Tanda Tangan -->
            <div class="absolute bottom-8 right-8 text-right w-64">
                <p class="mb-4">Hormat Kami,<br>Direktur</p>
                
                <!-- Mockup QR Code Signature -->
                <div class="border-2 border-emerald-500 bg-emerald-50 p-2 rounded-lg inline-flex items-center gap-3 text-left">
                    <div class="bg-white p-1 rounded border border-emerald-200">
                        <!-- Placeholder QR -->
                        <svg class="w-16 h-16 text-gray-800" viewBox="0 0 100 100" fill="currentColor">
                            <path d="M10,10 h30 v30 h-30 z M50,10 h30 v30 h-30 z M10,50 h30 v30 h-30 z M50,50 h10 v10 h-10 z M70,50 h10 v10 h-10 z M50,70 h10 v10 h-10 z M70,70 h10 v10 h-10 z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-emerald-600 font-bold uppercase">Ditandatangani Secara Digital</p>
                        <p class="text-xs font-bold text-gray-900">Dr. Fikri, Sp.A</p>
                        <p class="text-[9px] text-gray-500">ID: 8a7b-9c2d-1e3f</p>
                        <p class="text-[9px] text-emerald-600 mt-0.5">Scan untuk verifikasi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Simulasi Verifikasi -->
        <div class="space-y-6">
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                <h3 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Simulasi: Apa yang terjadi saat discan?
                </h3>
                <p class="text-sm text-gray-600 mb-4">Pengguna akan diarahkan ke halaman validasi publik yang menampilkan:</p>
                
                <!-- Mockup Mobile Screen -->
                <div class="bg-white border-4 border-gray-800 rounded-[2rem] w-64 mx-auto overflow-hidden shadow-2xl relative">
                    <!-- Notch -->
                    <div class="bg-gray-800 h-6 w-32 mx-auto rounded-b-xl absolute top-0 left-0 right-0 z-10"></div>
                    
                    <!-- Screen Content -->
                    <div class="pt-8 pb-4 px-4 h-[400px] flex flex-col bg-gray-50">
                        <div class="text-center mb-6">
                            <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <h4 class="text-emerald-700 font-bold text-lg">Dokumen Valid</h4>
                            <p class="text-xs text-gray-500">Terverifikasi oleh Sistem YARSI</p>
                        </div>
                        
                        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100 text-xs space-y-2 mb-4">
                            <div>
                                <span class="text-gray-400 block text-[10px]">Perihal</span>
                                <span class="font-medium">Sosialisasi Tanda Tangan</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block text-[10px]">Tanggal</span>
                                <span class="font-medium">7 Des 2025</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block text-[10px]">Penandatangan</span>
                                <span class="font-medium">Direktur Utama</span>
                            </div>
                        </div>

                        <button class="bg-emerald-600 text-white py-2 rounded-lg text-xs font-medium">Download Dokumen Asli</button>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                <h4 class="text-sm font-bold text-blue-900 mb-1">Keuntungan QR Code</h4>
                <ul class="list-disc list-inside text-xs text-blue-800 space-y-1">
                    <li>Tidak bisa dipalsukan (scan akan gagal jika palsu).</li>
                    <li>Otomatis (tidak perlu tanda tangan manual satu per satu).</li>
                    <li>Terlihat profesional dan modern.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
