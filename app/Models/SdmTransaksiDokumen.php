<?php

namespace App\Models;

use App\Models\SDM\SdmPegawai;
use Illuminate\Database\Eloquent\Model;

class SdmTransaksiDokumen extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(SdmPegawai::class, 'sdm_pegawai_id');
    }

    public function kategori()
    {
        return $this->belongsTo(SdmKategoriDokumen::class, 'sdm_kategori_dokumen_id');
    }

    public function kategoriLegacy()
    {
        return $this->belongsTo(SdmKategoriDokumen::class, 'kategori_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'diajukan' => 'Diajukan',
            'diperiksa_staff' => 'Diperiksa Staff',
            'perlu_revisi' => 'Perlu Revisi',
            'menunggu_sekjen' => 'Menunggu Sekjen',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'diarsipkan' => 'Diarsipkan',
            'Pending' => 'Diajukan',
            'Reviewed' => 'Menunggu Sekjen',
            'Approved' => 'Disetujui',
            'Rejected' => 'Ditolak',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }
}
