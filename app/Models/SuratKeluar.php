<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuratKeluar extends Model
{
    use HasFactory;
    
    protected $table = 'surat_keluar';
    
    protected $fillable = [
        'instansi_id',
        'nomor_surat',
        'tanggal_keluar',
        'tujuan',
        'perihal',
        'file',
        'status',
        'klasifikasi_id'
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
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }
}
