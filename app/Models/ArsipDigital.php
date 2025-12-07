<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArsipDigital extends Model
{
    use HasFactory;
    
    protected $table = 'arsip_digital';
    
    protected $fillable = [
        'instansi_id',
        'nama_dokumen',
        'kategori',
        'deskripsi',
        'nama_file',
        'file_path',
        'tipe',
        'ukuran',
        'tanggal_upload'
    ];

    protected $casts = [
        'tanggal_upload' => 'date',
    ];
}
