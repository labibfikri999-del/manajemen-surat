# USER ROLES & PERMISSIONS MATRIX

## Role Summary

| Role | Email | Password | Primary Function | Menus | Locked Menus |
|------|-------|----------|------------------|-------|--------------|
| **Direktur** | direktur@yarsi-ntb.ac.id | direktur@2025 | Validate & approve documents | Dashboard, Validasi Dokumen, Data Master, Arsip Digital, Hasil Validasi, Laporan | Upload Dokumen, Tracking Dokumen, Proses Dokumen |
| **Staff** | staff@yarsi-ntb.ac.id | staff@2025 | Process & finalize documents | Dashboard, Proses Dokumen, Arsip Digital, Hasil Validasi, Laporan | Validasi Dokumen, Data Master, Upload Dokumen, Tracking Dokumen |
| **Instansi** | instansi1-7@yarsi-ntb.ac.id | mataram10 | Submit & track documents | Dashboard, Upload Dokumen, Tracking Dokumen, Hasil Validasi, Laporan | Validasi Dokumen, Data Master, Proses Dokumen, Arsip Digital |

---

## DIREKTUR (Director / Admin)

### Who
- Director/Head of Organization
- 1 user: direktur@yarsi-ntb.ac.id

### What They Can Do
1. **Dashboard** - View all statistics
   - Total documents: all
   - Pending: count by status
   - Instansi summary: list all institutions with document count
   
2. **Validasi Dokumen** 🔑 MAIN JOB
   - See list of documents with status: pending, review
   - Open modal for each document
   - Choose: Disetujui ✓ or Ditolak ✗
   - Add catatan (notes/reason)
   - Save → document status changes + name recorded

3. **Data Master** ⚙️
   - Manage users (list, edit, deactivate)
   - Manage instansis (list, edit info)
   - View all system users

4. **Arsip Digital**
   - View completed documents (selesai status)
   - Download files
   - View full audit trail (who validated, who processed, dates)

5. **Hasil Validasi**
   - View ALL documents with final status
   - Filter by: Disetujui, Ditolak, Diproses, Selesai
   - See who validated & processed each document

6. **Laporan**
   - View charts & statistics
   - Export reports (button provided)
   - Print reports

### What They CANNOT Do
- Upload documents (🔒 locked)
- Track documents (🔒 locked)
- Process documents (🔒 locked - Staff job)

### Database Access
```sql
-- Direktur sees
SELECT * FROM dokumens; -- ALL documents

-- Direktur updates
- status: pending → disetujui / ditolak
- validated_by: direktur user id
- catatan_validasi: validation notes
- tanggal_validasi: current timestamp
```

---

## STAFF (Operator)

### Who
- Staff/Operator
- 1 user: staff@yarsi-ntb.ac.id

### What They Can Do
1. **Dashboard** - View approved documents statistics
   - Total documents: disetujui + diproses + selesai only
   - Disetujui: count (ready to process)
   - Diproses: count (in progress)
   - Selesai: count (completed)

2. **Proses Dokumen** 🔑 MAIN JOB
   - See list of documents status: disetujui, diproses
   - For each document: 2-step process
     - **Step 1**: Status disetujui → change to diproses
     - **Step 2**: Status diproses → change to selesai
   - Each step: add catatan (processing notes)
   - Save → document status changes + name recorded

3. **Arsip Digital**
   - View completed documents (selesai status)
   - Download files for archiving
   - See full document information

4. **Hasil Validasi**
   - View ALL documents (all statuses)
   - Filter by status
   - See complete workflow trail

5. **Laporan**
   - View charts & statistics for processed documents
   - Generate & print reports

### What They CANNOT Do
- Upload documents (🔒 locked - Instansi job)
- Track personal documents (🔒 locked - Instansi job)
- Validate/approve documents (🔒 locked - Direktur job)
- Access Data Master (🔒 locked - Direktur job)

### Database Access
```sql
-- Staff sees
SELECT * FROM dokumens 
WHERE status IN ('disetujui', 'diproses', 'selesai'); -- Only approved+

-- Staff updates
- status: disetujui → diproses → selesai
- processed_by: staff user id
- catatan_proses: processing notes
- tanggal_proses: step 1 timestamp
- tanggal_selesai: step 2 timestamp (when status=selesai)
```

---

## INSTANSI (Institution User / Submitter)

### Who
- Representatives from various institutions
- 7 users: instansi1-7@yarsi-ntb.ac.id

### What They Can Do
1. **Dashboard** - View their own documents statistics
   - Total documents: their institution only
   - Menunggu: count (pending validation)
   - Disetujui: count (approved)
   - Ditolak: count (rejected)
   - Selesai: count (completed)

2. **Upload Dokumen** 🔑 MAIN JOB
   - Fill form:
     - Judul (title): required
     - Deskripsi (description): optional
     - File: required (PDF, Word, Excel - max 10MB)
   - Click "Upload Dokumen"
   - Automatic nomor_dokumen generated: DOC/[KODE]/[YYYY][MM]/[SEQ]
   - Status set to: pending (menunggu validasi)
   - Toast notification: "Dokumen berhasil diunggah"

3. **Tracking Dokumen** 📊
   - See all their uploaded documents
   - Status for each:
     - ⏳ Menunggu → Validation pending
     - ✓ Disetujui → Approved by direktur
     - ✗ Ditolak → Rejected (see reason)
     - ⚙️ Diproses → Staff is processing
     - ✓ Selesai → Completed/Archived
   - See tanggal upload, last update, validator name, processor name

4. **Hasil Validasi**
   - View final status of all their documents
   - Filter by: Disetujui, Ditolak, Diproses, Selesai
   - See complete history (who validated, who processed, dates, notes)

5. **Laporan**
   - View charts & statistics for their documents
   - Print reports

### What They CANNOT Do
- Validate documents (🔒 locked - Direktur job)
- Process documents (🔒 locked - Staff job)
- Access Data Master (🔒 locked - Direktur job)
- View other institutions' documents (🔒 locked)
- Access Arsip Digital (🔒 locked - Direktur & Staff only)

### Database Access
```sql
-- Instansi sees
SELECT * FROM dokumens 
WHERE instansi_id = $user->instansi_id; -- Only THEIR documents

-- Instansi creates
- user_id: instansi user id
- instansi_id: instansi id
- status: 'pending' (default)
- nomor_dokumen: auto-generated
- file_path: storage/app/public/dokumen/[KODE]/filename.ext
- created_at: now

-- Instansi CANNOT
- Update status (read-only to them)
- See other instansis' documents
- Delete files after submission (if pending)
```

---

## WORKFLOW PERMISSIONS BY ACTION

### Upload Document
- **Who Can**: Instansi ✓
- **Who Cannot**: Direktur ✗, Staff ✗
- **Middleware**: `role:instansi`
- **API**: `POST /api/dokumen`

### View All Documents
- **Direktur**: All documents ✓
- **Staff**: Only disetujui+ status ✓
- **Instansi**: Only their own ✓
- **Logic**: Role-based query filtering in DokumenController

### Validate Documents
- **Who Can**: Direktur ✓
- **Who Cannot**: Staff ✗, Instansi ✗
- **Middleware**: `role:direktur`
- **API**: `POST /api/dokumen/{id}/validasi`
- **Payload**: { status: 'disetujui'|'ditolak', catatan: '...' }

### Process Documents
- **Who Can**: Staff ✓
- **Who Cannot**: Direktur ✗, Instansi ✗
- **Middleware**: `role:staff`
- **API**: `POST /api/dokumen/{id}/proses`
- **Payload**: { status: 'diproses'|'selesai', catatan: '...' }

### Download Files
- **Direktur**: All documents ✓
- **Staff**: disetujui+ documents ✓
- **Instansi**: Only their own ✓
- **API**: `GET /api/dokumen/{id}/download`
- **Storage**: `storage/app/public/dokumen/[KODE]/...`

### Edit Documents
- **Who Can**: Instansi (owner) ✓ - Only if status = pending
- **Who Cannot**: Direktur ✗, Staff ✗, Other instansi ✗
- **API**: `PUT /api/dokumen/{id}`
- **Protection**: Check `user_id == $user->id AND status == 'pending'`

### Delete Documents
- **Who Can**: Instansi (owner) ✓ - Only if status = pending
- **Who Cannot**: Direktur ✗, Staff ✗, Other instansi ✗
- **API**: `DELETE /api/dokumen/{id}`
- **Protection**: Check `user_id == $user->id AND status == 'pending'`

---

## STATUS TRANSITIONS & WHO CONTROLS

```
PENDING ──[Direktur Validasi]──→ DISETUJUI ──[Staff Process]──→ DIPROSES ──[Staff Complete]──→ SELESAI
                            └──→ DITOLAK (End - Staff skips this)
```

### Status: PENDING
- **Set By**: System (auto, when Instansi uploads)
- **Visible To**: Direktur (list), Instansi (tracking)
- **Next Action**: Direktur validates

### Status: DISETUJUI
- **Set By**: Direktur (via validasi endpoint)
- **Visible To**: Direktur, Staff, Instansi
- **Next Action**: Staff processes
- **Fields Updated**: validated_by, catatan_validasi, tanggal_validasi

### Status: DITOLAK
- **Set By**: Direktur (via validasi endpoint)
- **Visible To**: Direktur, Instansi (in hasil-validasi)
- **Next Action**: None (process stops)
- **Fields Updated**: validated_by, catatan_validasi, tanggal_validasi
- **Important**: NOT appear in Staff "Proses Dokumen" list

### Status: DIPROSES
- **Set By**: Staff (via proses endpoint)
- **Visible To**: Direktur, Staff, Instansi
- **Next Action**: Staff completes
- **Fields Updated**: processed_by, catatan_proses, tanggal_proses

### Status: SELESAI
- **Set By**: Staff (via proses endpoint)
- **Visible To**: All (in arsip-digital, hasil-validasi)
- **Next Action**: Archived
- **Fields Updated**: processed_by, catatan_proses, tanggal_selesai
- **Important**: Appears in Arsip Digital

---

## PAGE ACCESS CONTROL MATRIX

| Page | Route | Direktur | Staff | Instansi | Middleware |
|------|-------|----------|-------|----------|------------|
| Dashboard | dashboard | ✓ | ✓ | ✓ | auth |
| Upload Dokumen | upload-dokumen | 🔒 | 🔒 | ✓ | role:instansi |
| Tracking Dokumen | tracking-dokumen | 🔒 | 🔒 | ✓ | role:instansi |
| Validasi Dokumen | validasi-dokumen | ✓ | 🔒 | 🔒 | role:direktur |
| Proses Dokumen | proses-dokumen | 🔒 | ✓ | 🔒 | role:staff |
| Hasil Validasi | hasil-validasi | ✓ | ✓ | ✓ | auth |
| Arsip Digital | arsip-digital | ✓ | ✓ | 🔒 | role:direktur,staff |
| Laporan | laporan | ✓ | ✓ | ✓ | auth |
| Data Master | data-master | ✓ | 🔒 | 🔒 | role:direktur |

---

## API ENDPOINT SECURITY

```php
// DokumenController@store (Upload)
- Check: $user->isInstansi()
- Returns 403 if not

// DokumenController@validasi (Validate)
- Check: $user->isDirektur()
- Returns 403 if not

// DokumenController@proses (Process)
- Check: $user->isStaff()
- Returns 403 if not

// DokumenController@index (List)
- Direktur: Returns ALL
- Staff: WHERE status IN ('disetujui', 'diproses', 'selesai')
- Instansi: WHERE instansi_id = $user->instansi_id

// DokumenController@update (Edit)
- Check: $dokumen->user_id == $user->id
- Check: $dokumen->status == 'pending'
- Returns 403 if both fail

// DokumenController@destroy (Delete)
- Check: $dokumen->user_id == $user->id
- Check: $dokumen->status == 'pending'
- Returns 403 if both fail
```

---

## IMPLEMENTATION NOTES

### Menu Locking 🔒
- Implemented in: `resources/views/partials/sidebar-menu.blade.php`
- Logic: Check if `in_array($role, $menu['roles'])`
- If NOT: Apply class `nav-item-locked`
  - Styles: `opacity-60`, `cursor-not-allowed`, `pointer-events-none`
  - Icon: Lock SVG displayed
  - Tooltip: "Khusus [Role]" on hover
- If YES: Normal link with route

### Error Handling
- Unauthorized page access: Redirect to dashboard
- Middleware: `app/Http/Middleware/CheckRole.php`
- Behavior: `return redirect()->route('dashboard')->with('error', '🔒 Anda tidak memiliki akses...')`
- No 403 error page shown (UX improvement)

### Flash Messages
- Success: Green toast (3s auto-close)
- Error: Red toast (3s auto-close)
- Warning: Yellow toast (3s auto-close)
- Info: Blue toast (3s auto-close)
- Partial: `resources/views/partials/flash-messages.blade.php`

### Data Isolation
- Achieved via query filtering in controllers
- Not just UI (real security at database level)
- Instansi cannot see other instansi's data even with direct API call
- Example: `Dokumen::where('instansi_id', $user->instansi_id)->get();`

---

## SECURITY BEST PRACTICES IMPLEMENTED

1. ✅ **Role-based Access Control (RBAC)**
   - Middleware enforces at route level
   - Controllers double-check in methods
   - API endpoints validate user role

2. ✅ **Data Filtering**
   - All queries filtered by user role/instansi
   - Cannot access other data even with direct API

3. ✅ **CSRF Protection**
   - Form submissions use `@csrf` token
   - Logout button wrapped in form with CSRF

4. ✅ **Mass Assignment Protection**
   - Models use `$fillable` array
   - Only specified fields can be assigned

5. ✅ **File Upload Validation**
   - Max file size: 10MB
   - Stored in: `storage/app/public/dokumen/[KODE]/`
   - Not directly accessible outside storage

6. ✅ **Password Hashing**
   - All passwords hashed in migrations
   - Automatic via Eloquent `password` cast

7. ✅ **Authentication**
   - Session-based (Laravel's default)
   - Requires login for all protected routes
   - Logout clears session

---

## TESTING EACH ROLE

### Direktur Testing Checklist
- [ ] Login with direktur@yarsi-ntb.ac.id
- [ ] Dashboard shows all statistics
- [ ] "Validasi Dokumen" accessible (clickable)
- [ ] "Data Master" accessible
- [ ] "Arsip Digital" accessible
- [ ] "Upload Dokumen" locked (gray, 🔒)
- [ ] Click locked menu → nothing happens
- [ ] Validate document → status changes
- [ ] "Hasil Validasi" shows all documents
- [ ] Logout works

### Staff Testing Checklist
- [ ] Login with staff@yarsi-ntb.ac.id
- [ ] Dashboard shows only processed stats
- [ ] "Proses Dokumen" accessible
- [ ] "Arsip Digital" accessible
- [ ] "Validasi Dokumen" locked (gray, 🔒)
- [ ] "Upload Dokumen" locked
- [ ] Process document → status changes
- [ ] "Hasil Validasi" shows all documents
- [ ] Logout works

### Instansi Testing Checklist
- [ ] Login with instansi1@yarsi-ntb.ac.id
- [ ] Dashboard shows only their statistics
- [ ] "Upload Dokumen" accessible (clickable)
- [ ] "Tracking Dokumen" accessible
- [ ] Upload file successfully
- [ ] Track dokumen status shows: Menunggu
- [ ] "Validasi Dokumen" locked (gray, 🔒)
- [ ] "Arsip Digital" locked
- [ ] "Data Master" locked
- [ ] "Hasil Validasi" shows only THEIR documents
- [ ] Logout works

---

**Last Updated**: December 5, 2025  
**Version**: 1.0 (Complete Role System)
