<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akses Ditolak - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Space Grotesk', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col items-center justify-center p-4">
    <div class="text-center max-w-lg mx-auto">
        <div class="relative w-40 h-40 mx-auto mb-8 bg-red-100 rounded-full flex items-center justify-center">
            <svg class="w-20 h-20 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        
        <h1 class="text-4xl font-bold text-slate-800 mb-4">Akses Ditolak</h1>
        <p class="text-slate-500 text-lg mb-8">
            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="history.back()" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-600 font-bold hover:bg-slate-50 transition-colors">
                Kembali
            </button>
            <a href="{{ url('/') }}" class="px-6 py-3 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 shadow-lg shadow-red-200 transition-all active:scale-95">
                Ke Dashboard
            </a>
        </div>
    </div>
    
    <div class="mt-12 text-slate-400 text-sm">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>
</body>
</html>
