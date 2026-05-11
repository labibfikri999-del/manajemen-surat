<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SdmKategoriDokumen extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function dokumens()
    {
        return $this->hasMany(SdmTransaksiDokumen::class);
    }

    public function getNamaAttribute($value): ?string
    {
        return $value ?: ($this->attributes['nama_kategori'] ?? null);
    }
}
