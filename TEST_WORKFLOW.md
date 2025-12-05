# Test Workflow - Sistem Manajemen Surat

## User Credentials

### 1. Direktur (Admin)
- **Email**: direktur@yarsi-ntb.ac.id
- **Password**: direktur@2025
- **Role**: direktur
- **Akses**: Dashboard, Validasi Dokumen, Data Master, Arsip Digital, Hasil Validasi, Laporan

### 2. Staff (Operator)
- **Email**: staff@yarsi-ntb.ac.id
- **Password**: staff@2025
- **Role**: staff
- **Akses**: Dashboard, Proses Dokumen, Arsip Digital, Hasil Validasi, Laporan

### 3. Instansi (Submitter)
- **Email**: instansi1@yarsi-ntb.ac.id s/d instansi7@yarsi-ntb.ac.id
- **Password**: mataram10
- **Role**: instansi
- **Akses**: Dashboard, Upload Dokumen, Tracking Dokumen, Hasil Validasi, Laporan

---

## Test Flow

### STEP 1: Login sebagai INSTANSI
1. Buka `http://127.0.0.1:8000/login`
2. Masukkan Email: `instansi1@yarsi-ntb.ac.id`
3. Masukkan Password: `mataram10`
4. Klik Login

**Expected Result:**
- ✅ Redirect ke dashboard
- ✅ Tampil role badge "Login sebagai Instansi 1"
- ✅ Sidebar show menus: Dashboard, Upload Dokumen, Tracking Dokumen, Hasil Validasi, Laporan
- ✅ Menus yang tidak akses locked (cursor: not-allowed, opacity: 0.6, icon: 🔒)
  - Validasi Dokumen (Direktur only)
  - Data Master (Direktur only)
  - Proses Dokumen (Staff only)
  - Arsip Digital (Direktur & Staff only)

---

### STEP 2: Upload Dokumen (Instansi)
1. Klik menu "Upload Dokumen" di sidebar
2. Isi form:
   - **Judul**: "Surat Permohonan Kerjasama"
   - **Deskripsi**: "Permohonan kerjasama dengan institusi lain"
   - **File**: Pilih file PDF atau Word (max 10MB)
3. Klik "Upload Dokumen"

**Expected Result:**
- ✅ File berhasil di-upload
- ✅ Toast notification: "Dokumen berhasil diunggah"
- ✅ Dokumen status: "pending" (menunggu validasi direktur)
- ✅ Nomor dokumen auto-generated dengan format: [INSTANSI_KODE]/[NOMOR]/2025

---

### STEP 3: Tracking Dokumen (Instansi)
1. Dari dashboard Instansi, klik "Tracking Dokumen"
2. Lihat tabel dokumen yang sudah di-upload

**Expected Result:**
- ✅ Tabel menampilkan dokumen yang baru di-upload
- ✅ Kolom status menunjukkan: "⏳ Menunggu Validasi"
- ✅ Informasi: tanggal upload, judul, jenis, deskripsi
- ✅ Status card summary:
  - Menunggu: 1
  - Disetujui: 0
  - Ditolak: 0
  - Selesai: 0

---

### STEP 4: Login sebagai DIREKTUR
1. Logout dari akun Instansi (klik logout di sidebar)
2. Buka `http://127.0.0.1:8000/login`
3. Masukkan Email: `direktur@yarsi-ntb.ac.id`
4. Masukkan Password: `direktur@2025`
5. Klik Login

**Expected Result:**
- ✅ Redirect ke dashboard Direktur
- ✅ Tampil role badge "Login sebagai Direktur Yayasan"
- ✅ Sidebar show menus: Dashboard, Validasi Dokumen, Data Master, Arsip Digital, Hasil Validasi, Laporan
- ✅ Menus yang tidak akses locked:
  - Upload Dokumen (Instansi only)
  - Tracking Dokumen (Instansi only)
  - Proses Dokumen (Staff only)

---

### STEP 5: Validasi Dokumen (Direktur)
1. Dari dashboard Direktur, klik "Validasi Dokumen"
2. Lihat list dokumen dengan status "pending" atau "review"
3. Klik dokumen yang baru di-upload dari Instansi 1
4. Modal akan terbuka untuk validasi
5. Pilih:
   - **Status**: "Disetujui" ✓
   - **Catatan**: "Dokumen lengkap dan sesuai"
6. Klik "Validasi"

**Expected Result:**
- ✅ Modal tertutup
- ✅ Toast notification: "Dokumen berhasil divalidasi"
- ✅ Status dokumen berubah: pending → disetujui
- ✅ Tanggal validasi tercatat
- ✅ Validator terisi (nama Direktur)

---

### STEP 6: Login sebagai STAFF
1. Logout dari akun Direktur
2. Buka `http://127.0.0.1:8000/login`
3. Masukkan Email: `staff@yarsi-ntb.ac.id`
4. Masukkan Password: `staff@2025`
5. Klik Login

**Expected Result:**
- ✅ Redirect ke dashboard Staff
- ✅ Tampil role badge "Login sebagai Staff Direktur"
- ✅ Sidebar show menus: Dashboard, Proses Dokumen, Arsip Digital, Hasil Validasi, Laporan
- ✅ Menus yang tidak akses locked:
  - Upload Dokumen (Instansi only)
  - Tracking Dokumen (Instansi only)
  - Validasi Dokumen (Direktur only)
  - Data Master (Direktur only)

---

### STEP 7: Proses Dokumen (Staff)
1. Dari dashboard Staff, klik "Proses Dokumen"
2. Lihat list dokumen dengan status "disetujui"
3. Klik dokumen yang sudah divalidasi Direktur
4. Modal akan terbuka untuk proses
5. Pilih:
   - **Status**: "Diproses" ⚙️
   - **Catatan**: "Sedang dalam proses digitalisasi"
6. Klik "Proses"

**Expected Result:**
- ✅ Modal tertutup
- ✅ Toast notification: "Status dokumen berhasil diupdate"
- ✅ Status dokumen berubah: disetujui → diproses
- ✅ Tanggal proses tercatat
- ✅ Processor terisi (nama Staff)

---

### STEP 8: Finalisasi Dokumen (Staff)
1. Masih di halaman "Proses Dokumen"
2. Klik dokumen yang status "diproses"
3. Modal terbuka untuk finalisasi
4. Pilih:
   - **Status**: "Selesai" ✓
   - **Catatan**: "Dokumen berhasil didigitalisasi dan disimpan"
5. Klik "Proses"

**Expected Result:**
- ✅ Modal tertutup
- ✅ Toast notification: "Status dokumen berhasil diupdate"
- ✅ Status dokumen berubah: diproses → selesai
- ✅ Tanggal selesai tercatat
- ✅ Dokumen tidak lagi muncul di list "Proses Dokumen"

---

### STEP 9: Lihat Hasil Validasi (Semua Role)
1. Dari dashboard, klik "Hasil Validasi"
2. Lihat tabel dengan filter status:
   - Semua
   - Disetujui
   - Ditolak
   - Diproses
   - Selesai

**Expected Result:**
- ✅ Tabel menampilkan dokumen yang sudah diproses
- ✅ Status cards menunjukkan statistik:
  - Disetujui: [count]
  - Ditolak: [count]
  - Diproses: [count]
  - Selesai: [count]
- ✅ Informasi lengkap: nomor, judul, instansi, validator, processor, tanggal
- ✅ Untuk Instansi: hanya lihat dokumen sendiri
- ✅ Untuk Direktur & Staff: lihat semua dokumen

---

### STEP 10: Back to Instansi - Tracking Updated
1. Logout dari Staff
2. Login ulang sebagai Instansi 1 (`instansi1@yarsi-ntb.ac.id` / `mataram10`)
3. Klik "Tracking Dokumen"

**Expected Result:**
- ✅ Dokumen menampilkan status: "✓ Selesai"
- ✅ Status card summary updated:
  - Menunggu: 0
  - Disetujui: 1 (dari yang sudah validasi)
  - Ditolak: 0
  - Selesai: 1 (dari yang baru difinalisasi)

---

## Test Scenario 2: Dokumen Ditolak

### Upload Dokumen Baru (Instansi)
1. Upload dokumen baru dengan judul "Surat Tidak Lengkap"

### Validasi Dokumen - Reject (Direktur)
1. Login sebagai Direktur
2. Buka "Validasi Dokumen"
3. Validasi dokumen "Surat Tidak Lengkap"
4. Pilih Status: "Ditolak" ✗
5. Catatan: "Dokumen tidak lengkap, mohon revisi"
6. Klik "Validasi"

**Expected Result:**
- ✅ Status berubah: pending → ditolak
- ✅ Dokumen tidak muncul di list "Proses Dokumen" (Staff)
- ✅ Direktur bisa lihat di "Hasil Validasi" dengan status "Ditolak"
- ✅ Instansi bisa lihat di "Tracking" dan "Hasil Validasi" dengan status "Ditolak"

---

## Test Scenario 3: Menu Lock Testing

### Test Access Control dengan Direktur
1. Login sebagai Direktur
2. Lihat sidebar - menu yang locked harus tidak bisa diklik:
   - "Upload Dokumen" 🔒 (hover show tooltip "Khusus Instansi")
   - "Tracking Dokumen" 🔒
   - "Proses Dokumen" 🔒 (Staff only)
3. Coba akses langsung via URL (bypass): `http://127.0.0.1:8000/upload-dokumen`

**Expected Result:**
- ✅ Cursor jadi `not-allowed` saat hover locked menu
- ✅ Menu tidak bisa diklik (event listener preventDefault)
- ✅ Jika coba akses via URL: redirect ke dashboard dengan toast error 🔒
- ✅ Flash message: "🔒 Anda tidak memiliki akses ke halaman ini. Khusus Instansi"

---

## Test Scenario 4: Laporan & Arsip

### Laporan (Semua Role)
1. Login dengan salah satu role
2. Klik "Laporan" di sidebar
3. Lihat statistik:
   - Surat Masuk (legacy)
   - Surat Keluar (legacy)
   - Arsip Digital
   - Chart: Monthly data
   - Chart: Distribution by type
4. Klik "Cetak" untuk print

**Expected Result:**
- ✅ Statistik card menampilkan data
- ✅ Charts render dengan baik
- ✅ Cetak membuka print dialog browser

### Arsip Digital (Direktur & Staff)
1. Login sebagai Direktur atau Staff
2. Klik "Arsip Digital"
3. Lihat list dokumen yang status "selesai"
4. Bisa download dokumen

**Expected Result:**
- ✅ List menampilkan dokumen
- ✅ Download button berfungsi
- ✅ File ter-download dengan nama sesuai

---

## Troubleshooting Checklist

- [ ] Database migrasi sudah jalan: `php artisan migrate`
- [ ] Seeder sudah jalan: `php artisan db:seed`
- [ ] View cache di-clear: `php artisan view:clear`
- [ ] Cache di-clear: `php artisan cache:clear`
- [ ] Storage link ada: `php artisan storage:link` (untuk public file access)
- [ ] File permissions: `storage/` dan `public/` writable
- [ ] `.env` configuration sudah tepat:
  ```
  DB_CONNECTION=mysql
  DB_HOST=localhost
  DB_PORT=3306
  DB_DATABASE=manajemen_surat
  DB_USERNAME=root
  DB_PASSWORD=
  
  FILESYSTEM_DISK=public
  ```

---

## Expected Issues & Solutions

### Issue 1: File Upload Gagal
**Symptom**: Upload dokumen tidak berhasil, error "File tidak ditemukan"
**Solution**: 
```bash
php artisan storage:link
```
Pastikan folder `storage/app/public` writable.

### Issue 2: Dokumen Tidak Muncul di List
**Symptom**: Upload berhasil tapi dokumen tidak muncul di tracking
**Solution**: 
- Check database: `SELECT * FROM dokumens;`
- Clear cache: `php artisan cache:clear`
- Refresh page

### Issue 3: Sidebar Lock Tidak Berfungsi
**Symptom**: Menu locked bisa diklik
**Solution**:
- Clear browser cache
- Check `partials/sidebar-menu.blade.php` has correct classes
- Verify `partials/scripts.blade.php` loaded

### Issue 4: Role Redirect Tidak Bekerja
**Symptom**: Bisa akses halaman yang seharusnya di-lock
**Solution**:
- Clear route cache: `php artisan route:clear`
- Verify `app/Http/Middleware/CheckRole.php` loaded
- Check routes middleware setup

---

## Performance Notes

- Initial load: ~500ms (dengan Chart.js)
- API response time: ~50-100ms
- Database queries: Eager loading with `->with(...)` untuk prevent N+1
- Sidebar toggle: localStorage caching untuk state persistence

---

## Next Steps (Post-Testing)

1. ✅ Confirm all workflows functional
2. ⏳ Test file upload dengan berbagai format (PDF, Word, Excel)
3. ⏳ Test concurrent users (multiple roles at same time)
4. ⏳ Test error handling (invalid file, network error, etc)
5. ⏳ Test responsive design (mobile, tablet, desktop)
6. ⏳ Push to GitHub: `git add . && git commit -m "Complete role-based document workflow system" && git push`
