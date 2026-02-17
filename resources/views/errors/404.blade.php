<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman Tidak Ditemukan - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Space Grotesk', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col items-center justify-center p-4">
    <div class="text-center max-w-lg mx-auto">
        <div class="relative w-64 h-64 mx-auto mb-8">
            <div class="absolute inset-0 bg-cyan-100 rounded-full animate-pulse opacity-50"></div>
            <div class="absolute inset-4 bg-white rounded-full flex items-center justify-center shadow-lg">
                <span class="text-8xl font-bold text-cyan-500">404</span>
            </div>
        </div>
        
        <h1 class="text-4xl font-bold text-slate-800 mb-4">Halaman Tidak Ditemukan</h1>
        <p class="text-slate-500 text-lg mb-8">
            Maaf, halaman yang Anda cari tidak dapat ditemukan atau telah dipindahkan.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="history.back()" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-600 font-bold hover:bg-slate-50 transition-colors">
                Kembali
            </button>
            <a href="{{ url('/') }}" class="px-6 py-3 rounded-xl bg-cyan-600 text-white font-bold hover:bg-cyan-700 shadow-lg shadow-cyan-200 transition-all active:scale-95">
                Ke Beranda
            </a>
        </div>
    </div>
    
    <div class="mt-12 text-slate-400 text-sm">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>
</body>
</html>
