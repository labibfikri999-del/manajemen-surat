<?php

namespace App\Models;

use App\Models\SDM\SdmPegawai;
use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'verified_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(SdmPegawai::class, 'sdm_pegawai_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'terverifikasi' => 'Terverifikasi',
            'password_sementara_dibuat' => 'Password Sementara Dibuat',
            'ditolak' => 'Ditolak',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }
}
