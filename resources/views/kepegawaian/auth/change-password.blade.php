@extends('kepegawaian.layouts.app')

@section('title', 'Ganti Password')
@section('page-title', 'Ganti Password Awal')
@section('eyebrow', 'Keamanan Akun')

@section('content')
<div class="mx-auto max-w-xl rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
    <h2 class="text-lg font-bold text-slate-950">Buat Password Baru</h2>
    <p class="mt-1 text-sm text-slate-500">Akun dengan password sementara wajib mengganti password sebelum memakai modul Kepegawaian.</p>

    <form action="{{ route('kepegawaian.password.update') }}" method="POST" class="mt-6 space-y-4">
        @csrf
        <div>
            <label class="mb-2 block text-sm font-bold text-slate-700">Password Baru</label>
            <input type="password" name="password" required class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-brand-600 focus:ring-2 focus:ring-cyan-100" placeholder="Minimal 8 karakter">
        </div>
        <div>
            <label class="mb-2 block text-sm font-bold text-slate-700">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" required class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-brand-600 focus:ring-2 focus:ring-cyan-100" placeholder="Ulangi password baru">
        </div>
        <button class="w-full rounded-lg bg-brand-600 px-4 py-3 text-sm font-bold text-white hover:bg-brand-700">Simpan Password Baru</button>
    </form>
</div>
@endsection
