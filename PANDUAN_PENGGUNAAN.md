# 📚 PANDUAN PENGGUNAAN SISTEM ARSIP DIGITAL YARSI NTB

## 🎯 Tentang Sistem

Sistem Arsip Digital YARSI NTB adalah aplikasi manajemen dokumen berbasis web yang memungkinkan instansi di bawah naungan Yayasan YARSI NTB untuk mengirim, memvalidasi, dan memproses dokumen secara digital.

---

## 👥 Daftar Akun Login

### Direktur & Staff
| Role | Email | Password |
|------|-------|----------|
| Direktur | `direktur@yarsi.ac.id` | `direktur123` |
| Staff | `staff@yarsi.ac.id` | `staff123` |

### Instansi (7 User)
| Instansi | Email | Password |
|----------|-------|----------|
| RS Islam Siti Hajar Mataram | `rsi@yarsi.ac.id` | `rsi123` |
| Institut Kesehatan Yarsi Mataram | `ikym@yarsi.ac.id` | `ikym123` |
| SMK Yarsi Mataram | `smk@yarsi.ac.id` | `smk123` |
| SMA IT Yarsi Mataram | `smait@yarsi.ac.id` | `smait123` |
| SMP IT Yarsi Mataram | `smpit@yarsi.ac.id` | `smpit123` |
| SD IT Fauziah Yarsi Mataram | `sdit@yarsi.ac.id` | `sdit123` |
| TK Yarsi Mataram | `tk@yarsi.ac.id` | `tk123` |

---

## 🔐 Cara Login

1. Buka browser dan akses: `http://127.0.0.1:8000`
2. Anda akan diarahkan ke halaman login
3. Masukkan **Email** dan **Password** sesuai akun Anda
4. Centang **"Ingat saya"** jika ingin tetap login
5. Klik tombol **"Masuk ke Sistem"**

---

## 📊 Dashboard

Setelah login, Anda akan melihat **Dashboard** yang menampilkan:
- **Surat Masuk**: Jumlah total dokumen masuk
- **Surat Keluar**: Jumlah dokumen yang ditolak
- **Arsip Digital**: Total arsip tersimpan
- **Pengguna Aktif**: Jumlah user dalam sistem

---

## 🎭 Fitur Berdasarkan Role

### 1️⃣ INSTANSI (RS, Sekolah, dll)

#### 📤 Upload Dokumen
1. Klik menu **"Upload Dokumen"** di sidebar
2. Isi form:
   - **Judul Dokumen**: Nama/judul dokumen
   - **Jenis Dokumen**: Pilih kategori (Surat Masuk, Surat Keluar, dll)
   - **Deskripsi**: Keterangan singkat
   - **File**: Upload file (PDF, DOC, DOCX, maks 10MB)
3. Klik **"Upload Dokumen"**
4. Notifikasi hijau akan muncul jika berhasil

#### 📍 Tracking Dokumen
1. Klik menu **"Tracking Dokumen"**
2. Lihat daftar dokumen yang sudah Anda upload
3. Status dokumen:
   - 🟡 **Pending**: Menunggu validasi
   - 🟢 **Disetujui**: Sudah divalidasi Direktur
   - 🔴 **Ditolak**: Ditolak oleh Direktur
   - 🔵 **Diproses**: Sedang diproses Staff
   - ✅ **Selesai**: Proses selesai

#### 📋 Hasil Validasi
- Klik menu **"Hasil Validasi"** untuk melihat dokumen yang sudah divalidasi
- Dapat melihat catatan dari Direktur/Staff

#### 📈 Laporan
- Klik menu **"Laporan"** untuk melihat statistik dokumen

---

### 2️⃣ DIREKTUR

#### ✅ Validasi Dokumen
1. Klik menu **"Validasi Dokumen"**
2. Lihat daftar dokumen yang perlu divalidasi
3. Klik tombol **"Validasi"** pada dokumen
4. Modal akan muncul:
   - Pilih **"Disetujui"** atau **"Ditolak"**
   - Isi **Catatan** (opsional)
5. Klik **"Simpan"**
6. Notifikasi akan muncul dan halaman refresh otomatis

#### 📁 Arsip Digital
1. Klik menu **"Arsip Digital"**
2. Upload dokumen arsip:
   - Klik tombol **"Upload Dokumen"**
   - Isi nama dokumen, kategori, deskripsi
   - Pilih file
   - Klik **"Upload"**
3. Kelola arsip:
   - **Lihat**: Buka file di tab baru
   - **Edit**: Ubah informasi dokumen
   - **Hapus**: Hapus dokumen

#### 🗂️ Data Master
1. Klik menu **"Data Master"**
2. Kelola data instansi dan pengguna

#### 📊 Laporan & Hasil Validasi
- Akses statistik dan riwayat validasi

---

### 3️⃣ STAFF DIREKTUR

#### ⚙️ Proses Dokumen
1. Klik menu **"Proses Dokumen"**
2. Lihat dokumen yang sudah **Disetujui** oleh Direktur
3. Klik tombol **"Proses"** pada dokumen
4. Modal akan muncul:
   - Isi **Catatan Proses**
   - Klik **"Mulai Proses"**
5. Status berubah menjadi **"Diproses"**

#### ✔️ Menyelesaikan Dokumen
1. Klik tombol **"Selesai"** pada dokumen yang statusnya **"Diproses"**
2. Modal konfirmasi akan muncul
3. Isi catatan penyelesaian
4. Klik **"Tandai Selesai"**
5. Status berubah menjadi **"Selesai"**

#### 📁 Arsip Digital
- Sama seperti Direktur, dapat mengelola arsip digital

---

## 🔒 Menu yang Dikunci

Setiap role memiliki akses berbeda. Menu yang tidak bisa diakses akan:
- Berwarna **abu-abu**
- Memiliki ikon **🔒**
- Tidak bisa diklik
- Menampilkan tooltip saat di-hover

| Menu | Direktur | Staff | Instansi |
|------|:--------:|:-----:|:--------:|
| Dashboard | ✅ | ✅ | ✅ |
| Upload Dokumen | 🔒 | 🔒 | ✅ |
| Tracking Dokumen | 🔒 | 🔒 | ✅ |
| Validasi Dokumen | ✅ | 🔒 | 🔒 |
| Proses Dokumen | 🔒 | ✅ | 🔒 |
| Arsip Digital | ✅ | ✅ | 🔒 |
| Hasil Validasi | ✅ | ✅ | ✅ |
| Laporan | ✅ | ✅ | ✅ |
| Data Master | ✅ | 🔒 | 🔒 |

---

## 📱 Fitur Responsif

Website ini dapat diakses dari:
- 💻 **Desktop/Laptop**: Tampilan penuh dengan sidebar
- 📱 **Tablet/HP**: Sidebar dapat dibuka dengan tombol menu (☰)

### Collapse Sidebar (Desktop)
- Klik tombol **panah** di atas sidebar
- Sidebar akan menciut, hanya menampilkan ikon
- Klik lagi untuk mengembalikan

---

## 🔄 Alur Kerja Dokumen

```
┌─────────────────┐
│    INSTANSI     │
│ Upload Dokumen  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│    DIREKTUR     │
│ Validasi        │
│ ✅ Setuju       │
│ ❌ Tolak        │
└────────┬────────┘
         │ (Jika Disetujui)
         ▼
┌─────────────────┐
│     STAFF       │
│ Proses Dokumen  │
│ ⚙️ Diproses     │
│ ✅ Selesai      │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│   INSTANSI      │
│ Tracking Status │
│ ✅ Selesai!     │
└─────────────────┘
```

---

## 💡 Tips Penggunaan

1. **Refresh halaman** jika data tidak muncul: Tekan `Ctrl + F5`
2. **Notifikasi tidak muncul?** Pastikan JavaScript aktif di browser
3. **File tidak bisa diupload?** Cek ukuran file (maks 10MB) dan format (PDF, DOC, DOCX)
4. **Lupa password?** Hubungi administrator sistem

---

## 🆘 Troubleshooting

### Tidak bisa login
- Pastikan email dan password benar
- Cek capslock keyboard
- Clear cache browser: `Ctrl + Shift + Delete`

### Halaman blank/error
- Refresh halaman: `F5`
- Clear cache: `Ctrl + Shift + Delete`
- Coba browser lain

### Upload gagal
- Cek koneksi internet
- Pastikan ukuran file < 10MB
- Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PNG, JPG

### Toast notification tertutup
- Notifikasi muncul di pojok kanan atas
- Otomatis hilang setelah 3 detik

---

## 📞 Kontak Administrator

Jika mengalami kendala teknis:
- **Email**: admin@yarsi-ntb.ac.id
- **Telepon**: 0370-XXXXXX

---

## 🛠️ Untuk Administrator

### Menjalankan Server
```bash
cd C:\laravel\manajemensurat
php artisan serve
```

### Reset Database
```bash
php artisan migrate:fresh --seed
```

### Clear Cache
```bash
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

---

**Terakhir diperbarui**: 5 Desember 2025  
**Versi**: 1.0.0
