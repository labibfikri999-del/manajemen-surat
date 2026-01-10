<?php

namespace App\Models\SDM;

use Illuminate\Database\Eloquent\Model;

class SdmShift extends Model
{
    protected $guarded = ['id'];

    public function pegawai()
    {
        return $this->belongsTo(SdmPegawai::class, 'sdm_pegawai_id');
    }
}
