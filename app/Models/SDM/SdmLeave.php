<?php

namespace App\Models\SDM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SdmLeave extends Model
{
    use HasFactory;

    protected $fillable = [
        'sdm_pegawai_id',
        'type',
        'start_date',
        'end_date',
        'reason',
        'status',
        'rejection_reason',
    ];

    public function pegawai()
    {
        return $this->belongsTo(SdmPegawai::class, 'sdm_pegawai_id');
    }
}
