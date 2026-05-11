@extends('kepegawaian.layouts.app')

@section('title', 'Akun Pegawai')
@section('page-title', 'Akun Pegawai')
@section('eyebrow', 'Administrasi Akses')

@section('content')
<div x-data="{ search: '', matches(value) { return value.includes(this.search.toLowerCase()); } }" class="space-y-6">
    <div class="grid gap-4 lg:grid-cols-3">
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="font-bold text-slate-950">Tambah Manual</h2>
            <p class="mt-2 text-sm text-slate-500">Untuk satu pegawai atau akun khusus staff dan sekjen.</p>
            <form action="{{ route('kepegawaian.akun.action') }}" method="POST" class="mt-4 space-y-3">
                @csrf
                <input type="hidden" name="action" value="add">
                <input name="nama" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Nama pegawai">
                <input name="username" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="NIP / username">
                <input name="email" type="email" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Email aktif">
                <select name="role" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="pegawai">Pegawai</option>
                    <option value="staff_kepegawaian">Staff Kepegawaian</option>
                    <option value="sekjen">Sekjen</option>
                </select>
                <button class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-bold text-white">Tambah Akun</button>
            </form>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="font-bold text-slate-950">Import Excel</h2>
            <p class="mt-2 text-sm text-slate-500">Buat akun pegawai banyak dari template NIP, nama, email, unit, jabatan.</p>
            <form action="{{ route('kepegawaian.akun.action') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-3">
                @csrf
                <input type="hidden" name="action" value="import">
                <input name="template" type="file" accept=".xlsx,.xls,.csv" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <div class="flex flex-wrap gap-2">
                    <button class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700">Upload Template</button>
                    <a href="{{ route('kepegawaian.akun.template') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700">Download CSV</a>
                </div>
            </form>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="font-bold text-slate-950">Generate Password</h2>
            <p class="mt-2 text-sm text-slate-500">Password awal dibuat sementara dan pegawai wajib mengganti saat login pertama.</p>
            <form action="{{ route('kepegawaian.akun.action') }}" method="POST" class="mt-4">
                @csrf
                <button name="action" value="generate" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700">Generate Batch</button>
            </form>
        </div>
    </div>

    @if(session('generated_passwords'))
        <div class="rounded-lg border border-amber-200 bg-amber-50 p-5 shadow-sm">
            <h2 class="font-bold text-amber-900">Password Sementara</h2>
            <p class="mt-1 text-sm text-amber-800">Berikan password ini ke pegawai terkait. Password wajib diganti saat login pertama.</p>
            <div class="mt-4 overflow-x-auto">
                <table class="w-full min-w-[560px] text-left text-sm">
                    <thead class="text-xs uppercase tracking-wide text-amber-800">
                        <tr>
                            <th class="py-2 pr-4">Nama</th>
                            <th class="py-2 pr-4">Username</th>
                            <th class="py-2 pr-4">Password</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-200">
                        @foreach(session('generated_passwords') as $passwordRow)
                            <tr>
                                <td class="py-2 pr-4 font-semibold">{{ $passwordRow['nama'] }}</td>
                                <td class="py-2 pr-4">{{ $passwordRow['username'] }}</td>
                                <td class="py-2 pr-4 font-mono font-bold">{{ $passwordRow['password'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if(session('import_errors'))
        <div class="rounded-lg border border-red-200 bg-red-50 p-5 shadow-sm">
            <h2 class="font-bold text-red-900">Catatan Import</h2>
            <div class="mt-3 space-y-1 text-sm font-semibold text-red-700">
                @foreach(session('import_errors') as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-slate-950">Daftar Akun</h2>
                <p class="text-sm text-slate-500">Akun pegawai, staff kepegawaian, dan sekjen.</p>
            </div>
            <input x-model="search" type="search" class="rounded-lg border border-slate-300 px-4 py-2 text-sm outline-none focus:border-brand-600 focus:ring-2 focus:ring-cyan-100" placeholder="Cari akun">
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Nama</th>
                        <th class="px-5 py-3">Username</th>
                        <th class="px-5 py-3">Role</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($accounts as $account)
                        <tr x-show="matches(@js(strtolower($account['nama'].' '.$account['username'].' '.$account['role'].' '.$account['status'])))" class="hover:bg-slate-50">
                            <td class="px-5 py-4 font-bold text-slate-900">{{ $account['nama'] }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $account['username'] }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $account['role'] }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-lg px-2.5 py-1 text-xs font-bold {{ $account['status'] === 'Aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">{{ $account['status'] }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex gap-2">
                                    <form action="{{ route('kepegawaian.akun.action') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $account['id'] }}">
                                        <button name="action" value="toggle" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-700">{{ $account['is_active'] ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                    </form>
                                    <form action="{{ route('kepegawaian.akun.action') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $account['id'] }}">
                                        <button name="action" value="reset" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-700">Reset</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
