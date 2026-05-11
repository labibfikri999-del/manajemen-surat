<?php

namespace Tests\Feature;

use App\Models\Dokumen;
use App\Models\Instansi;
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
}
