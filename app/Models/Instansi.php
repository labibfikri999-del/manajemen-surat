<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kode',
        'alamat',
        'telepon',
        'email',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relasi ke dokumen
    public function dokumens()
    {
        return $this->hasMany(Dokumen::class);
    }
}
