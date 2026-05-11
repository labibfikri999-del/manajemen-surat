<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password Kepegawaian - YARSI NTB</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_rsi_ntb_new.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-lg">
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-6 flex items-center gap-3">
                    <img src="{{ asset('images/logo_rsi_ntb_new.png') }}" alt="Logo" class="h-14 w-auto">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-950">Lupa Password</h1>
                        <p class="text-sm text-slate-500">Ajukan bantuan reset ke Staff Kepegawaian.</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('kepegawaian.forgot-password.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">NIP / Username</label>
                        <input name="nip" value="{{ old('nip') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-brand-600 focus:ring-2 focus:ring-cyan-100" placeholder="Masukkan NIP atau username">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Kontak Aktif</label>
                        <input name="kontak" value="{{ old('kontak') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-brand-600 focus:ring-2 focus:ring-cyan-100" placeholder="Nomor HP atau email aktif">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Catatan</label>
                        <textarea name="alasan" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-brand-600 focus:ring-2 focus:ring-cyan-100" placeholder="Opsional, contoh: lupa password setelah ganti perangkat">{{ old('alasan') }}</textarea>
                    </div>
                    <button class="w-full rounded-lg bg-brand-600 px-4 py-3 text-sm font-bold text-white hover:bg-brand-700">Kirim Permintaan Reset</button>
                </form>
            </div>
            <a href="{{ route('kepegawaian.login') }}" class="mt-5 inline-flex w-full items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50">Kembali ke Login</a>
        </div>
    </main>
</body>
</html>
