@extends('sdm.layouts.app')

@section('content')
<div x-data="{ 
    activeTab: new URLSearchParams(window.location.search).get('tab') || 'general',
    showCreateUserModal: false
}" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('sdm.dashboard') }}" class="p-2 bg-white rounded-full text-slate-500 hover:text-cyan-600 shadow-sm border border-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pengaturan SDM</h1>
            <p class="text-slate-500 text-sm mt-1">Konfigurasi sistem manajemen sumber daya manusia.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Sidebar Navigation -->
        <div class="col-span-1 space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
                <nav class="flex flex-col">
                    <a href="#general" @click.prevent="activeTab = 'general'" :class="{ 'bg-cyan-50 text-cyan-700 border-cyan-600': activeTab === 'general', 'text-slate-600 border-transparent hover:bg-slate-50': activeTab !== 'general' }" class="flex items-center gap-3 px-4 py-3 font-bold border-l-4 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Profil Instansi
                    </a>
                    <a href="#attendance" @click.prevent="activeTab = 'attendance'" :class="{ 'bg-cyan-50 text-cyan-700 border-cyan-600': activeTab === 'attendance', 'text-slate-600 border-transparent hover:bg-slate-50': activeTab !== 'attendance' }" class="flex items-center gap-3 px-4 py-3 font-medium border-l-4 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1 -18 0 9 9 0 0 1 18 0z"></path></svg>
                        Jam Kerja & Shift
                    </a>
                    <a href="#payroll" @click.prevent="activeTab = 'payroll'" :class="{ 'bg-cyan-50 text-cyan-700 border-cyan-600': activeTab === 'payroll', 'text-slate-600 border-transparent hover:bg-slate-50': activeTab !== 'payroll' }" class="flex items-center gap-3 px-4 py-3 font-medium border-l-4 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 1 1 -18 0 9 9 0 0 1 18 0z"></path></svg>
                        Payroll & Pajak
                    </a>
                    <a href="#users" @click.prevent="activeTab = 'users'" :class="{ 'bg-cyan-50 text-cyan-700 border-cyan-600': activeTab === 'users', 'text-slate-600 border-transparent hover:bg-slate-50': activeTab !== 'users' }" class="flex items-center gap-3 px-4 py-3 font-medium border-l-4 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Manajemen Akun
                    </a>
                </nav>
            </div>
            
            <button x-show="activeTab !== 'users'" type="button" onclick="document.getElementById('settings-form').submit()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-bold shadow-sm transition-all active:scale-95 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Perubahan
            </button>
        </div>

        <!-- Main Settings Content -->
        <div class="col-span-1 md:col-span-2 space-y-6">
            
            <!-- Settings Form -->
            <form id="settings-form" action="{{ route('sdm.settings.update') }}" method="POST">
                @csrf
                <!-- Company Profile Section -->
                <div x-show="activeTab === 'general'" id="general" class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 pb-2 border-b border-slate-100">Informasi Umum</h3>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Instansi</label>
                                <input type="text" name="company_name" value="{{ $settings->get('company_name') }}" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Kode Instansi</label>
                                <input type="text" value="RSI-NTB-001" class="w-full bg-slate-50 rounded-lg border-slate-300 text-slate-500 shadow-sm" readonly>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap</label>
                            <textarea name="company_address" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" rows="3">{{ $settings->get('company_address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Attendance Section -->
                <div x-show="activeTab === 'attendance'" id="attendance" class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 pb-2 border-b border-slate-100">Pengaturan Absensi</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Jam Masuk (Default)</label>
                            <input type="time" name="work_start_time" value="{{ $settings->get('work_start_time') }}" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Jam Pulang (Default)</label>
                            <input type="time" name="work_end_time" value="{{ $settings->get('work_end_time') }}" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- Payroll Section -->
                <div x-show="activeTab === 'payroll'" id="payroll" class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 pb-2 border-b border-slate-100">Konfigurasi Payroll</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Pajak PPh 21 (%)</label>
                            <div class="relative">
                                <input type="number" name="payroll_tax_rate" value="{{ $settings->get('payroll_tax_rate') }}" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm pr-10">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-slate-500 font-bold">%</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Potongan BPJS (%)</label>
                             <div class="relative">
                                <input type="number" name="bpjs_rate" value="{{ $settings->get('bpjs_rate') }}" class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm pr-10">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-slate-500 font-bold">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Users Section -->
            <div x-cloak x-show="activeTab === 'users'" id="users" class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <div class="flex justify-between items-center mb-6 pb-2 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Manajemen Pengguna</h3>
                    <button @click="showCreateUserModal = true" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-bold hover:bg-emerald-700 shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Pengguna
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Instansi</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @foreach($users as $user)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $user->username }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 uppercase">{{ $user->role }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $user->instansi ? $user->instansi->nama : '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <!-- Edit Button (To be implemented with modal) -->
                                        <!-- Delete Form -->
                                        <form action="{{ route('sdm.settings.user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-900 p-1 bg-rose-50 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
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
    </div>

    <!-- Create User Modal -->
    <div x-cloak x-show="showCreateUserModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showCreateUserModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showCreateUserModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('sdm.settings.user.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">Tambah Pengguna Baru</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700">Nama Lengkap</label>
                                        <input type="text" name="name" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700">Username</label>
                                        <input type="text" name="username" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700">Email</label>
                                        <input type="email" name="email" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700">Password</label>
                                        <input type="password" name="password" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700">Role</label>
                                        <select name="role" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="">Pilih Role...</option>
                                            <option value="direktur">Direktur</option>
                                            <option value="staff">Staff</option>
                                            <option value="instansi">Instansi</option>
                                        </select>
                                    </div>
                                    <div x-show="false" class="hidden">
                                        <!-- Hidden instansi logic if needed, simplify for now -->
                                        <label class="block text-sm font-bold text-slate-700">Instansi</label>
                                        <select name="instansi_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="">Pilih Instansi...</option>
                                            @foreach($instansis ?? [] as $instansi)
                                                <option value="{{ $instansi->id }}">{{ $instansi->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button @click="showCreateUserModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
