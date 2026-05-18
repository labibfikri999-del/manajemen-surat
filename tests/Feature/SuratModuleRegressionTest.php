<?php

namespace Tests\Feature;

use App\Models\Dokumen;
use App\Models\Instansi;
use App\Models\SuratAudit;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SuratModuleRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_surat_export_route(): void
    {
        $response = $this->get('/api/surat-masuk/export/excel');

        $response->assertRedirect('/login');
    }

    public function test_updating_surat_masuk_creates_audit_log(): void
    {
        $user = User::factory()->create([
            'role' => 'staff',
        ]);

        $surat = SuratMasuk::create([
            'nomor_surat' => 'SM-001',
            'tanggal_diterima' => now()->toDateString(),
            'pengirim' => 'Pengirim Lama',
            'perihal' => 'Perihal Lama',
            'status' => 'Belum Diproses',
        ]);

        $this->actingAs($user)->putJson('/api/surat-masuk/' . $surat->id, [
            'nomor_surat' => 'SM-001',
            'tanggal_diterima' => now()->toDateString(),
            'pengirim' => 'Pengirim Lama',
            'perihal' => 'Perihal Baru',
            'status' => 'Sedang Diproses',
        ])->assertOk();

        $audit = SuratAudit::where('auditable_type', SuratMasuk::class)
            ->where('auditable_id', $surat->id)
            ->where('action', 'updated')
            ->latest()
            ->first();

        $this->assertNotNull($audit);
        $this->assertSame('Perihal Lama', $audit->old_values['perihal']);
        $this->assertSame('Perihal Baru', $audit->new_values['perihal']);
        $this->assertSame('Sedang Diproses', $audit->new_values['status']);
    }

    public function test_show_surat_keluar_returns_json_payload(): void
    {
        $user = User::factory()->create([
            'role' => 'staff',
        ]);

        $surat = SuratKeluar::create([
            'nomor_surat' => '001/UMUM/V/2026',
            'tanggal_keluar' => now()->toDateString(),
            'tujuan' => 'Tujuan Uji',
            'perihal' => 'Perihal Uji',
            'status' => 'Draft',
        ]);

        $this->actingAs($user)
            ->getJson('/api/surat-keluar/' . $surat->id)
            ->assertOk()
            ->assertJsonPath('id', $surat->id)
            ->assertJsonPath('nomor_surat', '001/UMUM/V/2026');
    }

    public function test_instansi_cannot_read_surat_masuk_from_other_instansi(): void
    {
        [$instansiA, $instansiB] = $this->makeTwoInstansis();
        $user = User::factory()->create([
            'role' => 'instansi',
            'instansi_id' => $instansiA->id,
            'module_access' => ['surat'],
        ]);

        $surat = SuratMasuk::create([
            'instansi_id' => $instansiB->id,
            'nomor_surat' => 'SM-OTHER',
            'tanggal_diterima' => now()->toDateString(),
            'pengirim' => 'Unit Lain',
            'perihal' => 'Rahasia Unit Lain',
            'status' => 'Belum Diproses',
        ]);

        $this->actingAs($user)
            ->getJson('/api/surat-masuk/'.$surat->id)
            ->assertNotFound();
    }

    public function test_instansi_cannot_access_dokumen_from_other_instansi_by_id(): void
    {
        [$instansiA, $instansiB] = $this->makeTwoInstansis();
        $userA = User::factory()->create([
            'role' => 'instansi',
            'instansi_id' => $instansiA->id,
            'module_access' => ['surat'],
        ]);
        $userB = User::factory()->create([
            'role' => 'instansi',
            'instansi_id' => $instansiB->id,
            'module_access' => ['surat'],
        ]);

        $dokumen = Dokumen::create([
            'nomor_dokumen' => 'DOC/B/202605/0001',
            'judul' => 'Dokumen Unit B',
            'file_path' => 'dokumen/B/file.pdf',
            'file_name' => 'file.pdf',
            'file_type' => 'pdf',
            'file_size' => 10,
            'user_id' => $userB->id,
            'instansi_id' => $instansiB->id,
            'status' => 'pending',
        ]);

        $this->actingAs($userA)
            ->getJson('/api/dokumen/'.$dokumen->id)
            ->assertForbidden();
    }

    public function test_instansi_cannot_manage_data_master_users(): void
    {
        $instansi = Instansi::create([
            'kode' => 'UA',
            'nama' => 'Unit A',
            'is_active' => true,
        ]);
        $user = User::factory()->create([
            'role' => 'instansi',
            'instansi_id' => $instansi->id,
            'module_access' => ['surat'],
        ]);

        $this->actingAs($user)
            ->postJson('/api/pengguna-store', [
                'name' => 'User Baru',
                'username' => 'userbaru',
                'email' => 'userbaru@example.test',
                'password' => 'password',
                'role' => 'staff',
            ])
            ->assertForbidden();
    }

    public function test_staff_cannot_download_database_backup(): void
    {
        $user = User::factory()->create([
            'role' => 'staff',
            'module_access' => ['surat'],
        ]);

        $this->actingAs($user)
            ->getJson('/api/backup/db')
            ->assertForbidden();
    }

    public function test_pengguna_list_does_not_expose_plain_password(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'module_access' => ['surat'],
        ]);
        User::factory()->create([
            'role' => 'instansi',
            'module_access' => ['surat'],
            'plain_password' => 'secret123',
        ]);

        $this->actingAs($staff)
            ->getJson('/api/pengguna-list')
            ->assertOk()
            ->assertJsonMissing(['plain_password' => 'secret123']);
    }

    public function test_arsip_category_counts_are_scoped_for_instansi(): void
    {
        [$instansiA, $instansiB] = $this->makeTwoInstansis();
        $userA = User::factory()->create([
            'role' => 'instansi',
            'instansi_id' => $instansiA->id,
            'module_access' => ['surat'],
        ]);
        $userB = User::factory()->create([
            'role' => 'instansi',
            'instansi_id' => $instansiB->id,
            'module_access' => ['surat'],
        ]);

        Dokumen::create([
            'nomor_dokumen' => 'DOC/A/202605/0001',
            'judul' => 'Arsip Unit A',
            'file_path' => 'dokumen/A/file.pdf',
            'file_name' => 'file-a.pdf',
            'file_type' => 'pdf',
            'file_size' => 10,
            'user_id' => $userA->id,
            'instansi_id' => $instansiA->id,
            'status' => 'selesai',
            'kategori_arsip' => 'UMUM',
            'is_archived' => true,
            'tanggal_arsip' => now(),
        ]);
        Dokumen::create([
            'nomor_dokumen' => 'DOC/B/202605/0001',
            'judul' => 'Arsip Unit B',
            'file_path' => 'dokumen/B/file.pdf',
            'file_name' => 'file-b.pdf',
            'file_type' => 'pdf',
            'file_size' => 10,
            'user_id' => $userB->id,
            'instansi_id' => $instansiB->id,
            'status' => 'selesai',
            'kategori_arsip' => 'UMUM',
            'is_archived' => true,
            'tanggal_arsip' => now(),
        ]);

        $this->actingAs($userA)
            ->getJson('/api/arsip-kategori-count')
            ->assertOk()
            ->assertJsonPath('UMUM', 1);
    }

    public function test_export_csv_uses_current_surat_masuk_columns(): void
    {
        $user = User::factory()->create([
            'role' => 'staff',
            'module_access' => ['surat'],
        ]);

        SuratMasuk::create([
            'nomor_surat' => 'SM-COL-001',
            'tanggal_diterima' => now()->toDateString(),
            'pengirim' => 'Pengirim CSV',
            'perihal' => 'Perihal CSV',
            'status' => 'Belum Diproses',
        ]);

        $this->actingAs($user)
            ->get('/api/export/csv')
            ->assertOk()
            ->assertSee('SM-COL-001', false);
    }

    public function test_dashboard_counts_use_unified_surat_stats_and_active_users(): void
    {
        [$instansiA] = $this->makeTwoInstansis();
        $staff = User::factory()->create([
            'role' => 'staff',
            'is_active' => true,
            'module_access' => ['surat'],
        ]);
        $unitUser = User::factory()->create([
            'role' => 'instansi',
            'instansi_id' => $instansiA->id,
            'is_active' => true,
            'module_access' => ['surat'],
        ]);
        User::factory()->create([
            'role' => 'staff',
            'is_active' => false,
            'module_access' => ['surat'],
        ]);

        Dokumen::create([
            'nomor_dokumen' => 'DOC/UA/202605/0001',
            'judul' => 'Dokumen Unit ke Pusat',
            'jenis_dokumen' => 'surat_keluar',
            'file_path' => 'dokumen/UA/a.pdf',
            'file_name' => 'a.pdf',
            'file_type' => 'pdf',
            'file_size' => 10,
            'user_id' => $unitUser->id,
            'instansi_id' => $instansiA->id,
            'status' => 'pending',
        ]);
        Dokumen::create([
            'nomor_dokumen' => 'DOC/UA/202605/0002',
            'nomor_surat' => 'SK-DUP',
            'judul' => 'Surat Pusat ke Unit',
            'jenis_dokumen' => 'surat_keluar',
            'file_path' => 'dokumen/UA/b.pdf',
            'file_name' => 'b.pdf',
            'file_type' => 'pdf',
            'file_size' => 10,
            'user_id' => $staff->id,
            'instansi_id' => $instansiA->id,
            'status' => 'selesai',
        ]);
        SuratMasuk::create([
            'nomor_surat' => 'SM-MANUAL',
            'tanggal_diterima' => now()->toDateString(),
            'pengirim' => 'Pengirim Manual',
            'perihal' => 'Surat Masuk Manual',
            'status' => 'Belum Diproses',
        ]);
        SuratKeluar::create([
            'nomor_surat' => 'SK-DUP',
            'tanggal_keluar' => now()->toDateString(),
            'tujuan' => 'Unit A',
            'perihal' => 'Duplikat Dokumen',
            'status' => 'Terkirim',
        ]);
        SuratKeluar::create([
            'nomor_surat' => 'SK-MANUAL',
            'tanggal_keluar' => now()->toDateString(),
            'tujuan' => 'Tujuan Manual',
            'perihal' => 'Surat Keluar Manual',
            'status' => 'Draft',
        ]);

        $this->actingAs($staff)
            ->getJson('/api/surat-masuk/count')
            ->assertOk()
            ->assertJsonPath('count', 2);

        $this->actingAs($staff)
            ->getJson('/api/surat-keluar/count')
            ->assertOk()
            ->assertJsonPath('count', 3);

        $this->actingAs($staff)
            ->getJson('/api/pengguna-aktif')
            ->assertOk()
            ->assertJsonPath('count', 2);
    }

    public function test_instansi_dashboard_counts_follow_unit_perspective(): void
    {
        [$instansiA] = $this->makeTwoInstansis();
        $staff = User::factory()->create([
            'role' => 'staff',
            'module_access' => ['surat'],
        ]);
        $unitUser = User::factory()->create([
            'role' => 'instansi',
            'instansi_id' => $instansiA->id,
            'module_access' => ['surat'],
        ]);

        Dokumen::create([
            'nomor_dokumen' => 'DOC/UA/202605/0101',
            'judul' => 'Dari Pusat ke Unit',
            'jenis_dokumen' => 'surat_keluar',
            'file_path' => 'dokumen/UA/pusat.pdf',
            'file_name' => 'pusat.pdf',
            'file_type' => 'pdf',
            'file_size' => 10,
            'user_id' => $staff->id,
            'instansi_id' => $instansiA->id,
            'status' => 'selesai',
        ]);
        Dokumen::create([
            'nomor_dokumen' => 'DOC/UA/202605/0102',
            'judul' => 'Dari Unit ke Pusat',
            'jenis_dokumen' => 'surat_masuk',
            'file_path' => 'dokumen/UA/unit.pdf',
            'file_name' => 'unit.pdf',
            'file_type' => 'pdf',
            'file_size' => 10,
            'user_id' => $unitUser->id,
            'instansi_id' => $instansiA->id,
            'status' => 'pending',
        ]);
        SuratMasuk::create([
            'instansi_id' => $instansiA->id,
            'nomor_surat' => 'SM-UNIT',
            'tanggal_diterima' => now()->toDateString(),
            'pengirim' => 'Pusat',
            'perihal' => 'Manual Masuk Unit',
            'status' => 'Belum Diproses',
        ]);
        SuratKeluar::create([
            'instansi_id' => $instansiA->id,
            'nomor_surat' => 'DOC/UA/202605/0102',
            'tanggal_keluar' => now()->toDateString(),
            'tujuan' => 'Pusat',
            'perihal' => 'Duplikat Keluar Unit',
            'status' => 'Terkirim',
        ]);

        $this->actingAs($unitUser)
            ->getJson('/api/surat-masuk/count')
            ->assertOk()
            ->assertJsonPath('count', 2);

        $this->actingAs($unitUser)
            ->getJson('/api/surat-keluar/count')
            ->assertOk()
            ->assertJsonPath('count', 1);
    }

    public function test_dokumen_upload_accepts_common_file_types(): void
    {
        Storage::fake('public');
        $instansi = Instansi::create([
            'kode' => 'UA',
            'nama' => 'Unit A',
            'is_active' => true,
        ]);
        $user = User::factory()->create([
            'role' => 'instansi',
            'instansi_id' => $instansi->id,
            'module_access' => ['surat'],
        ]);

        foreach ([
            ['surat.png', 'image/png'],
            ['surat.pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'],
            ['surat.txt', 'text/plain'],
        ] as [$name, $mime]) {
            $this->actingAs($user)
                ->postJson('/api/dokumen', [
                    'judul' => 'Upload '.$name,
                    'jenis' => 'surat_masuk',
                    'file' => UploadedFile::fake()->create($name, 1, $mime),
                ])
                ->assertCreated();
        }
    }

    public function test_dokumen_upload_rejects_unsupported_file_types(): void
    {
        Storage::fake('public');
        $instansi = Instansi::create([
            'kode' => 'UA',
            'nama' => 'Unit A',
            'is_active' => true,
        ]);
        $user = User::factory()->create([
            'role' => 'instansi',
            'instansi_id' => $instansi->id,
            'module_access' => ['surat'],
        ]);

        $this->actingAs($user)
            ->postJson('/api/dokumen', [
                'judul' => 'Upload EXE',
                'jenis' => 'surat_masuk',
                'file' => UploadedFile::fake()->create('malware.exe', 1, 'application/x-msdownload'),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('file');
    }

    public function test_role_pages_render_for_available_surat_menus(): void
    {
        $instansi = Instansi::create([
            'kode' => 'UA',
            'nama' => 'Unit A',
            'is_active' => true,
        ]);

        $direktur = User::factory()->create([
            'role' => 'direktur',
            'module_access' => ['surat'],
        ]);
        $staff = User::factory()->create([
            'role' => 'staff',
            'module_access' => ['surat'],
        ]);
        $unit = User::factory()->create([
            'role' => 'instansi',
            'instansi_id' => $instansi->id,
            'module_access' => ['surat'],
        ]);

        foreach ([
            [$direktur, ['/dashboard', '/validasi-dokumen', '/data-master', '/arsip-dokumen', '/hasil-validasi', '/laporan']],
            [$staff, ['/dashboard', '/upload-dokumen', '/proses-dokumen', '/buat-surat', '/data-master', '/arsip-dokumen', '/hasil-validasi', '/laporan']],
            [$unit, ['/dashboard', '/upload-dokumen', '/tracking-dokumen', '/surat-masuk', '/surat-keluar', '/hasil-validasi', '/laporan']],
        ] as [$user, $paths]) {
            foreach ($paths as $path) {
                $this->actingAs($user)
                    ->get($path)
                    ->assertOk();
            }
        }
    }

    public function test_staff_without_surat_module_cannot_open_upload_dokumen_page(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'module_access' => ['kepegawaian'],
        ]);

        $this->actingAs($staff)
            ->get('/upload-dokumen')
            ->assertForbidden();
    }

    private function makeTwoInstansis(): array
    {
        return [
            Instansi::create([
                'kode' => 'UA',
                'nama' => 'Unit A',
                'is_active' => true,
            ]),
            Instansi::create([
                'kode' => 'UB',
                'nama' => 'Unit B',
                'is_active' => true,
            ]),
        ];
    }
}
