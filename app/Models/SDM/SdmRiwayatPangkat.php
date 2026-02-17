<?php

namespace App\Models\SDM;

use Illuminate\Database\Eloquent\Model;

class SdmRiwayatPangkat extends Model
{
    protected $table = 'sdm_riwayat_pangkats';
    protected $guarded = ['id'];

    public function pegawai()
    {
        return $this->belongsTo(SdmPegawai::class, 'sdm_pegawai_id');
    }
}
