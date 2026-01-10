<?php

namespace App\Models\SDM;

use Illuminate\Database\Eloquent\Model;

class SdmPegawai extends Model
{
    protected $guarded = ['id'];

    public function shifts()
    {
        return $this->hasMany(SdmShift::class);
    }

    public function attendances()
    {
        return $this->hasMany(SdmAttendance::class);
    }
}
