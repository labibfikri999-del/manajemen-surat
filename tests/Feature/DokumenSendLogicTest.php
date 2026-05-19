<?php

namespace Tests\Feature;

use App\Models\Dokumen;
use App\Models\Instansi;
use App\Models\SuratKeluar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DokumenSendLogicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function staff_sending_to_unit_marks_as_selesai()
    {
        // 1. Setup User (Staff)
        $staff = User::where('role', 'staff')->first();
        if (!$staff) {
            $staff = User::factory()->create([
                'role' => 'staff',
                'username' => 'teststaff' . uniqid(),
            ]);
        }

        // 2. Setup Instansi
        $instansi = Instansi::first();
        if (!$instansi) {
            $instansi = Instansi::create([
                'kode' => 'TEST',
                'nama' => 'Test Instansi',
                'email' => 'test@instansi.com'
            ]);
        }

        // 3. Act
        $response = $this->actingAs($staff)->postJson('/api/dokumen', [
            'judul' => 'Test Dokumen Internal',
            'jenis' => 'surat_keluar',
            'file' => UploadedFile::fake()->create('doc.pdf', 100),
            'tujuan_instansi_id' => $instansi->id,
            // 'kategori_arsip' => null // Not set
        ]);

        // 4. Assert
        $response->assertStatus(201);
        $this->assertEquals('selesai', $response->json('dokumen.status'));
        $this->assertEquals('SURAT_KELUAR', $response->json('dokumen.kategori_arsip'));
    }

    /** @test */
    public function staff_sending_to_unit_is_recorded_as_surat_keluar_even_if_form_jenis_differs()
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'username' => 'teststaff' . uniqid(),
            'module_access' => ['surat'],
        ]);

        $instansi = Instansi::create([
            'kode' => 'UNIT',
            'nama' => 'Unit Tujuan',
            'email' => 'unit@example.test',
            'is_active' => true,
        ]);

        $response = $this->actingAs($staff)->postJson('/api/dokumen', [
            'judul' => 'Surat Pusat ke Unit',
            'jenis' => 'proposal',
            'file' => UploadedFile::fake()->create('surat.pdf', 100),
            'tujuan_instansi_id' => $instansi->id,
        ]);

        $response->assertStatus(201);
        $this->assertEquals('surat_keluar', $response->json('dokumen.jenis_dokumen'));
        $this->assertEquals('selesai', $response->json('dokumen.status'));
        $this->assertEquals('SURAT_KELUAR', $response->json('dokumen.kategori_arsip'));
    }

    /** @test */
    public function staff_broadcast_is_recorded_as_surat_keluar_even_if_form_jenis_differs()
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'username' => 'broadcaststaff' . uniqid(),
            'module_access' => ['surat'],
        ]);

        Instansi::create([
            'kode' => 'UA',
            'nama' => 'Unit A',
            'email' => 'unit-a@example.test',
            'is_active' => true,
        ]);
        Instansi::create([
            'kode' => 'UB',
            'nama' => 'Unit B',
            'email' => 'unit-b@example.test',
            'is_active' => true,
        ]);

        $response = $this->actingAs($staff)->postJson('/api/dokumen', [
            'judul' => 'Broadcast Pusat',
            'jenis' => 'laporan',
            'file' => UploadedFile::fake()->create('broadcast.pdf', 100),
            'send_to_all' => '1',
        ]);

        $response->assertStatus(201);
        $this->assertEquals('surat_keluar', $response->json('dokumen.jenis_dokumen'));
        $this->assertDatabaseCount('dokumens', 2);
        $this->assertDatabaseMissing('dokumens', [
            'judul' => 'Broadcast Pusat',
            'jenis_dokumen' => 'laporan',
        ]);
    }

    /** @test */
    public function staff_with_empty_legacy_module_access_can_broadcast_to_all_units()
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'username' => 'legacybroadcaststaff' . uniqid(),
            'module_access' => [],
        ]);

        Instansi::create([
            'kode' => 'LA',
            'nama' => 'Legacy Unit A',
            'email' => 'legacy-a@example.test',
            'is_active' => true,
        ]);
        Instansi::create([
            'kode' => 'LB',
            'nama' => 'Legacy Unit B',
            'email' => 'legacy-b@example.test',
            'is_active' => true,
        ]);

        $response = $this->actingAs($staff)->postJson('/api/dokumen', [
            'judul' => 'Broadcast Data Lama',
            'jenis' => 'surat_keluar',
            'file' => UploadedFile::fake()->create('broadcast-legacy.pdf', 100),
            'send_to_all' => '1',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseCount('dokumens', 2);
    }

    /** @test */
    public function staff_sending_to_email_marks_as_selesai()
    {
        $staff = User::where('role', 'staff')->first();
        if (!$staff) {
            $staff = User::factory()->create([
                'role' => 'staff',
                'username' => 'teststaff' . uniqid(),
            ]);
        }

        // 3. Act
        $response = $this->actingAs($staff)->postJson('/api/dokumen', [
            'judul' => 'Test Dokumen Eksternal',
            'jenis' => 'surat_keluar',
            'file' => UploadedFile::fake()->create('doc.pdf', 100),
            'email_eksternal' => 'external@example.com',
        ]);

        // 4. Assert
        $response->assertStatus(201);
        $this->assertEquals('selesai', $response->json('dokumen.status'));
        $this->assertEquals('SURAT_KELUAR', $response->json('dokumen.kategori_arsip'));
    }

    /** @test */
    public function staff_upload_without_destination_marks_as_disetujui()
    {
        $staff = User::where('role', 'staff')->first();
        if (!$staff) {
            $staff = User::factory()->create([
                'role' => 'staff',
                'username' => 'teststaff' . uniqid(),
            ]);
        }

        // 3. Act
        $response = $this->actingAs($staff)->postJson('/api/dokumen', [
            'judul' => 'Test Dokumen Upload Only',
            'jenis' => 'surat_masuk',
            'file' => UploadedFile::fake()->create('doc.pdf', 100),
            // No destination
        ]);

        // 4. Assert
        $response->assertStatus(201);
        $this->assertEquals('disetujui', $response->json('dokumen.status'));
        // Should NOT call Director validation
    }

    /** @test */
    public function staff_sent_document_goes_to_unit_inbox_not_staff_process_queue()
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'username' => 'staff-kirim-' . uniqid(),
            'module_access' => ['surat'],
        ]);

        $instansi = Instansi::create([
            'kode' => 'UNIT',
            'nama' => 'Unit Tujuan',
            'email' => 'unit@example.test',
            'is_active' => true,
        ]);

        $unitUser = User::factory()->create([
            'role' => 'instansi',
            'username' => 'unit-tujuan-' . uniqid(),
            'instansi_id' => $instansi->id,
            'module_access' => ['surat'],
        ]);

        $outgoing = Dokumen::create([
            'nomor_dokumen' => 'DOC/UNIT/202605/0001',
            'judul' => 'Surat Balasan Staff ke Unit',
            'jenis_dokumen' => 'surat_keluar',
            'file_path' => 'dokumen/UNIT/balasan.pdf',
            'file_name' => 'balasan.pdf',
            'file_type' => 'pdf',
            'file_size' => 100,
            'user_id' => $staff->id,
            'instansi_id' => $instansi->id,
            'status' => 'selesai',
            'kategori_arsip' => 'SURAT_KELUAR',
            'is_archived' => true,
            'tanggal_arsip' => now(),
            'tanggal_selesai' => now(),
            'updated_at' => now(),
        ]);

        $incoming = Dokumen::create([
            'nomor_dokumen' => 'DOC/UNIT/202605/0002',
            'judul' => 'Dokumen Unit Perlu Diproses',
            'jenis_dokumen' => 'surat_masuk',
            'file_path' => 'dokumen/UNIT/masuk.pdf',
            'file_name' => 'masuk.pdf',
            'file_type' => 'pdf',
            'file_size' => 100,
            'user_id' => $unitUser->id,
            'instansi_id' => $instansi->id,
            'status' => 'disetujui',
            'tanggal_validasi' => now(),
        ]);

        $this->actingAs($staff)
            ->get('/proses-dokumen')
            ->assertOk()
            ->assertDontSee($outgoing->judul)
            ->assertSee($incoming->judul);

        $this->actingAs($unitUser)
            ->get('/surat-masuk')
            ->assertOk()
            ->assertSee($outgoing->judul);
    }

    /** @test */
    public function staff_without_surat_module_gets_json_for_dokumen_upload_forbidden()
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'username' => 'staff-no-surat-' . uniqid(),
            'module_access' => ['kepegawaian'],
        ]);

        $response = $this->actingAs($staff)->postJson('/api/dokumen', [
            'judul' => 'Upload Tanpa Akses Surat',
            'jenis' => 'surat_keluar',
            'file' => UploadedFile::fake()->create('surat.pdf', 100),
        ]);

        $response->assertForbidden()
            ->assertJsonPath('message', 'Akses Ditolak. Akun Anda tidak terdaftar untuk modul ini.');
    }

    /** @test */
    public function instansi_upload_preserves_physical_nomor_surat_in_auto_surat_keluar()
    {
        $instansi = Instansi::create([
            'kode' => 'UNIT',
            'nama' => 'Unit Pengirim',
            'is_active' => true,
        ]);
        $unitUser = User::factory()->create([
            'role' => 'instansi',
            'username' => 'unit-upload-' . uniqid(),
            'instansi_id' => $instansi->id,
            'module_access' => ['surat'],
        ]);

        $response = $this->actingAs($unitUser)->postJson('/api/dokumen', [
            'nomor_surat' => '001/UNIT/V/2026',
            'judul' => 'Surat Unit ke Pusat',
            'jenis' => 'surat_keluar',
            'file' => UploadedFile::fake()->create('surat-unit.pdf', 100),
        ]);

        $response->assertStatus(201);
        $this->assertEquals('001/UNIT/V/2026', $response->json('dokumen.nomor_surat'));
        $this->assertDatabaseHas('surat_keluar', [
            'instansi_id' => $instansi->id,
            'nomor_surat' => '001/UNIT/V/2026',
            'perihal' => 'Surat Unit ke Pusat',
            'status' => 'Terkirim',
        ]);
        $this->assertSame(1, SuratKeluar::count());
    }
}
