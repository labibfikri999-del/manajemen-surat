@extends('kepegawaian.layouts.app')

@section('title', 'Reset Password')
@section('page-title', 'Reset Password Pegawai')
@section('eyebrow', 'Bantuan Akses')

@section('content')
<div class="grid gap-6 xl:grid-cols-[0.85fr_1.15fr]">
    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-lg font-bold text-slate-950">Prosedur Aman</h2>
        <div class="mt-5 space-y-4">
            @foreach([
                'Pegawai mengajukan reset memakai NIP dan kontak terdaftar.',
                'Staff memverifikasi identitas dan status kepegawaian.',
                'Sistem membuat password sementara satu kali pakai.',
                'Pegawai wajib mengganti password setelah berhasil masuk.'
            ] as $step)
                <div class="flex gap-3">
                    <div class="h-8 w-8 shrink-0 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center text-sm font-bold">{{ $loop->iteration }}</div>
                    <p class="pt-1 text-sm font-semibold text-slate-700">{{ $step }}</p>
                </div>
            @endforeach
        </div>
        <div class="mt-6 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm font-semibold text-amber-800">
            Hindari menyimpan password asli pegawai. Simpan hanya hash password dan gunakan password sementara untuk proses reset.
        </div>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="font-bold text-slate-950">Permintaan Reset</h2>
            <p class="text-sm text-slate-500">Antrean pegawai yang tidak bisa masuk akun.</p>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($requests as $request)
                <div class="p-5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="font-bold text-slate-950">{{ $request['pegawai'] }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $request['nip'] }} | {{ $request['kontak'] }}</p>
                            <p class="mt-1 text-xs text-slate-400">{{ $request['waktu'] }}</p>
                        </div>
                        <span class="w-fit rounded-lg bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">{{ $request['status'] }}</span>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <form action="{{ route('kepegawaian.reset-password.action', $request['id']) }}" method="POST">
                            @csrf
                            <button name="action" value="verify" class="rounded-lg bg-brand-600 px-3 py-2 text-sm font-bold text-white">Verifikasi</button>
                        </form>
                        <form action="{{ route('kepegawaian.reset-password.action', $request['id']) }}" method="POST">
                            @csrf
                            <button name="action" value="temp_password" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-bold text-slate-700">Buat Password Sementara</button>
                        </form>
                        <form action="{{ route('kepegawaian.reset-password.action', $request['id']) }}" method="POST">
                            @csrf
                            <button name="action" value="reject" class="rounded-lg border border-red-200 px-3 py-2 text-sm font-bold text-red-700">Tolak</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-sm font-semibold text-slate-500">Belum ada permintaan reset password.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
