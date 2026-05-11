<?php

namespace App\Models\SDM;

use Illuminate\Database\Eloquent\Model;

class SdmPegawai extends Model
{
    protected $guarded = ['id'];
    
    // Add default values if needed, but schema default handles it.


    public function shifts()
    {
        return $this->hasMany(SdmShift::class);
    }

    public function attendances()
    {
        return $this->hasMany(SdmAttendance::class);
    }

    public function pendidikans()
    {
        return $this->hasMany(SdmPendidikan::class, 'sdm_pegawai_id');
    }

    public function keluargas()
    {
        return $this->hasMany(SdmKeluarga::class, 'sdm_pegawai_id');
    }

    public function riwayatJabatans()
    {
        return $this->hasMany(SdmRiwayatJabatan::class, 'sdm_pegawai_id')->orderBy('tgl_mulai', 'desc');
    }

    public function riwayatPangkats()
    {
        return $this->hasMany(SdmRiwayatPangkat::class, 'sdm_pegawai_id')->orderBy('tmt', 'desc');
    }

    public function documents()
    {
        return $this->hasMany(SdmDocument::class, 'sdm_pegawai_id')->orderBy('created_at', 'desc');
    }

    public function transaksiDokumens()
    {
        return $this->hasMany(\App\Models\SdmTransaksiDokumen::class, 'sdm_pegawai_id')->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
