<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsAudit;

class SuratMasuk extends Model
{
    use HasFactory, LogsAudit;

    protected $table = 'surat_masuk';

    protected $fillable = ['instansi_id', 'nomor_surat', 'tanggal_diterima', 'pengirim', 'perihal', 'file', 'klasifikasi_id', 'status'];

    public function klasifikasi()
    {
        return $this->belongsTo(Klasifikasi::class, 'klasifikasi_id');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }



    public function lampirans()
    {
        return $this->morphMany(SuratLampiran::class, 'suratable');
    }
}
