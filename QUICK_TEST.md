# QUICK TEST CHECKLIST - Manajemen Surat

## Server Running ✅
```
cd C:\laravel\manajemensurat
php artisan serve
```
Akses: http://127.0.0.1:8000

---

## TEST 1: LOGIN & DASHBOARD

### 1.1 Login Direktur
**URL**: http://127.0.0.1:8000/login
- Email: `direktur@yarsi-ntb.ac.id`
- Password: `direktur@2025`

**Check**:
- [ ] Redirect ke dashboard
- [ ] Badge: "Login sebagai Direktur Yayasan"
- [ ] Sidebar menus visible:
  - [x] Dashboard
  - [x] Validasi Dokumen
  - [x] Data Master
  - [x] Arsip Digital
  - [x] Hasil Validasi
  - [x] Laporan
- [ ] Locked menus (gray 🔒):
  - [ ] Upload Dokumen (Instansi)
  - [ ] Tracking Dokumen (Instansi)
  - [ ] Proses Dokumen (Staff)

### 1.2 Logout
- [ ] Klik avatar / Logout button di sidebar
- [ ] Redirect ke login page
- [ ] Session cleared

---

## TEST 2: INSTANSI WORKFLOW

### 2.1 Login Instansi
**URL**: http://127.0.0.1:8000/login
- Email: `instansi1@yarsi-ntb.ac.id`
- Password: `mataram10`

**Check**:
- [ ] Redirect ke dashboard Instansi
- [ ] Badge: "Login sebagai [Instansi Name]"
- [ ] Sidebar menus:
  - [x] Dashboard
  - [x] Upload Dokumen
  - [x] Tracking Dokumen
  - [x] Hasil Validasi
  - [x] Laporan
- [ ] Locked menus (gray 🔒):
  - [ ] Validasi Dokumen
  - [ ] Data Master
  - [ ] Proses Dokumen
  - [ ] Arsip Digital

### 2.2 Upload Dokumen
1. Klik "Upload Dokumen" di sidebar
2. Form input:
   - Judul: "Surat Permohonan Kerjasama"
   - Deskripsi: "Test upload dokumen dari instansi 1"
   - File: Upload file PDF/Word (max 10MB)
3. Klik "Upload Dokumen"

**Check**:
- [ ] Toast notification: "Dokumen berhasil diunggah"
- [ ] Redirect atau form clear
- [ ] File tersimpan di `storage/app/public/dokumen/[INSTANSI_KODE]/`

### 2.3 Tracking Dokumen
1. Klik "Tracking Dokumen" di sidebar
2. Lihat tabel dokumen

**Check**:
- [ ] Dokumen yang baru upload muncul di tabel
- [ ] Status: "⏳ Menunggu Validasi" (pending)
- [ ] Kolom: Tanggal, Judul, Deskripsi, Status
- [ ] Status summary cards:
  - [ ] Menunggu: 1+
  - [ ] Disetujui: 0+
  - [ ] Ditolak: 0+
  - [ ] Selesai: 0+

---

## TEST 3: DIREKTUR VALIDASI

### 3.1 Login Direktur
- Email: `direktur@yarsi-ntb.ac.id`
- Password: `direktur@2025`

### 3.2 Validasi Dokumen
1. Klik "Validasi Dokumen" di sidebar
2. Cari dokumen dari Instansi 1 (status: pending)
3. Klik dokumen untuk buka modal
4. Modal form:
   - Status: "Disetujui" ✓ (atau "Ditolak" ✗)
   - Catatan: "Dokumen lengkap dan sesuai"
5. Klik "Validasi"

**Check**:
- [ ] Modal tertutup
- [ ] Toast: "Dokumen berhasil divalidasi"
- [ ] Status berubah: pending → disetujui (di table)
- [ ] Direktur name tercatat di "Validator"
- [ ] Tanggal validasi tercatat

### 3.3 Klik Lock Menu (Test access control)
1. Coba klik menu "Upload Dokumen" (gray & locked)

**Check**:
- [ ] Cursor: `not-allowed`
- [ ] Menu tidak respond ke klik (preventDefault)
- [ ] Tooltip hover: "Khusus Instansi"

---

## TEST 4: STAFF PROSES

### 4.1 Login Staff
**URL**: http://127.0.0.1:8000/login
- Email: `staff@yarsi-ntb.ac.id`
- Password: `staff@2025`

**Check**:
- [ ] Badge: "Login sebagai Staff Direktur"
- [ ] Menus:
  - [x] Dashboard
  - [x] Proses Dokumen
  - [x] Arsip Digital
  - [x] Hasil Validasi
  - [x] Laporan
- [ ] Locked:
  - [ ] Validasi Dokumen
  - [ ] Data Master
  - [ ] Upload Dokumen
  - [ ] Tracking Dokumen

### 4.2 Proses Dokumen
1. Klik "Proses Dokumen" di sidebar
2. Lihat dokumen status "disetujui"
3. Klik dokumen untuk buka modal
4. Modal form:
   - Status: "Diproses" ⚙️ (step 1) atau "Selesai" ✓ (step 2)
   - Catatan: "Sedang dalam proses"
5. Klik "Proses"

**Check**:
- [ ] Modal tertutup
- [ ] Toast: "Status dokumen berhasil diupdate"
- [ ] Status berubah: disetujui → diproses (pada step 1)
- [ ] Staff name tercatat di "Processor"
- [ ] Tanggal proses tercatat

### 4.3 Finalisasi Dokumen
1. Klik dokumen status "diproses"
2. Modal form:
   - Status: "Selesai" ✓
   - Catatan: "Dokumen berhasil didigitalisasi"
3. Klik "Proses"

**Check**:
- [ ] Status berubah: diproses → selesai
- [ ] Tanggal selesai tercatat
- [ ] Dokumen tidak lagi muncul di "Proses Dokumen" list

---

## TEST 5: HASIL VALIDASI (All Roles)

### 5.1 Lihat Hasil Validasi (Direktur)
1. Login Direktur
2. Klik "Hasil Validasi"

**Check**:
- [ ] Tabel menampilkan dokumen dengan status: disetujui, ditolak, diproses, selesai
- [ ] Status filter buttons work:
  - [ ] "Semua" - show all
  - [ ] "Disetujui" - filter status
  - [ ] "Ditolak" - filter status
  - [ ] "Diproses" - filter status
  - [ ] "Selesai" - filter status
- [ ] Status cards show count:
  - [ ] Disetujui: [count]
  - [ ] Ditolak: [count]
  - [ ] Diproses: [count]
  - [ ] Selesai: [count]
- [ ] Kolom: Nomor, Judul, Instansi, Validator, Processor, Status

### 5.2 Lihat Hasil Validasi (Instansi)
1. Login Instansi 1
2. Klik "Hasil Validasi"

**Check**:
- [ ] Tabel hanya menampilkan dokumen dari Instansi 1 (bukan from other instansi)
- [ ] Filter buttons work
- [ ] Dapat melihat hasil validasi dokumen mereka

---

## TEST 6: LAPORAN (All Roles)

### 6.1 Lihat Laporan
1. Klik "Laporan" di sidebar (dari salah satu role)

**Check**:
- [ ] Halaman load dengan baik
- [ ] Chart.js render:
  - [ ] Chart 1: Monthly bar chart (Surat Masuk)
  - [ ] Chart 2: Doughnut chart (Arsip Digital Distribution)
- [ ] Statistics cards:
  - [ ] Surat Masuk: [number]
  - [ ] Surat Keluar: [number]
  - [ ] Arsip Digital: [number]
- [ ] Progress bars:
  - [ ] Surat Masuk [%]
  - [ ] Surat Keluar [%]
- [ ] Buttons:
  - [ ] "Buat Laporan" → Toast: "Fitur sedang dalam pengembangan"
  - [ ] "Cetak" → Browser print dialog

---

## TEST 7: ARSIP DIGITAL (Direktur & Staff)

### 7.1 Akses Arsip Digital
1. Login sebagai Direktur atau Staff
2. Klik "Arsip Digital" di sidebar

**Check**:
- [ ] Halaman load dengan baik
- [ ] List dokumen dengan status "selesai" ditampilkan
- [ ] Info: Nomor, Judul, Instansi, Tanggal Selesai
- [ ] Download button available

### 7.2 Download Dokumen
1. Klik "Download" button
2. File akan di-download

**Check**:
- [ ] File ter-download dengan nama asli
- [ ] File integrity terjaga

---

## TEST 8: DATA MASTER (Direktur Only)

### 8.1 Akses Data Master
1. Login Direktur
2. Klik "Data Master" di sidebar

**Check**:
- [ ] Halaman load dengan baik
- [ ] Tab/section untuk Instansi dan User
- [ ] Tabel menampilkan data master

---

## TEST 9: REJECT DOKUMEN WORKFLOW

### 9.1 Upload Dokumen (Instansi)
- Upload dokumen baru dengan judul "Test Reject"

### 9.2 Validasi Tolak (Direktur)
1. Login Direktur
2. Klik "Validasi Dokumen"
3. Validasi dokumen "Test Reject" dengan Status: "Ditolak"
4. Catatan: "Dokumen tidak lengkap"

**Check**:
- [ ] Status berubah: pending → ditolak
- [ ] Dokumen tidak muncul di "Proses Dokumen" (Staff)
- [ ] Direktur lihat di "Hasil Validasi" → status "Ditolak"

### 9.3 Instansi Lihat Reject (Instansi)
1. Login Instansi 1
2. Klik "Tracking Dokumen"

**Check**:
- [ ] Dokumen yang ditolak menampilkan status "✗ Ditolak"
- [ ] Bisa lihat catatan reject dari Direktur

---

## TEST 10: RESPONSIVE DESIGN

### 10.1 Desktop (1920px)
- [ ] Sidebar on left
- [ ] Main content wide
- [ ] All elements visible

### 10.2 Tablet (768px)
- [ ] Sidebar collapse button visible
- [ ] Mobile menu hamburger appear
- [ ] Content responsive

### 10.3 Mobile (375px)
- [ ] Sidebar hidden by default
- [ ] Hamburger menu button work
- [ ] Mobile overlay appear
- [ ] Content full width

---

## TEST 11: ERROR HANDLING

### 11.1 Try Access Locked Page (via URL)
1. Login sebagai Direktur
2. Try access: `http://127.0.0.1:8000/upload-dokumen` (Instansi only)

**Check**:
- [ ] Redirect ke dashboard
- [ ] Toast error: "🔒 Anda tidak memiliki akses..."
- [ ] Flash message visible

### 11.2 File Upload Validation
1. Try upload file > 10MB

**Check**:
- [ ] Validation error shown
- [ ] Toast: "File terlalu besar..."

### 11.3 Required Field Validation
1. Try submit upload form without judul

**Check**:
- [ ] Form validation error shown
- [ ] Cannot submit

---

## TEST 12: FLASH MESSAGES & NOTIFICATIONS

### 12.1 Success Toast
- [ ] Upload dokumen → Green toast "Dokumen berhasil diunggah"
- [ ] Validasi dokumen → Green toast "Dokumen berhasil divalidasi"
- [ ] Toast auto-close after 3 seconds

### 12.2 Error Toast
- [ ] Try access locked page → Red toast "🔒 Anda tidak memiliki akses"
- [ ] File validation fail → Red toast

### 12.3 Info Toast
- [ ] Buat Laporan button → Blue toast "Fitur sedang dalam pengembangan"

---

## DATABASE VERIFICATION

Jalankan commands ini untuk verify data:

```sql
-- Check dokumen yang di-upload
SELECT nomor_dokumen, judul, status, user_id, instansi_id, validated_by, processed_by 
FROM dokumens 
ORDER BY created_at DESC;

-- Check user login history (check last_login atau updated_at)
SELECT id, name, role, email, instansi_id 
FROM users;

-- Check instansi data
SELECT id, nama, kode, alamat 
FROM instansis;
```

---

## FINAL VERIFICATION CHECKLIST

After all tests complete, verify:

- [ ] All 3 user roles can login successfully
- [ ] Dashboard shows correct menus per role
- [ ] Locked menus display with 🔒 icon and are non-clickable
- [ ] Full workflow: Upload → Validate → Process → Complete works
- [ ] Rejected documents don't appear in Process queue
- [ ] Instansi can only see their own documents
- [ ] All flash messages display correctly
- [ ] Responsive design works on mobile/tablet/desktop
- [ ] Charts load and display data
- [ ] File upload/download works
- [ ] Sidebar collapse/expand works with localStorage persistence
- [ ] No console errors in browser DevTools (F12)
- [ ] All API endpoints return correct status codes

---

## COMMIT TO GITHUB

```bash
cd C:\laravel\manajemensurat
git add .
git commit -m "Complete role-based document management system with workflow"
git push origin main
```

---

## NOTES

- Clear cache if changes not visible: `php artisan view:clear && php artisan cache:clear`
- Storage link required: `php artisan storage:link`
- All timestamps in UTC (add 8 hours for WITA timezone)
