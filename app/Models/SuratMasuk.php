<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuratMasuk extends Model {
    use HasFactory;
    protected $table = 'surat_masuk';
    protected $fillable = ['nomor_surat','tanggal_diterima','pengirim','perihal','file','klasifikasi_id'];

    public function klasifikasi() {
        return $this->belongsTo(Klasifikasi::class, 'klasifikasi_id');
    }
}
