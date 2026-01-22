# Panduan Update Website di cPanel

Berikut adalah langkah-langkah untuk mengupdate website Anda di cPanel setelah ada perubahan kode dari GitHub (seperti penambahan fitur Nomor Surat).

## 1. Buka Terminal di cPanel
Masuk ke akun cPanel Anda, cari menu **Terminal** atau **Shell Access** dan bukalah.

## 2. Masuk ke Folder Proyek
Gunakan perintah `cd` untuk masuk ke folder tempat Anda menyimpan file website.
*Contoh (sesuaikan dengan nama folder asli Anda):*
```bash
cd public_html
# atau nama folder project anda
cd manajemensurat 
```

## 3. Tarik Kode Terbaru (Git Pull)
Jalankan perintah ini untuk mengambil perubahan terbaru dari GitHub:
```bash
git pull origin main
```
*Jika diminta username/password dan Anda menggunakan HTTPS, masukkan token GitHub Anda (bukan password akun).*

## 4. Jalankan Migrasi Database
Karena kita menambahkan kolom `nomor_surat` baru di database, Anda **WAJIB** menjalankan perintah ini:
```bash
php artisan migrate --force
```
*Flag `--force` digunakan untuk melewati konfirmasi "Are you sure?" di production.*

## 5. Bersihkan Cache
Agar perubahan tampilan dan route langsung terlihat/berfungsi:
```bash
php artisan optimize:clear
```

---
**Ringkasan Perintah (Copy-Paste satu per satu):**
```bash
git pull origin main
php artisan migrate --force
php artisan optimize:clear
```
