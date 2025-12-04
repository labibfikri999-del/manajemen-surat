<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Klasifikasi extends Model {
    use HasFactory;
    protected $table = 'klasifikasi';
    protected $fillable = ['nama'];

    public function suratMasuk() {
        return $this->hasMany(SuratMasuk::class, 'klasifikasi_id');
    }
}
