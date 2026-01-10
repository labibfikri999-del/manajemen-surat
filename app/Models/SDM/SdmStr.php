<?php

namespace App\Models\SDM;

use Illuminate\Database\Eloquent\Model;

class SdmStr extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function pegawai()
    {
        return $this->belongsTo(SdmPegawai::class, 'sdm_pegawai_id');
    }
}
