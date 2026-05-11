<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Kepegawaian - YARSI NTB</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_rsi_ntb_new.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800">
    <main class="min-h-screen grid lg:grid-cols-[1.05fr_0.95fr]">
        <section class="hidden lg:flex flex-col justify-between bg-brand-900 text-white px-12 py-10">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo_rsi_ntb_new.png') }}" alt="Logo" class="h-14 w-auto bg-white rounded-lg p-1">
                <div>
                    <p class="text-lg font-bold leading-tight">YARSI NTB</p>
                    <p class="text-sm text-cyan-100">Sistem Kepegawaian</p>
                </div>
            </div>

            <div class="max-w-xl">
                <p class="mb-4 inline-flex rounded-lg bg-white/10 px-3 py-1 text-sm font-semibold text-cyan-100">Portal dokumen pegawai</p>
                <h1 class="text-5xl font-bold leading-tight tracking-tight">Pengajuan dokumen pegawai dengan alur verifikasi yang tertib.</h1>
                <div class="mt-8 grid grid-cols-3 gap-3">
                    <div class="rounded-lg border border-white/10 bg-white/10 p-4">
                        <p class="text-2xl font-bold">Pegawai</p>
                        <p class="mt-1 text-sm text-cyan-100">Upload dan revisi</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/10 p-4">
                        <p class="text-2xl font-bold">Staff</p>
                        <p class="mt-1 text-sm text-cyan-100">Verifikasi berkas</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/10 p-4">
                        <p class="text-2xl font-bold">Sekjen</p>
                        <p class="mt-1 text-sm text-cyan-100">Persetujuan akhir</p>
                    </div>
                </div>
            </div>

            <p class="text-sm text-cyan-100">&copy; {{ date('Y') }} Yayasan Rumah Sakit Islam NTB</p>
        </section>

        <section class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6">
            <div class="w-full max-w-md">
                <div class="mb-8 text-center lg:hidden">
                    <img src="{{ asset('images/logo_rsi_ntb_new.png') }}" alt="Logo" class="mx-auto h-20 w-auto">
                    <h1 class="mt-4 text-2xl font-bold text-brand-900">Sistem Kepegawaian</h1>
                </div>

                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-slate-950">Masuk Akun</h2>
                        <p class="mt-1 text-sm text-slate-500">Gunakan username pegawai, staff, atau sekjen.</p>
                    </div>

                    @if(session('error'))
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ session('error') }}</div>
                    @endif

                    @if(session('success'))
                        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="login_source" value="kepegawaian">

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Username / NIP</label>
                            <input type="text" name="username" value="{{ old('username') }}" required autofocus class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-brand-600 focus:ring-2 focus:ring-cyan-100" placeholder="contoh: 198801012014041001">
                        </div>

                        <div>
                            <div class="mb-2 flex items-center justify-between">
                                <label class="block text-sm font-bold text-slate-700">Password</label>
                                <a href="{{ route('kepegawaian.forgot-password') }}" class="text-sm font-semibold text-brand-700 hover:text-brand-900">Lupa password?</a>
                            </div>
                            <input type="password" name="password" required class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-brand-600 focus:ring-2 focus:ring-cyan-100" placeholder="Password akun">
                        </div>

                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-600">
                            Ingat perangkat ini
                        </label>

                        <button type="submit" class="w-full rounded-lg bg-brand-600 px-4 py-3 text-sm font-bold text-white shadow-sm hover:bg-brand-700">Masuk ke Kepegawaian</button>
                    </form>
                </div>

                <a href="{{ url('/') }}" class="mt-5 inline-flex w-full items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50">Kembali ke Portal</a>
            </div>
        </section>
    </main>
</body>
</html>
