# 📋 DOKUMENTASI SISTEM MANAJEMEN SURAT
## Yayasan YARSI NTB

---

## 📌 DAFTAR USER LOGIN

### 🔑 **1. DIREKTUR (Admin Utama)**
| Field | Value |
|-------|-------|
| **Email** | `direktur@yarsi.ac.id` |
| **Password** | `direktur123` |
| **Role** | Direktur |
| **Jabatan** | Direktur Yayasan YARSI NTB |

---

### 🔑 **2. STAFF DIREKTUR**
| Field | Value |
|-------|-------|
| **Email** | `staff@yarsi.ac.id` |
| **Password** | `staff123` |
| **Role** | Staff |
| **Jabatan** | Staff Administrasi Direktur |

---

### 🔑 **3. USER INSTANSI (7 Instansi)**

| No | Instansi | Email | Password |
|----|----------|-------|----------|
| 1 | RS Islam Siti Hajar | `rsi@yarsi.ac.id` | `rsi123` |
| 2 | Institut Kesehatan Yarsi | `ikym@yarsi.ac.id` | `ikym123` |
| 3 | SMK Yarsi Mataram | `smk@yarsi.ac.id` | `smk123` |
| 4 | SMA IT Yarsi Mataram | `smait@yarsi.ac.id` | `smait123` |
| 5 | SMP IT Yarsi Mataram | `smpit@yarsi.ac.id` | `smpit123` |
| 6 | SD IT Fauziah Yarsi | `sdit@yarsi.ac.id` | `sdit123` |
| 7 | TK Yarsi Mataram | `tk@yarsi.ac.id` | `tk123` |

---

## 🔄 ALUR PENGGUNAAN SISTEM

### **ALUR UTAMA: User Instansi → Direktur → Staff → Arsip Digital**

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│  USER INSTANSI  │────▶│    DIREKTUR     │────▶│     STAFF       │────▶│  ARSIP DIGITAL  │
│  Upload WORD    │     │  Validasi Surat │     │  Proses Surat   │     │  5 Kategori     │
└─────────────────┘     └─────────────────┘     └─────────────────┘     └─────────────────┘
```

---

## 📝 LANGKAH-LANGKAH DETAIL

### **STEP 1: User Instansi - Upload Dokumen**

1. **Login** menggunakan email & password instansi (contoh: `rsi@yarsi.ac.id` / `rsi123`)
2. Klik menu **"Upload Dokumen"** di sidebar
3. Isi form:
   - **Judul Dokumen**: Nama/judul surat
   - **Deskripsi**: Keterangan singkat (opsional)
   - **Upload File**: Pilih file **WORD (.doc/.docx)** saja
4. Klik **"Upload"**
5. Dokumen akan masuk ke antrian validasi Direktur dengan status **"Pending"**

> ⚠️ **Catatan**: User instansi hanya bisa upload file WORD. File lain (PDF, Excel, dll) akan ditolak.

---

### **STEP 2: Direktur - Validasi Dokumen**

1. **Login** sebagai Direktur (`direktur@yarsi.ac.id` / `direktur123`)
2. Klik menu **"Validasi Surat"** di sidebar
3. Lihat daftar dokumen yang perlu divalidasi
4. Klik dokumen untuk melihat detail
5. Pilih aksi:
   - ✅ **Setujui**: Dokumen diteruskan ke Staff untuk diproses
   - ❌ **Tolak**: Dokumen dikembalikan dengan catatan alasan penolakan
6. Tambahkan **catatan validasi** jika diperlukan
7. Klik **"Submit"**

---

### **STEP 3: Staff - Proses Dokumen**

1. **Login** sebagai Staff (`staff@yarsi.ac.id` / `staff123`)
2. Klik menu **"Proses Dokumen"** di sidebar
3. Lihat daftar dokumen yang sudah divalidasi Direktur
4. Klik dokumen untuk memproses
5. Lakukan:
   - 📄 **Download file WORD** asli dari user
   - ✏️ **Edit/proses dokumen** sesuai kebutuhan
   - 📤 **Upload File Pengganti** (bisa PDF, gambar, dll)
   - 📁 **Pilih Kategori Arsip**: UMUM / SDM / ASSET / HUKUM / KEUANGAN
   - 📝 Tambahkan **catatan proses** jika perlu
6. Klik **"Selesai"**
7. Dokumen otomatis masuk ke **Arsip Digital** sesuai kategori yang dipilih

---

### **STEP 4: Arsip Digital**

1. Semua dokumen yang sudah selesai diproses masuk ke **Arsip Digital**
2. Dokumen dikelompokkan dalam **5 folder kategori**:
   - 📁 **UMUM** - Dokumen umum
   - 👥 **SDM** - Surat kepegawaian, SDM
   - 🏢 **ASSET** - Dokumen aset, inventaris
   - ⚖️ **HUKUM** - Dokumen legal, hukum
   - 💰 **KEUANGAN** - Dokumen keuangan, anggaran
3. Staff/Direktur juga bisa **upload langsung** ke arsip digital tanpa melalui alur validasi

---

## 🔐 HAK AKSES PER ROLE

| Fitur | Direktur | Staff | User Instansi |
|-------|:--------:|:-----:|:-------------:|
| Dashboard | ✅ | ✅ | ✅ |
| Upload Dokumen | ❌ | ❌ | ✅ |
| Validasi Surat | ✅ | ❌ | ❌ |
| Proses Dokumen | ❌ | ✅ | ❌ |
| Hasil Validasi | ✅ | ✅ | ✅ |
| Arsip Digital | ✅ | ✅ | ✅ (lihat saja) |
| Upload ke Arsip | ✅ | ✅ | ❌ |
| Laporan | ✅ | ✅ | ❌ |
| Kelola User | ✅ | ❌ | ❌ |
| Kelola Instansi | ✅ | ❌ | ❌ |

---

## 📊 STATUS DOKUMEN

| Status | Keterangan | Warna |
|--------|------------|-------|
| **Pending** | Menunggu validasi Direktur | 🟡 Kuning |
| **Review** | Sedang direview Direktur | 🔵 Biru |
| **Disetujui** | Disetujui, menunggu diproses Staff | 🟢 Hijau |
| **Ditolak** | Ditolak oleh Direktur | 🔴 Merah |
| **Diproses** | Sedang diproses oleh Staff | 🟠 Orange |
| **Selesai** | Selesai diproses, masuk arsip | ✅ Hijau Tua |

---

## 📁 KATEGORI ARSIP DIGITAL

| Kategori | Icon | Deskripsi |
|----------|------|-----------|
| **UMUM** | 📁 | Dokumen umum, surat menyurat biasa |
| **SDM** | 👥 | Surat kepegawaian, SK, kontrak kerja |
| **ASSET** | 🏢 | Dokumen inventaris, pengadaan barang |
| **HUKUM** | ⚖️ | MoU, perjanjian, dokumen legal |
| **KEUANGAN** | 💰 | Laporan keuangan, anggaran, invoice |

---

## 🚀 QUICK START

### Untuk Testing Cepat:

1. **Login sebagai User Instansi** → Upload file WORD
   ```
   Email: rsi@yarsi.ac.id
   Password: rsi123
   ```

2. **Login sebagai Direktur** → Validasi/Setujui dokumen
   ```
   Email: direktur@yarsi.ac.id
   Password: direktur123
   ```

3. **Login sebagai Staff** → Proses dokumen, upload file pengganti, pilih kategori
   ```
   Email: staff@yarsi.ac.id
   Password: staff123
   ```

4. **Cek Arsip Digital** → Dokumen akan muncul di folder sesuai kategori

---

## 📞 KONTAK SUPPORT

Jika mengalami kendala, hubungi:
- **Developer**: Labib Fikri
- **Email**: labib@gmail.com

---

*Dokumentasi ini dibuat pada: 6 Desember 2025*
*Sistem Manajemen Surat - Yayasan YARSI NTB*
