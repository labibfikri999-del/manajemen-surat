<?php

namespace Tests\Feature;

use App\Models\SDM\SdmPegawai;
use App\Models\SdmKategoriDokumen;
use App\Models\SdmTransaksiDokumen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class KepegawaianPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_pegawai_tidak_bisa_download_dokumen_pegawai_lain(): void
    {
        $owner = User::factory()->create([
            'username' => '198801012014041001',
            'role' => 'pegawai',
            'module_access' => ['kepegawaian'],
            'is_active' => true,
        ]);
        $other = User::factory()->create([
            'username' => '199002022015032001',
            'role' => 'pegawai',
            'module_access' => ['kepegawaian'],
            'is_active' => true,
        ]);

        $category = SdmKategoriDokumen::create([
            'kode' => 'IJAZAH',
            'nama' => 'Ijazah Terakhir',
            'is_active' => true,
        ]);

        Storage::disk('public')->put('kepegawaian/dokumen/ijazah.pdf', 'dummy');

        $document = SdmTransaksiDokumen::create([
            'user_id' => $owner->id,
            'sdm_kategori_dokumen_id' => $category->id,
            'nomor_pengajuan' => 'KPG-TEST-001',
            'judul' => 'Ijazah',
            'file_path' => 'kepegawaian/dokumen/ijazah.pdf',
            'file_name' => 'ijazah.pdf',
            'status' => 'diajukan',
            'submitted_at' => now(),
        ]);

        $this->actingAs($other)
            ->get(route('kepegawaian.dokumen.download', $document->id))
            ->assertForbidden();
    }

    public function test_preview_dokumen_kepegawaian_ditampilkan_inline(): void
    {
        $staff = User::factory()->create([
            'username' => 'staff.preview',
            'role' => 'staff_kepegawaian',
            'module_access' => ['kepegawaian'],
            'is_active' => true,
        ]);
        $pegawai = User::factory()->create([
            'username' => '198801012014041002',
            'role' => 'pegawai',
            'module_access' => ['kepegawaian'],
            'is_active' => true,
        ]);
        $category = SdmKategoriDokumen::create([
            'kode' => 'PREVIEW',
            'nama' => 'Dokumen Preview',
            'is_active' => true,
        ]);

        Storage::disk('public')->put('kepegawaian/dokumen/preview.pdf', 'dummy pdf');

        $document = SdmTransaksiDokumen::create([
            'user_id' => $pegawai->id,
            'sdm_kategori_dokumen_id' => $category->id,
            'nomor_pengajuan' => 'KPG-PREVIEW-001',
            'judul' => 'Preview PDF',
            'file_path' => 'kepegawaian/dokumen/preview.pdf',
            'file_name' => 'preview.pdf',
            'status' => 'diajukan',
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($staff)
            ->get(route('kepegawaian.dokumen.preview', $document->id));

        $response->assertOk();
        $this->assertStringContainsString('inline', $response->headers->get('content-disposition'));
    }

    public function test_preview_link_muncul_di_verifikasi_dan_persetujuan(): void
    {
        $staff = User::factory()->create([
            'username' => 'staff.preview.link',
            'role' => 'staff_kepegawaian',
            'module_access' => ['kepegawaian'],
            'is_active' => true,
        ]);
        $sekjen = User::factory()->create([
            'username' => 'sekjen.preview.link',
            'role' => 'sekjen',
            'module_access' => ['kepegawaian'],
            'is_active' => true,
        ]);
        $pegawai = User::factory()->create([
            'username' => '198801012014041003',
            'role' => 'pegawai',
            'module_access' => ['kepegawaian'],
            'is_active' => true,
        ]);
        $category = SdmKategoriDokumen::create([
            'kode' => 'LINK',
            'nama' => 'Dokumen Link',
            'is_active' => true,
        ]);

        Storage::disk('public')->put('kepegawaian/dokumen/link.pdf', 'dummy pdf');

        $document = SdmTransaksiDokumen::create([
            'user_id' => $pegawai->id,
            'sdm_kategori_dokumen_id' => $category->id,
            'nomor_pengajuan' => 'KPG-PREVIEW-002',
            'judul' => 'Preview Link',
            'file_path' => 'kepegawaian/dokumen/link.pdf',
            'file_name' => 'link.pdf',
            'status' => 'menunggu_sekjen',
            'submitted_at' => now(),
        ]);

        $previewUrl = route('kepegawaian.dokumen.preview', $document->id);

        $this->actingAs($staff)
            ->get(route('kepegawaian.verifikasi'))
            ->assertOk()
            ->assertSee($previewUrl, false);

        $this->actingAs($sekjen)
            ->get(route('kepegawaian.persetujuan'))
            ->assertOk()
            ->assertSee($previewUrl, false);
    }

    public function test_panel_verifikasi_memperbarui_dokumen_yang_dipilih(): void
    {
        $staff = User::factory()->create([
            'username' => 'staff.kpg',
            'role' => 'staff_kepegawaian',
            'module_access' => ['kepegawaian'],
            'is_active' => true,
        ]);
        $pegawai = User::factory()->create([
            'username' => '198801012014041001',
            'role' => 'pegawai',
            'module_access' => ['kepegawaian'],
            'is_active' => true,
        ]);
        $category = SdmKategoriDokumen::create([
            'kode' => 'SK',
            'nama' => 'SK Pengangkatan',
            'is_active' => true,
        ]);

        $first = SdmTransaksiDokumen::create([
            'user_id' => $pegawai->id,
            'sdm_kategori_dokumen_id' => $category->id,
            'nomor_pengajuan' => 'KPG-TEST-002',
            'judul' => 'Dokumen Pertama',
            'status' => 'diajukan',
            'submitted_at' => now(),
        ]);
        $selected = SdmTransaksiDokumen::create([
            'user_id' => $pegawai->id,
            'sdm_kategori_dokumen_id' => $category->id,
            'nomor_pengajuan' => 'KPG-TEST-003',
            'judul' => 'Dokumen Kedua',
            'status' => 'diajukan',
            'submitted_at' => now(),
        ]);

        $this->actingAs($staff)->post(route('kepegawaian.verifikasi.panel'), [
            'document_id' => $selected->id,
            'keputusan' => 'Perlu revisi pegawai',
            'catatan' => 'File kurang jelas.',
        ])->assertRedirect(route('kepegawaian.verifikasi'));

        $this->assertEquals('diajukan', $first->fresh()->status);
        $this->assertEquals('perlu_revisi', $selected->fresh()->status);
        $this->assertEquals('File kurang jelas.', $selected->fresh()->catatan_staff);
    }

    public function test_login_source_pegawai_masuk_ke_dashboard_kepegawaian(): void
    {
        $user = User::factory()->create([
            'username' => '199704042020012003',
            'password' => Hash::make('secret123'),
            'role' => 'pegawai',
            'module_access' => ['pegawai'],
            'is_active' => true,
        ]);

        SdmPegawai::create([
            'user_id' => $user->id,
            'nip' => $user->username,
            'name' => $user->name,
            'role' => 'Pegawai',
            'status' => 'active',
        ]);

        $this->post(route('login'), [
            'username' => $user->username,
            'password' => 'secret123',
            'login_source' => 'pegawai',
        ])->assertRedirect(route('kepegawaian.dashboard'));
    }

    public function test_upload_submit_wajib_mencentang_semua_checklist(): void
    {
        $user = User::factory()->create([
            'username' => 'pegawai.checklist',
            'role' => 'pegawai',
            'module_access' => ['kepegawaian'],
            'is_active' => true,
        ]);
        SdmPegawai::create([
            'user_id' => $user->id,
            'nip' => $user->username,
            'name' => $user->name,
            'role' => 'Pegawai',
            'status' => 'active',
        ]);
        $category = SdmKategoriDokumen::create([
            'kode' => 'CHECK',
            'nama' => 'Checklist',
            'is_active' => true,
        ]);

        $this->actingAs($user)->from(route('kepegawaian.upload'))->post(route('kepegawaian.upload.store'), [
            'kategori' => $category->id,
            'judul' => 'Dokumen Checklist',
            'file' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
            'action' => 'submit',
        ])->assertRedirect(route('kepegawaian.upload'))
            ->assertSessionHasErrors('checklist');

        $this->assertDatabaseMissing('sdm_transaksi_dokumens', [
            'judul' => 'Dokumen Checklist',
        ]);
    }

    public function test_upload_submit_berhasil_saat_checklist_lengkap(): void
    {
        $user = User::factory()->create([
            'username' => 'pegawai.upload',
            'role' => 'pegawai',
            'module_access' => ['kepegawaian'],
            'is_active' => true,
        ]);
        SdmPegawai::create([
            'user_id' => $user->id,
            'nip' => $user->username,
            'name' => $user->name,
            'role' => 'Pegawai',
            'status' => 'active',
        ]);
        $category = SdmKategoriDokumen::create([
            'kode' => 'UPLOAD',
            'nama' => 'Upload',
            'is_active' => true,
        ]);

        $checklist = [
            'nama_dokumen_jelas' => '1',
            'file_dapat_dibuka' => '1',
            'data_terbaca_lengkap' => '1',
            'kategori_sesuai' => '1',
            'tidak_duplikat' => '1',
            'ukuran_file_aman' => '1',
        ];

        $this->actingAs($user)->post(route('kepegawaian.upload.store'), [
            'kategori' => $category->id,
            'judul' => 'Dokumen Valid',
            'file' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
            'action' => 'submit',
            'checklist' => $checklist,
        ])->assertRedirect(route('kepegawaian.upload'));

        $this->assertDatabaseHas('sdm_transaksi_dokumens', [
            'judul' => 'Dokumen Valid',
            'status' => 'diajukan',
        ]);
    }
}
