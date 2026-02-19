<?php

namespace App\Models\SDM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SdmDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'sdm_pegawai_id',
        'nama_dokumen',
        'kategori',
        'file_path',
        'tgl_kadaluarsa',
        'keterangan',
    ];

    public function pegawai()
    {
        return $this->belongsTo(SdmPegawai::class, 'sdm_pegawai_id');
    }
}
