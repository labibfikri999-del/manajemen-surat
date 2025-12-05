# TESTING GUIDE - STEP BY STEP

## 🎯 QUICK START (5 MINUTES)

### 1. Start Server
```bash
cd C:\laravel\manajemensurat
php artisan serve
```
✅ Server running: http://127.0.0.1:8000

### 2. Open in Browser
```
http://127.0.0.1:8000/login
```

### 3. Test Login with Direktur
```
Email:    direktur@yarsi-ntb.ac.id
Password: direktur@2025
```

**Expected**: ✅ Redirect to dashboard with role badge "Login sebagai Direktur Yayasan"

---

## 📝 COMPLETE TEST SCENARIOS

### SCENARIO 1: FULL WORKFLOW (20 minutes)

#### Phase 1: Instansi Upload (5 min)
1. Logout (if logged in)
2. Login: `instansi1@yarsi-ntb.ac.id` / `mataram10`
3. Click "Upload Dokumen"
4. Fill form:
   - Judul: "Surat Permohonan Kerjasama"
   - Deskripsi: "Test dokumen upload"
   - File: Choose any PDF/Word file
5. Click "Upload Dokumen"

**Verify**:
- ✅ Toast: "Dokumen berhasil diunggah" (green, auto-closes 3s)
- ✅ Form clears or redirect
- ✅ File exists in `storage/app/public/dokumen/INST1/`

#### Phase 2: Direktur Validate (5 min)
1. Logout
2. Login: `direktur@yarsi-ntb.ac.id` / `direktur@2025`
3. Click "Validasi Dokumen"
4. Find dokumen from Instansi 1 (should be first in list, status: "Menunggu")
5. Click dokumen row (should open modal)
6. Fill modal:
   - Status: Select "✓ Disetujui"
   - Catatan: "Dokumen lengkap dan sesuai"
7. Click "Validasi" button in modal

**Verify**:
- ✅ Modal closes
- ✅ Toast: "Dokumen berhasil divalidasi" (green)
- ✅ List refreshes (dokumen disappears or status changes to "✓ Disetujui")

#### Phase 3: Staff Process (5 min)
1. Logout
2. Login: `staff@yarsi-ntb.ac.id` / `staff@2025`
3. Click "Proses Dokumen"
4. Find dokumen status "✓ Disetujui" (should show "Disetujui")
5. Click dokumen row (should open modal)
6. Fill modal:
   - Status: Select "⚙️ Diproses"
   - Catatan: "Sedang dalam proses digitalisasi"
7. Click "Proses" button

**Verify**:
- ✅ Modal closes
- ✅ Toast: "Status dokumen berhasil diupdate" (green)
- ✅ Dokumen still in list but status shows "⚙️ Diproses"

#### Phase 4: Staff Complete (5 min)
1. On same "Proses Dokumen" page
2. Click dokumen status "⚙️ Diproses" again
3. Modal opens again
4. Fill modal:
   - Status: Select "✓ Selesai"
   - Catatan: "Dokumen berhasil didigitalisasi"
5. Click "Proses" button

**Verify**:
- ✅ Modal closes
- ✅ Toast: "Status dokumen berhasil diupdate" (green)
- ✅ Dokumen disappears from "Proses Dokumen" list (not there anymore)

#### Phase 5: Instansi Track Status (5 min)
1. Logout
2. Login: `instansi1@yarsi-ntb.ac.id` / `mataram10`
3. Click "Tracking Dokumen"
4. Find dokumen uploaded earlier

**Verify**:
- ✅ Dokumen shows status "✓ Selesai"
- ✅ Informasi lengkap: validator name, processor name, dates
- ✅ Status summary cards show:
  - Disetujui: at least 1
  - Selesai: 1 (the one we just completed)

---

### SCENARIO 2: MENU LOCKING TEST (10 minutes)

#### Test Locked Menus as Direktur
1. Login: `direktur@yarsi-ntb.ac.id` / `direktur@2025`
2. Look at sidebar menus

**Verify These Are Locked** (gray 🔒):
- [ ] "Upload Dokumen" - opacity 0.6, lock icon visible
- [ ] "Tracking Dokumen" - opacity 0.6, lock icon visible
- [ ] "Proses Dokumen" - opacity 0.6, lock icon visible

**Test Locked Menu Interaction**:
1. Hover over "Upload Dokumen" (locked)
   - ✅ Tooltip shows "🔒 Khusus Instansi"
   - ✅ Cursor changes to `not-allowed`
2. Try click on locked menu
   - ✅ Nothing happens (menu not clickable)

**Test Unauthorized Direct Access**:
1. Try accessing URL directly: `http://127.0.0.1:8000/upload-dokumen`
2. ✅ Redirect to dashboard
3. ✅ Toast error: "🔒 Anda tidak memiliki akses ke halaman ini. Khusus Instansi"

#### Test Locked Menus as Staff
1. Logout
2. Login: `staff@yarsi-ntb.ac.id` / `staff@2025`

**Verify These Are Locked** (gray 🔒):
- [ ] "Upload Dokumen"
- [ ] "Tracking Dokumen"
- [ ] "Validasi Dokumen"
- [ ] "Data Master"

**Verify These Are OPEN** (color, clickable):
- [ ] "Dashboard" ✓
- [ ] "Proses Dokumen" ✓
- [ ] "Arsip Digital" ✓
- [ ] "Hasil Validasi" ✓
- [ ] "Laporan" ✓

---

### SCENARIO 3: REJECT WORKFLOW (10 minutes)

#### Upload New Document
1. Login: `instansi2@yarsi-ntb.ac.id` / `mataram10`
2. Click "Upload Dokumen"
3. Upload file with judul: "Test Dokumen Reject"
4. ✅ Toast: "Dokumen berhasil diunggah"

#### Direktur Reject
1. Logout
2. Login: `direktur@yarsi-ntb.ac.id` / `direktur@2025`
3. Click "Validasi Dokumen"
4. Find "Test Dokumen Reject" (should be in list)
5. Click dokumen → modal opens
6. Select Status: "✗ Ditolak"
7. Catatan: "Dokumen tidak lengkap, mohon revisi"
8. Click "Validasi"

**Verify**:
- ✅ Toast: "Dokumen berhasil divalidasi"
- ✅ Dokumen disappears from Direktur list

#### Staff Doesn't See Rejected
1. Logout
2. Login: `staff@yarsi-ntb.ac.id` / `staff@2025`
3. Click "Proses Dokumen"

**Verify**:
- ✅ Rejected dokumen NOT in Staff list (only disetujui+ documents)

#### Instansi Sees Rejection
1. Logout
2. Login: `instansi2@yarsi-ntb.ac.id` / `mataram10`
3. Click "Hasil Validasi"

**Verify**:
- ✅ Dokumen shows status "✗ Ditolak"
- ✅ Can see catatan: "Dokumen tidak lengkap, mohon revisi"

---

### SCENARIO 4: DATA ISOLATION TEST (5 minutes)

#### Upload Dokumen from Instansi 1 & 2
1. Login: `instansi1@yarsi-ntb.ac.id` / `mataram10`
2. Upload dokumen: "Dokumen Instansi 1"
3. Logout

4. Login: `instansi2@yarsi-ntb.ac.id` / `mataram10`
5. Upload dokumen: "Dokumen Instansi 2"

#### Test Instansi 1 Cannot See Instansi 2's Document
1. Logout
2. Login: `instansi1@yarsi-ntb.ac.id` / `mataram10`
3. Click "Tracking Dokumen"

**Verify**:
- ✅ Only "Dokumen Instansi 1" visible
- ✅ "Dokumen Instansi 2" NOT visible

4. Click "Hasil Validasi"

**Verify**:
- ✅ Only documents from Instansi 1 shown
- ✅ Instansi 2's documents NOT visible

#### Test Direktur Sees All
1. Logout
2. Login: `direktur@yarsi-ntb.ac.id` / `direktur@2025`
3. Click "Hasil Validasi"

**Verify**:
- ✅ Both "Dokumen Instansi 1" AND "Dokumen Instansi 2" visible
- ✅ Can see all institutions' documents

---

### SCENARIO 5: RESPONSIVE DESIGN (5 minutes)

#### Desktop (1920px)
1. Open browser at full screen width
2. ✅ Sidebar visible on left
3. ✅ Content on right with good spacing
4. ✅ All menus visible without scrolling

#### Tablet (768px)
1. Open DevTools: F12
2. Click device toolbar (mobile icon)
3. Set to iPad/Tablet width (~768px)
4. ✅ Sidebar collapse button visible
5. Click collapse button (< icon)
6. ✅ Sidebar collapses
7. ✅ Content expands to full width
8. Click expand button
9. ✅ Sidebar re-expands

#### Mobile (375px)
1. Set width to 375px (mobile)
2. ✅ Hamburger menu (≡) visible top-left
3. ✅ Sidebar hidden by default
4. Click hamburger button
5. ✅ Sidebar slides in from left
6. ✅ Dark overlay appears (clickable to close)
7. Click overlay
8. ✅ Sidebar closes
9. Click on sidebar menu item
10. ✅ Sidebar auto-closes after navigation

---

## 🔍 DETAILED VERIFICATION CHECKLIST

### ✅ AUTHENTICATION
- [ ] Login page loads at /login
- [ ] Invalid email shows error
- [ ] Invalid password shows error
- [ ] Valid credentials redirect to /dashboard
- [ ] Logout button clears session
- [ ] Back button after logout doesn't access dashboard

### ✅ DASHBOARD
- [ ] Dashboard loads with correct role badge
- [ ] Statistics cards show correct numbers
- [ ] All accessible menus highlighted (clickable)
- [ ] All locked menus grayed out (not clickable)
- [ ] Flash messages display if any
- [ ] Welcome message includes user name

### ✅ UPLOAD DOKUMEN (Instansi)
- [ ] Upload page accessible
- [ ] Form validates required fields
- [ ] File upload with drag-drop works
- [ ] File selected shows preview/name
- [ ] Submit button uploads file
- [ ] Toast notification appears
- [ ] File stored in storage/app/public/dokumen/[CODE]/
- [ ] Database record created with status='pending'

### ✅ TRACKING DOKUMEN (Instansi)
- [ ] Tracking page accessible
- [ ] Only user's own documents shown
- [ ] Status labels with icons (⏳, ✓, ✗, ⚙️, ✓)
- [ ] Table columns: Tanggal, Judul, Deskripsi, Status
- [ ] Status summary cards show correct counts
- [ ] Can see validator name when approved
- [ ] Can see processor name when processed
- [ ] Timestamps shown (validasi, proses, selesai)

### ✅ VALIDASI DOKUMEN (Direktur)
- [ ] Validasi page accessible
- [ ] List shows only pending/review documents
- [ ] Click dokumen opens modal
- [ ] Modal shows: dokumen info, file info, status select
- [ ] Status options: Disetujui ✓, Ditolak ✗
- [ ] Can enter catatan/notes
- [ ] Submit button updates database
- [ ] Toast notification shows success/error
- [ ] Database updated: status, validated_by, tanggal_validasi, catatan_validasi
- [ ] List refreshes (dokumen removed from list)

### ✅ PROSES DOKUMEN (Staff)
- [ ] Proses page accessible
- [ ] Only disetujui & diproses documents shown
- [ ] Click dokumen opens modal
- [ ] Modal shows: dokumen info, current status, next status options
- [ ] Step 1 options: Diproses ⚙️
- [ ] Step 2 options: Selesai ✓
- [ ] Can enter catatan/notes
- [ ] Submit button updates database
- [ ] Toast notification shows success
- [ ] Database updated: status, processed_by, tanggal_proses, tanggal_selesai
- [ ] List refreshes (selesai dokumen removed from list)

### ✅ HASIL VALIDASI (All Roles)
- [ ] Page accessible to all 3 roles
- [ ] Status filter buttons work (Semua, Disetujui, Ditolak, Diproses, Selesai)
- [ ] Status cards show counts for each status
- [ ] Table shows all matching documents
- [ ] For Instansi: only their documents shown
- [ ] For Direktur/Staff: all documents shown
- [ ] Complete info: nomor, judul, instansi, validator, processor, status, dates

### ✅ ARSIP DIGITAL (Direktur & Staff)
- [ ] Page accessible to direktur & staff
- [ ] Locked for instansi (gray, 🔒)
- [ ] Shows only selesai documents
- [ ] Can see all info: nomor, judul, instansi, tanggal_selesai
- [ ] Download button available
- [ ] Download works (file downloads)
- [ ] File name correct
- [ ] File integrity (can open downloaded file)

### ✅ LAPORAN (All Roles)
- [ ] Page accessible to all roles
- [ ] Chart.js renders:
  - [ ] Bar chart (monthly data)
  - [ ] Doughnut chart (distribution)
- [ ] Statistics cards show numbers
- [ ] Progress bars show percentages
- [ ] Buttons work:
  - [ ] "Buat Laporan" → Blue toast "Fitur sedang dalam pengembangan"
  - [ ] "Cetak" → Opens browser print dialog

### ✅ DATA MASTER (Direktur)
- [ ] Page accessible to direktur
- [ ] Locked for staff & instansi
- [ ] Shows list of instansis
- [ ] Shows list of users
- [ ] Can view but probably not edit (MVP)

### ✅ SIDEBAR & NAVIGATION
- [ ] Role badge displays with avatar
- [ ] Collapse button toggles sidebar (desktop)
- [ ] Collapse state persisted in localStorage
- [ ] Mobile hamburger menu works (mobile view)
- [ ] Mobile overlay closes sidebar (mobile view)
- [ ] Logout form works and clears session
- [ ] Active menu highlighted (has background color)
- [ ] Locked menus have 🔒 icon on right

### ✅ FLASH MESSAGES
- [ ] Success messages: Green background ✓
- [ ] Error messages: Red background ✗
- [ ] Warning messages: Yellow background ⚠️
- [ ] Info messages: Blue background ℹ️
- [ ] All auto-close after 3 seconds
- [ ] Close button (×) works
- [ ] Fade-in animation on appear
- [ ] Multiple messages queue properly

### ✅ ERROR HANDLING
- [ ] Try access locked page → Redirect + error toast
- [ ] Upload file > 10MB → Validation error shown
- [ ] Submit form without required fields → Validation error
- [ ] Invalid session → Redirect to login
- [ ] Missing file on download → Friendly error message
- [ ] No 403 error pages shown (all redirected)

### ✅ DATABASE INTEGRITY
- [ ] All new dokumens have unique nomor_dokumen
- [ ] Status changes properly recorded
- [ ] Timestamps auto-populated (created_at, updated_at, etc)
- [ ] User references correct (validated_by, processed_by)
- [ ] File paths correct in database
- [ ] No orphaned records
- [ ] Cascade delete works (if instansi deleted, dokumens deleted)

---

## 📊 TEST RESULTS TEMPLATE

```markdown
## TEST RESULTS - [DATE]

### Overall Status
- [ ] PASSED - All tests successful
- [ ] PARTIAL - Some failures, noted below
- [ ] FAILED - Critical issues found

### Tests Executed
- [ ] Scenario 1: Full Workflow - PASS / FAIL
- [ ] Scenario 2: Menu Locking - PASS / FAIL
- [ ] Scenario 3: Reject Workflow - PASS / FAIL
- [ ] Scenario 4: Data Isolation - PASS / FAIL
- [ ] Scenario 5: Responsive Design - PASS / FAIL

### Issues Found
1. [Issue description]
   - Severity: Low / Medium / High
   - Reproducible: Yes / No
   - Fix Status: Pending / In Progress / Fixed

### Recommendations
- [Recommendation 1]
- [Recommendation 2]

### Sign Off
- Tester: [Name]
- Date: [Date]
- Status: Ready for Deployment / Needs Fixes
```

---

## 🚀 AFTER TESTING - COMMIT & DEPLOY

### 1. Review Changes
```bash
git status
git diff
```

### 2. Add All Changes
```bash
cd C:\laravel\manajemensurat
git add .
```

### 3. Commit
```bash
git commit -m "Complete role-based document management system - all tests passing"
```

### 4. Push to GitHub
```bash
git push origin main
```

### 5. Verify on GitHub
- Open https://github.com/labibfikri999-del/manajemen-surat
- Verify changes pushed
- Check commit message

---

## 💾 BACKUP BEFORE TESTING

If first time testing:

```bash
# Create backup of database
mysqldump -u root manajemen_surat > backup_manajemen_surat.sql

# OR backup entire project folder
# Copy c:\laravel\manajemensurat to c:\laravel\manajemensurat_backup
```

---

## ⏱️ ESTIMATED TESTING TIME

| Scenario | Time | Critical |
|----------|------|----------|
| Scenario 1: Full Workflow | 20 min | ✅ YES |
| Scenario 2: Menu Locking | 10 min | ✅ YES |
| Scenario 3: Reject Workflow | 10 min | ✅ YES |
| Scenario 4: Data Isolation | 5 min | ✅ YES |
| Scenario 5: Responsive | 5 min | ❌ NO |
| **TOTAL** | **~50 min** | |

---

## 📞 TROUBLESHOOTING

### Problem: "File not found" error
**Solution**: Run `php artisan storage:link`

### Problem: Dokumen not appearing in list after upload
**Solution**: Clear cache `php artisan cache:clear && php artisan view:clear`

### Problem: Locked menu is clickable
**Solution**: Clear browser cache (Ctrl+Shift+Delete) and refresh

### Problem: Upload fails with 413 Payload Too Large
**Solution**: File > 10MB, choose smaller file

### Problem: Toast not disappearing
**Solution**: Normal if dismissed manually, otherwise check browser console

### Problem: Sidebar localStorage not working
**Solution**: Check browser allows localStorage, disable browser privacy mode

---

## 📖 DOCUMENTATION REFERENCES

- **Full Test Scenarios**: `TEST_WORKFLOW.md`
- **Quick Checklist**: `QUICK_TEST.md`
- **System Status**: `SYSTEM_STATUS.md`
- **Roles & Permissions**: `ROLES_AND_PERMISSIONS.md`
- **This File**: `TESTING_GUIDE.md`

---

**Created**: December 5, 2025  
**Last Updated**: December 5, 2025  
**Version**: 1.0 (Complete & Ready)

**Status**: ✅ READY FOR TESTING

---

## START TESTING NOW! 🎉

1. Run: `php artisan serve`
2. Open: http://127.0.0.1:8000
3. Follow scenarios above
4. Report any issues
5. Commit when all pass

Good luck! 🚀
