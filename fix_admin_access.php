<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Script untuk update user 'admin' di Production
// Gunakan via 'php artisan tinker' atau buat route sementara

$admin = User::where('username', 'admin')->first();

if ($admin) {
    // Berikan akses ke SEMUA modul
    $admin->module_access = ['surat', 'aset', 'sdm', 'keuangan', 'pegawai'];
    $admin->save();
    echo "Sukses! User 'admin' sekarang punya akses: " . json_encode($admin->module_access) . "\n";
} else {
    echo "User 'admin' tidak ditemukan.\n";
}
