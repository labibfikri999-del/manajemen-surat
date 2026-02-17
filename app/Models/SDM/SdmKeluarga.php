<?php

namespace App\Models\SDM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SdmKeluarga extends Model
{
    use HasFactory;

    protected $table = 'sdm_keluargas';

    protected $guarded = ['id'];

    public function pegawai()
    {
        return $this->belongsTo(SdmPegawai::class, 'sdm_pegawai_id');
    }
}
