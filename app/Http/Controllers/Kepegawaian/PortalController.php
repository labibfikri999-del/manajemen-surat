<?php

namespace App\Http\Controllers\Kepegawaian;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Models\SDM\SdmPegawai;
use App\Models\SdmKategoriDokumen;
use App\Models\SdmTransaksiDokumen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PortalController extends Controller
{
    private array $defaultCategories = [
        ['kode' => 'KTP_KK', 'nama' => 'KTP dan Kartu Keluarga'],
        ['kode' => 'IJAZAH', 'nama' => 'Ijazah Terakhir'],
        ['kode' => 'SK_PENGANGKATAN', 'nama' => 'SK Pengangkatan'],
        ['kode' => 'SK_JABATAN', 'nama' => 'SK Jabatan'],
        ['kode' => 'SERTIFIKAT', 'nama' => 'Sertifikat Kompetensi'],
        ['kode' => 'LAINNYA', 'nama' => 'Dokumen Pendukung Lainnya'],
    ];

    private array $checklistItems = [
        'nama_dokumen_jelas' => 'Nama dokumen jelas',
        'file_dapat_dibuka' => 'File dapat dibuka',
        'data_terbaca_lengkap' => 'Data terbaca lengkap',
        'kategori_sesuai' => 'Kategori sesuai',
        'tidak_duplikat' => 'Tidak duplikat',
        'ukuran_file_aman' => 'Ukuran file aman',
    ];

    private function tablesReady(): bool
    {
        return Schema::hasTable('sdm_kategori_dokumens')
            && Schema::hasTable('sdm_transaksi_dokumens')
            && Schema::hasTable('password_reset_requests');
    }

    private function documentsReady(): bool
    {
        return Schema::hasTable('sdm_kategori_dokumens')
            && Schema::hasTable('sdm_transaksi_dokumens');
    }

    private function userCanManageDocuments(?User $user = null): bool
    {
        $user = $user ?: Auth::user();

        return $user && in_array($user->role, ['staff', 'staff_kepegawaian', 'direktur', 'sekjen']);
    }

    private function userCanManageAccounts(?User $user = null): bool
    {
        $user = $user ?: Auth::user();

        return $user && in_array($user->role, ['staff', 'staff_kepegawaian']);
    }

    private function isPegawaiOnly(?User $user = null): bool
    {
        $user = $user ?: Auth::user();

        return $user && $user->role === 'pegawai';
    }

    private function canViewDocument(SdmTransaksiDokumen $document, ?User $user = null): bool
    {
        $user = $user ?: Auth::user();
        if (!$user) {
            return false;
        }

        if ($this->userCanManageDocuments($user)) {
            return true;
        }

        return (int) $document->user_id === (int) $user->id
            || (int) optional($document->pegawai)->user_id === (int) $user->id;
    }

    private function ensureDefaultCategories(): void
    {
        if (!Schema::hasTable('sdm_kategori_dokumens')) {
            return;
        }

        foreach ($this->defaultCategories as $index => $category) {
            $defaults = ['nama' => $category['nama'], 'sort_order' => $index + 1, 'is_active' => true];
            if (Schema::hasColumn('sdm_kategori_dokumens', 'nama_kategori')) {
                $defaults['nama_kategori'] = $category['nama'];
            }

            SdmKategoriDokumen::firstOrCreate(
                ['kode' => $category['kode']],
                $defaults
            );
        }
    }

    private function currentPegawai(): ?SdmPegawai
    {
        if (!Auth::check() || !Schema::hasTable('sdm_pegawais')) {
            return null;
        }

        return SdmPegawai::where('user_id', Auth::id())->first();
    }

    private function fallbackDocuments(): array
    {
        return [
            ['id' => 0, 'pegawai' => 'Budi Santoso', 'nip' => '198801012014041001', 'unit' => 'Tata Usaha', 'kategori' => 'Ijazah Terakhir', 'status' => 'diperiksa_staff', 'status_label' => 'Diperiksa Staff', 'tanggal' => '10 Mei 2026', 'file_path' => null],
            ['id' => 1, 'pegawai' => 'Siti Aminah', 'nip' => '199002022015032001', 'unit' => 'Keuangan', 'kategori' => 'SK Pengangkatan', 'status' => 'perlu_revisi', 'status_label' => 'Perlu Revisi', 'tanggal' => '09 Mei 2026', 'file_path' => null],
            ['id' => 2, 'pegawai' => 'Ahmad Fauzi', 'nip' => '199503032019011002', 'unit' => 'Akademik', 'kategori' => 'Sertifikat Kompetensi', 'status' => 'menunggu_sekjen', 'status_label' => 'Menunggu Sekjen', 'tanggal' => '08 Mei 2026', 'file_path' => null],
            ['id' => 3, 'pegawai' => 'Nur Fadilah', 'nip' => '199704042020012003', 'unit' => 'SDM', 'kategori' => 'KTP dan KK', 'status' => 'disetujui', 'status_label' => 'Disetujui', 'tanggal' => '07 Mei 2026', 'file_path' => null],
        ];
    }

    private function formatDocument(SdmTransaksiDokumen $document): array
    {
        $pegawai = $document->pegawai;
        $user = $document->user;

        return [
            'id' => $document->id,
            'pegawai' => $pegawai->name ?? $user->name ?? 'Pegawai',
            'nip' => $pegawai->nip ?? $user->username ?? '-',
            'unit' => $pegawai->unit_kerja ?? $pegawai->role ?? '-',
            'judul' => $document->judul,
            'kategori' => ($document->kategori ?? $document->kategoriLegacy)?->nama ?? '-',
            'status' => $document->status,
            'status_label' => $document->status_label,
            'tanggal' => optional($document->submitted_at ?? $document->created_at)->format('d M Y'),
            'file_path' => $document->file_path,
            'catatan_pegawai' => $document->catatan_pegawai,
            'catatan_staff' => $document->catatan_staff,
            'catatan_sekjen' => $document->catatan_sekjen,
        ];
    }

    private function documentRows(?array $statuses = null): array
    {
        if (!$this->documentsReady()) {
            $fallback = $this->fallbackDocuments();

            return $statuses ? array_values(array_filter($fallback, fn ($row) => in_array($row['status'], $statuses))) : $fallback;
        }

        $query = SdmTransaksiDokumen::with(['user', 'pegawai', 'kategori', 'kategoriLegacy'])->latest();

        if ($statuses) {
            $legacyStatuses = collect($statuses)->map(fn ($status) => match ($status) {
                'diajukan', 'diperiksa_staff' => 'Pending',
                'menunggu_sekjen' => 'Reviewed',
                'disetujui', 'diarsipkan' => 'Approved',
                'ditolak' => 'Rejected',
                default => null,
            })->filter()->unique()->values()->all();

            $query->where(function ($query) use ($statuses, $legacyStatuses) {
                $query->whereIn('status', $statuses);
                if ($legacyStatuses) {
                    $query->orWhereIn('status', $legacyStatuses);
                }
            });
        }

        $user = Auth::user();
        if ($this->isPegawaiOnly($user)) {
            $query->where('user_id', $user->id);
        }

        return $query->take(30)->get()->map(fn ($document) => $this->formatDocument($document))->all();
    }

    public function dashboard()
    {
        $this->ensureDefaultCategories();

        if ($this->documentsReady()) {
            $baseQuery = SdmTransaksiDokumen::query();
            if ($this->isPegawaiOnly()) {
                $baseQuery->where('user_id', Auth::id());
            }

            $stats = [
                'pegawai' => $this->isPegawaiOnly()
                    ? 1
                    : (Schema::hasTable('sdm_pegawais') ? SdmPegawai::where('status', 'active')->count() : User::where('role', 'pegawai')->count()),
                'diajukan' => (clone $baseQuery)->whereIn('status', ['diajukan', 'diperiksa_staff', 'Pending'])->count(),
                'revisi' => (clone $baseQuery)->where('status', 'perlu_revisi')->count(),
                'menunggu_sekjen' => (clone $baseQuery)->whereIn('status', ['menunggu_sekjen', 'Reviewed'])->count(),
            ];
        } else {
            $stats = ['pegawai' => 426, 'diajukan' => 38, 'revisi' => 7, 'menunggu_sekjen' => 12];
        }

        $documents = $this->documentRows();

        return view('kepegawaian.dashboard', compact('stats', 'documents'));
    }

    public function upload()
    {
        $this->ensureDefaultCategories();

        $categories = Schema::hasTable('sdm_kategori_dokumens')
            ? SdmKategoriDokumen::where('is_active', true)->orderBy('sort_order')->get()
            : collect($this->defaultCategories)->map(fn ($category, $index) => (object) ['id' => $index + 1, 'nama' => $category['nama']]);

        $documents = $this->documentRows();
        $checklistItems = $this->checklistItems;

        return view('kepegawaian.upload', compact('categories', 'documents', 'checklistItems'));
    }

    public function storeUpload(Request $request)
    {
        $this->ensureDefaultCategories();
        if (!$this->documentsReady()) {
            return redirect()->route('kepegawaian.upload')->with('error', 'Tabel dokumen kepegawaian belum siap. Jalankan migrasi database terlebih dahulu.');
        }

        $action = $request->input('action', 'submit');

        $validated = $request->validate([
            'kategori' => ['required', 'exists:sdm_kategori_dokumens,id'],
            'judul' => ['required', 'string', 'max:255'],
            'catatan' => ['nullable', 'string', 'max:1000'],
            'file' => [$action === 'submit' ? 'required' : 'nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,jpg,jpeg,png'],
            'checklist' => [$action === 'submit' ? 'required' : 'nullable', 'array'],
            ...collect(array_keys($this->checklistItems))
                ->mapWithKeys(fn ($key) => ["checklist.{$key}" => [$action === 'submit' ? 'accepted' : 'nullable']])
                ->all(),
        ], [
            'checklist.required' => 'Checklist sebelum kirim wajib dicentang semua.',
            'checklist.*.accepted' => 'Checklist sebelum kirim wajib dicentang semua.',
        ]);

        $fileData = [];
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('kepegawaian/dokumen', 'public');
            $fileData = [
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
            ];
        }

        $pegawai = $this->currentPegawai();

        $data = [
            'user_id' => Auth::id(),
            'sdm_pegawai_id' => $pegawai?->id,
            'sdm_kategori_dokumen_id' => $validated['kategori'],
            'nomor_pengajuan' => 'KPG-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4)),
            'judul' => $validated['judul'],
            'catatan_pegawai' => $validated['catatan'] ?? null,
            'status' => $action === 'draft' ? 'draft' : 'diajukan',
            'submitted_at' => $action === 'draft' ? null : now(),
        ];

        if (Schema::hasColumn('sdm_transaksi_dokumens', 'pengirim_type')) {
            $data['pengirim_type'] = User::class;
        }
        if (Schema::hasColumn('sdm_transaksi_dokumens', 'pengirim_id')) {
            $data['pengirim_id'] = Auth::id();
        }
        if (Schema::hasColumn('sdm_transaksi_dokumens', 'penerima_type')) {
            $data['penerima_type'] = User::class;
        }
        if (Schema::hasColumn('sdm_transaksi_dokumens', 'kategori_id')) {
            $data['kategori_id'] = $validated['kategori'];
        }
        if (Schema::hasColumn('sdm_transaksi_dokumens', 'judul_dokumen')) {
            $data['judul_dokumen'] = $validated['judul'];
        }
        if (Schema::hasColumn('sdm_transaksi_dokumens', 'deskripsi')) {
            $data['deskripsi'] = $validated['catatan'] ?? null;
        }

        SdmTransaksiDokumen::create(array_merge($data, $fileData));

        $message = $action === 'draft'
            ? 'Draft dokumen berhasil disimpan.'
            : 'Dokumen berhasil dikirim ke antrean verifikasi staff.';

        return redirect()->route('kepegawaian.upload')->with('success', $message);
    }

    public function verifikasi()
    {
        $documents = $this->documentRows(['diajukan', 'diperiksa_staff', 'perlu_revisi', 'menunggu_sekjen']);

        return view('kepegawaian.verifikasi', compact('documents'));
    }

    public function verifikasiAction(Request $request, int $document)
    {
        $validated = $request->validate([
            'action' => ['required', 'in:preview,forward,revise,reject,panel'],
            'keputusan' => ['nullable', 'string', 'max:100'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $model = $this->documentsReady() ? SdmTransaksiDokumen::find($document) : null;
        $name = $model?->pegawai?->name ?? $model?->user?->name ?? 'pegawai';

        if ($validated['action'] === 'preview') {
            return redirect()->route('kepegawaian.verifikasi')->with('info', 'Preview dokumen '.$name.' siap ditampilkan.');
        }

        if ($model) {
            $nextStatus = match ($validated['action']) {
                'forward' => 'menunggu_sekjen',
                'revise' => 'perlu_revisi',
                'reject' => 'ditolak',
                'panel' => str_contains(strtolower($validated['keputusan'] ?? ''), 'revisi') ? 'perlu_revisi' : (str_contains(strtolower($validated['keputusan'] ?? ''), 'tolak') ? 'ditolak' : 'menunggu_sekjen'),
            };

            $model->update([
                'status' => $nextStatus,
                'catatan_staff' => $validated['catatan'] ?? $model->catatan_staff,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);
        }

        return redirect()->route('kepegawaian.verifikasi')->with('success', 'Keputusan pemeriksaan untuk '.$name.' berhasil disimpan.');
    }

    public function verifikasiPanelAction(Request $request)
    {
        $validated = $request->validate([
            'document_id' => ['required', 'integer'],
            'keputusan' => ['required', 'string', 'max:100'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $request->merge(['action' => 'panel']);

        return $this->verifikasiAction($request, (int) $validated['document_id']);
    }

    public function persetujuan()
    {
        $documents = $this->documentRows(['menunggu_sekjen']);
        if ($this->documentsReady()) {
            $stats = [
                'menunggu' => SdmTransaksiDokumen::whereIn('status', ['menunggu_sekjen', 'Reviewed'])->count(),
                'disetujui_bulan_ini' => SdmTransaksiDokumen::whereIn('status', ['disetujui', 'Approved'])->whereMonth('approved_at', now()->month)->whereYear('approved_at', now()->year)->count(),
                'ditolak_bulan_ini' => SdmTransaksiDokumen::whereIn('status', ['ditolak', 'Rejected'])->whereMonth('approved_at', now()->month)->whereYear('approved_at', now()->year)->count(),
            ];
        } else {
            $stats = ['menunggu' => 12, 'disetujui_bulan_ini' => 46, 'ditolak_bulan_ini' => 0];
        }

        return view('kepegawaian.persetujuan', compact('documents', 'stats'));
    }

    public function persetujuanAction(Request $request, int $document)
    {
        $validated = $request->validate([
            'action' => ['required', 'in:preview,approve,reject'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $model = $this->documentsReady() ? SdmTransaksiDokumen::find($document) : null;
        $name = $model?->pegawai?->name ?? $model?->user?->name ?? 'pegawai';

        if ($validated['action'] === 'preview') {
            return redirect()->route('kepegawaian.persetujuan')->with('info', 'Preview final dokumen '.$name.' siap ditampilkan.');
        }

        if ($model) {
            $model->update([
                'status' => $validated['action'] === 'approve' ? 'disetujui' : 'ditolak',
                'catatan_sekjen' => $validated['catatan'] ?? $model->catatan_sekjen,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'archived_at' => $validated['action'] === 'approve' ? now() : null,
            ]);
        }

        $message = $validated['action'] === 'approve'
            ? 'Dokumen '.$name.' disetujui dan masuk arsip kepegawaian.'
            : 'Dokumen '.$name.' ditolak oleh Sekjen.';

        return redirect()->route('kepegawaian.persetujuan')->with($validated['action'] === 'approve' ? 'success' : 'error', $message);
    }

    public function downloadDocument(int $document)
    {
        $model = SdmTransaksiDokumen::findOrFail($document);

        abort_unless($this->canViewDocument($model), 403);
        abort_unless($model->file_path && Storage::disk('public')->exists($model->file_path), 404);

        return Storage::disk('public')->download($model->file_path, $model->file_name);
    }

    public function akun()
    {
        $accounts = User::query()
            ->latest()
            ->get()
            ->filter(fn ($user) => in_array('kepegawaian', $user->module_access ?? []) || in_array('sdm', $user->module_access ?? []) || in_array($user->role, ['pegawai', 'staff_kepegawaian', 'sekjen']))
            ->map(fn ($user) => [
                'id' => $user->id,
                'nama' => $user->name,
                'username' => $user->username,
                'role' => $user->role_label,
                'status' => $user->must_change_password ? 'Wajib Ganti Password' : ($user->is_active ? 'Aktif' : 'Nonaktif'),
                'akses' => implode(', ', $user->module_access ?? []),
                'is_active' => (bool) $user->is_active,
            ])
            ->values()
            ->all();

        return view('kepegawaian.akun', compact('accounts'));
    }

    public function akunAction(Request $request)
    {
        abort_unless($this->userCanManageAccounts(), 403);

        $action = $request->input('action');

        if ($action === 'add') {
            $validated = $request->validate([
                'nama' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:100', 'unique:users,username'],
                'email' => ['required', 'email', 'max:100', 'unique:users,email'],
                'role' => ['required', 'in:pegawai,staff_kepegawaian,sekjen'],
            ]);

            $password = 'Kpg-'.Str::upper(Str::random(8));
            $user = User::create([
                'name' => $validated['nama'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'role' => $validated['role'],
                'module_access' => ['kepegawaian'],
                'is_active' => true,
                'must_change_password' => true,
            ]);

            if ($validated['role'] === 'pegawai' && Schema::hasTable('sdm_pegawais')) {
                SdmPegawai::updateOrCreate(
                    ['nip' => $validated['username']],
                    ['user_id' => $user->id, 'name' => $validated['nama'], 'email' => $validated['email'], 'role' => 'Pegawai', 'status' => 'active']
                );
            }

            return redirect()->route('kepegawaian.akun')->with('success', 'Akun dibuat. Password sementara: '.$password);
        }

        if ($action === 'import') {
            return $this->importAccounts($request);
        }

        if ($action === 'generate') {
            $passwordRows = [];
            $users = User::where('role', 'pegawai')
                ->where(function ($query) {
                    $query->whereJsonContains('module_access', 'kepegawaian')
                        ->orWhereJsonContains('module_access', 'pegawai')
                        ->orWhereJsonContains('module_access', 'sdm');
                })
                ->get();

            foreach ($users as $user) {
                $password = 'Kpg-'.Str::upper(Str::random(8));
                $user->update(['password' => Hash::make($password), 'must_change_password' => true]);
                $passwordRows[] = ['nama' => $user->name, 'username' => $user->username, 'password' => $password];
            }

            return redirect()->route('kepegawaian.akun')->with('success', count($passwordRows).' password sementara berhasil dibuat.')->with('generated_passwords', $passwordRows);
        }

        if ($action === 'reset') {
            $user = User::findOrFail($request->input('user_id'));
            $password = 'Kpg-'.Str::upper(Str::random(8));
            $user->update(['password' => Hash::make($password), 'must_change_password' => true]);

            return redirect()->route('kepegawaian.akun')->with('success', 'Password sementara '.$user->name.': '.$password);
        }

        if ($action === 'toggle') {
            $user = User::findOrFail($request->input('user_id'));
            if ((int) $user->id === (int) Auth::id()) {
                return redirect()->route('kepegawaian.akun')->with('error', 'Akun yang sedang dipakai tidak bisa dinonaktifkan dari halaman ini.');
            }

            $user->update(['is_active' => ! $user->is_active]);
            $message = $user->is_active ? 'Akun '.$user->name.' diaktifkan.' : 'Akun '.$user->name.' dinonaktifkan.';

            return redirect()->route('kepegawaian.akun')->with('success', $message);
        }

        return redirect()->route('kepegawaian.akun')->with('error', 'Aksi akun tidak dikenali.');
    }

    private function importAccounts(Request $request)
    {
        $request->validate([
            'template' => ['required', 'file', 'max:4096', 'mimes:xlsx,xls,csv,txt'],
        ]);

        $rows = $this->readSpreadsheetRows($request->file('template')->getPathname());
        if (empty($rows)) {
            return redirect()->route('kepegawaian.akun')->with('error', 'Template kosong atau tidak bisa dibaca.');
        }

        $created = 0;
        $errors = [];
        $passwordRows = [];

        foreach ($rows as $index => $row) {
            $nama = trim($row['nama'] ?? $row['Nama'] ?? '');
            $nip = trim((string) ($row['nip'] ?? $row['NIP'] ?? ''));
            $email = trim($row['email'] ?? $row['Email'] ?? '');
            $role = strtolower(trim($row['role'] ?? $row['Role'] ?? 'pegawai') ?: 'pegawai');
            $role = in_array($role, ['pegawai', 'staff_kepegawaian', 'sekjen'], true) ? $role : 'pegawai';

            if ($nama === '' || $nip === '') {
                continue;
            }

            $email = $email !== '' ? $email : $nip.'@pegawai.local';
            $emailOwner = User::where('email', $email)->where('username', '!=', $nip)->first();
            if ($emailOwner) {
                $errors[] = 'Baris '.($index + 2).': email '.$email.' sudah dipakai akun '.$emailOwner->username.'.';
                continue;
            }

            $password = 'Kpg-'.Str::upper(Str::random(8));

            $user = User::updateOrCreate(
                ['username' => $nip],
                [
                    'name' => $nama,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role' => $role,
                    'module_access' => ['kepegawaian'],
                    'is_active' => true,
                    'must_change_password' => true,
                ]
            );

            if (Schema::hasTable('sdm_pegawais')) {
                SdmPegawai::updateOrCreate(
                    ['nip' => $nip],
                    ['user_id' => $user->id, 'name' => $nama, 'email' => $email, 'role' => $row['jabatan'] ?? $row['Jabatan'] ?? 'Pegawai', 'unit_kerja' => $row['unit_kerja'] ?? $row['Unit Kerja'] ?? null, 'status' => 'active']
                );
            }

            $created++;
            $passwordRows[] = ['nama' => $nama, 'username' => $nip, 'password' => $password];
        }

        $redirect = redirect()->route('kepegawaian.akun')
            ->with($created > 0 ? 'success' : 'error', $created.' akun berhasil diproses.')
            ->with('generated_passwords', $passwordRows);

        return $errors ? $redirect->with('import_errors', $errors) : $redirect;
    }

    private function readSpreadsheetRows(string $path): array
    {
        if ($xlsx = \Shuchkin\SimpleXLSX::parse($path)) {
            $rows = $xlsx->rows();
            $headers = array_map(fn ($value) => strtolower(trim(strip_tags((string) $value))), array_shift($rows) ?? []);

            return array_values(array_filter(array_map(function ($row) use ($headers) {
                $row = array_pad($row, count($headers), null);
                return array_combine($headers, array_slice($row, 0, count($headers)));
            }, $rows)));
        }

        $handle = fopen($path, 'r');
        if (!$handle) {
            return [];
        }

        $firstLine = fgets($handle);
        $delimiter = substr_count((string) $firstLine, ';') > substr_count((string) $firstLine, ',') ? ';' : ',';
        rewind($handle);

        $bom = fread($handle, 3);
        if ($bom !== chr(0xEF).chr(0xBB).chr(0xBF)) {
            rewind($handle);
        }

        $headers = null;
        $rows = [];
        while (($row = fgetcsv($handle, 2000, $delimiter)) !== false) {
            if (!$headers) {
                $headers = array_map(fn ($value) => strtolower(trim(strip_tags((string) $value))), $row);
                continue;
            }
            $row = array_pad($row, count($headers), null);
            $rows[] = array_combine($headers, array_slice($row, 0, count($headers)));
        }
        fclose($handle);

        return $rows;
    }

    public function resetPassword()
    {
        $requests = $this->tablesReady()
            ? PasswordResetRequest::with(['user', 'pegawai'])->latest()->take(30)->get()->map(fn ($request) => [
                'id' => $request->id,
                'pegawai' => $request->pegawai->name ?? $request->user->name ?? 'Pegawai',
                'nip' => $request->nip,
                'kontak' => $request->kontak,
                'status' => $request->status_label,
                'waktu' => $request->created_at->format('d M Y H:i'),
            ])->all()
            : [
                ['id' => 0, 'pegawai' => 'Siti Aminah', 'nip' => '199002022015032001', 'kontak' => '0812-4488-1090', 'status' => 'Menunggu Verifikasi', 'waktu' => '10 Mei 2026 09:20'],
            ];

        return view('kepegawaian.reset-password', compact('requests'));
    }

    public function resetPasswordAction(Request $request, int $resetRequest)
    {
        abort_unless($this->userCanManageAccounts(), 403);

        $validated = $request->validate([
            'action' => ['required', 'in:verify,temp_password,reject'],
        ]);

        $model = PasswordResetRequest::findOrFail($resetRequest);

        if ($validated['action'] === 'verify') {
            $model->update(['status' => 'terverifikasi', 'verified_by' => Auth::id(), 'verified_at' => now()]);
            return redirect()->route('kepegawaian.reset-password')->with('success', 'Permintaan reset berhasil diverifikasi.');
        }

        if ($validated['action'] === 'reject') {
            $model->update(['status' => 'ditolak', 'verified_by' => Auth::id(), 'verified_at' => now()]);
            return redirect()->route('kepegawaian.reset-password')->with('error', 'Permintaan reset password ditolak.');
        }

        $user = $model->user ?? $model->pegawai?->user ?? User::where('username', $model->nip)->first();
        if (!$user) {
            return redirect()->route('kepegawaian.reset-password')->with('error', 'User untuk NIP '.$model->nip.' tidak ditemukan.');
        }

        $password = 'Kpg-'.Str::upper(Str::random(8));
        $user->update(['password' => Hash::make($password), 'must_change_password' => true]);
        $model->update(['status' => 'password_sementara_dibuat', 'completed_at' => now(), 'verified_by' => Auth::id(), 'verified_at' => $model->verified_at ?? now()]);

        return redirect()->route('kepegawaian.reset-password')->with('success', 'Password sementara '.$user->name.': '.$password);
    }

    public function forgotPassword()
    {
        return view('kepegawaian.auth.forgot-password');
    }

    public function requestPasswordReset(Request $request)
    {
        if (!Schema::hasTable('password_reset_requests')) {
            return redirect()->route('kepegawaian.login')->with('error', 'Fitur reset password belum siap. Jalankan migrasi database terlebih dahulu.');
        }

        $validated = $request->validate([
            'nip' => ['required', 'string', 'max:50'],
            'kontak' => ['required', 'string', 'max:100'],
            'alasan' => ['nullable', 'string', 'max:500'],
        ]);

        $user = User::where('username', $validated['nip'])->first();
        $pegawai = Schema::hasTable('sdm_pegawais') ? SdmPegawai::where('nip', $validated['nip'])->first() : null;

        PasswordResetRequest::updateOrCreate(
            ['nip' => $validated['nip'], 'status' => 'menunggu_verifikasi'],
            [
                'user_id' => $user?->id,
                'sdm_pegawai_id' => $pegawai?->id,
                'kontak' => $validated['kontak'],
                'alasan' => $validated['alasan'] ?? null,
            ]
        );

        return redirect()->route('kepegawaian.login')->with('success', 'Permintaan reset password untuk NIP '.$validated['nip'].' sudah dikirim ke Staff Kepegawaian.');
    }

    public function changePasswordForm()
    {
        return view('kepegawaian.auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ]);

        return redirect()->route('kepegawaian.dashboard')->with('success', 'Password berhasil diganti.');
    }

    public function exportVerifikasi(): StreamedResponse
    {
        $documents = $this->documentRows();

        return response()->streamDownload(function () use ($documents) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Pegawai', 'NIP', 'Unit', 'Kategori', 'Status', 'Tanggal']);
            foreach ($documents as $row) {
                fputcsv($handle, [$row['pegawai'], $row['nip'], $row['unit'], $row['kategori'], $row['status_label'], $row['tanggal']]);
            }
            fclose($handle);
        }, 'verifikasi-dokumen-kepegawaian.csv', ['Content-Type' => 'text/csv']);
    }

    public function downloadAccountTemplate(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['nama', 'nip', 'email', 'unit_kerja', 'jabatan', 'role']);
            fputcsv($handle, ['Budi Santoso', '198801012014041001', 'budi@yarsi-ntb.ac.id', 'Tata Usaha', 'Staff Administrasi', 'pegawai']);
            fclose($handle);
        }, 'template-akun-pegawai.csv', ['Content-Type' => 'text/csv']);
    }
}
