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
}
