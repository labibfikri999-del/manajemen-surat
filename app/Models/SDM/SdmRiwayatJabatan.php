<?php

namespace App\Models\SDM;

use Illuminate\Database\Eloquent\Model;

class SdmRiwayatJabatan extends Model
{
    protected $table = 'sdm_riwayat_jabatans';
    protected $guarded = ['id'];

    public function pegawai()
    {
        return $this->belongsTo(SdmPegawai::class, 'sdm_pegawai_id');
    }

    public function masterJabatan()
    {
        return $this->belongsTo(SdmMasterJabatan::class, 'sdm_master_jabatan_id');
    }
}
