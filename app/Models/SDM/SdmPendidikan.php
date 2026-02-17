<?php

namespace App\Models\SDM;

use Illuminate\Database\Eloquent\Model;

class SdmPendidikan extends Model
{
    protected $table = 'sdm_pendidikans';
    protected $guarded = ['id'];

    public function pegawai()
    {
        return $this->belongsTo(SdmPegawai::class, 'sdm_pegawai_id');
    }
}
