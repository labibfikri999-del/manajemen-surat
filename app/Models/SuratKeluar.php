<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsAudit;

class SuratKeluar extends Model
{
    use HasFactory, LogsAudit;

    protected $table = 'surat_keluar';

    protected $fillable = [
        'dokumen_id',
        'broadcast_group_id',
        'instansi_id',
        'nomor_surat',
        'tanggal_keluar',
        'tujuan',
        'perihal',
        'konten',
        'file',
        'status',
        'klasifikasi_id',
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
    ];

    public function klasifikasi()
    {
        return $this->belongsTo(Klasifikasi::class, 'klasifikasi_id');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class);
    }

    public function lampirans()
    {
        return $this->morphMany(SuratLampiran::class, 'suratable');
    }
}
