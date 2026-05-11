<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sdm_kategori_dokumens')) {
            Schema::table('sdm_kategori_dokumens', function (Blueprint $table) {
                if (!Schema::hasColumn('sdm_kategori_dokumens', 'kode')) {
                    $table->string('kode', 50)->nullable()->unique()->after('id');
                }
                if (!Schema::hasColumn('sdm_kategori_dokumens', 'nama')) {
                    $table->string('nama')->nullable()->after('kode');
                }
                if (!Schema::hasColumn('sdm_kategori_dokumens', 'deskripsi')) {
                    $table->text('deskripsi')->nullable()->after('nama');
                }
                if (!Schema::hasColumn('sdm_kategori_dokumens', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('deskripsi');
                }
                if (!Schema::hasColumn('sdm_kategori_dokumens', 'sort_order')) {
                    $table->unsignedSmallInteger('sort_order')->default(0)->after('is_active');
                }
            });
        }

        if (Schema::hasTable('sdm_transaksi_dokumens')) {
            Schema::table('sdm_transaksi_dokumens', function (Blueprint $table) {
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->cascadeOnDelete();
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'sdm_pegawai_id')) {
                    $table->foreignId('sdm_pegawai_id')->nullable()->after('user_id')->constrained('sdm_pegawais')->nullOnDelete();
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'sdm_kategori_dokumen_id')) {
                    $table->foreignId('sdm_kategori_dokumen_id')->nullable()->after('sdm_pegawai_id')->constrained('sdm_kategori_dokumens')->restrictOnDelete();
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'nomor_pengajuan')) {
                    $table->string('nomor_pengajuan')->nullable()->unique()->after('sdm_kategori_dokumen_id');
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'judul')) {
                    $table->string('judul')->nullable()->after('nomor_pengajuan');
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'catatan_pegawai')) {
                    $table->text('catatan_pegawai')->nullable()->after('judul');
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'file_path')) {
                    $table->string('file_path')->nullable()->after('catatan_pegawai');
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'file_name')) {
                    $table->string('file_name')->nullable()->after('file_path');
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'file_type')) {
                    $table->string('file_type', 50)->nullable()->after('file_name');
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'file_size')) {
                    $table->unsignedBigInteger('file_size')->nullable()->after('file_type');
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'status')) {
                    $table->string('status')->default('draft')->after('file_size');
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'catatan_staff')) {
                    $table->text('catatan_staff')->nullable()->after('status');
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'catatan_sekjen')) {
                    $table->text('catatan_sekjen')->nullable()->after('catatan_staff');
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'verified_by')) {
                    $table->foreignId('verified_by')->nullable()->after('catatan_sekjen')->constrained('users')->nullOnDelete();
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'verified_at')) {
                    $table->timestamp('verified_at')->nullable()->after('verified_by');
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'approved_by')) {
                    $table->foreignId('approved_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('approved_by');
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'submitted_at')) {
                    $table->timestamp('submitted_at')->nullable()->after('approved_at');
                }
                if (!Schema::hasColumn('sdm_transaksi_dokumens', 'archived_at')) {
                    $table->timestamp('archived_at')->nullable()->after('submitted_at');
                }
            });
        }

        if (Schema::hasTable('password_reset_requests')) {
            Schema::table('password_reset_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('password_reset_requests', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
                }
                if (!Schema::hasColumn('password_reset_requests', 'sdm_pegawai_id')) {
                    $table->foreignId('sdm_pegawai_id')->nullable()->after('user_id')->constrained('sdm_pegawais')->nullOnDelete();
                }
                if (!Schema::hasColumn('password_reset_requests', 'nip')) {
                    $table->string('nip', 50)->nullable()->after('sdm_pegawai_id');
                }
                if (!Schema::hasColumn('password_reset_requests', 'kontak')) {
                    $table->string('kontak', 100)->nullable()->after('nip');
                }
                if (!Schema::hasColumn('password_reset_requests', 'alasan')) {
                    $table->text('alasan')->nullable()->after('kontak');
                }
                if (!Schema::hasColumn('password_reset_requests', 'status')) {
                    $table->string('status')->default('menunggu_verifikasi')->after('alasan');
                }
                if (!Schema::hasColumn('password_reset_requests', 'verified_by')) {
                    $table->foreignId('verified_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
                }
                if (!Schema::hasColumn('password_reset_requests', 'verified_at')) {
                    $table->timestamp('verified_at')->nullable()->after('verified_by');
                }
                if (!Schema::hasColumn('password_reset_requests', 'completed_at')) {
                    $table->timestamp('completed_at')->nullable()->after('verified_at');
                }
            });
        }
    }

    public function down(): void
    {
        // Intentionally left non-destructive for existing local tables.
    }
};
