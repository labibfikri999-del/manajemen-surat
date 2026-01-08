<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_dokumen',
        'judul',
        'jenis_dokumen',
        'prioritas',
        'deskripsi',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'file_pengganti_path',
        'file_pengganti_name',
        'file_pengganti_type',
        'file_pengganti_size',
        'balasan_file',
        'instansi_id',
        'user_id',
        'validated_by',
        'processed_by',
        'status',
        'kategori_arsip',
        'is_archived',
        'tanggal_arsip',
        'catatan_validasi',
        'catatan_proses',
        'tanggal_validasi',
        'tanggal_proses',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_validasi' => 'datetime',
        'tanggal_proses' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_arsip' => 'datetime',
        'is_archived' => 'boolean',
    ];

    // Kategori Arsip constants
    const KATEGORI_UMUM = 'UMUM';

    const KATEGORI_SDM = 'SDM';

    const KATEGORI_ASSET = 'ASSET';

    const KATEGORI_HUKUM = 'HUKUM';

    const KATEGORI_SURAT_KELUAR = 'SURAT_KELUAR';

    const KATEGORI_SK = 'SK';

    public static function getKategoriArsip()
    {
        return [
            self::KATEGORI_UMUM => 'Umum',
            self::KATEGORI_SDM => 'SDM',
            self::KATEGORI_ASSET => 'Asset',
            self::KATEGORI_HUKUM => 'Hukum',
            self::KATEGORI_KEUANGAN => 'Keuangan',
            self::KATEGORI_SURAT_KELUAR => 'Surat Keluar',
            self::KATEGORI_SK => 'Surat Keputusan (SK)',
        ];
    }

    // Status constants
    const STATUS_PENDING = 'pending';

    const STATUS_REVIEW = 'review';

    const STATUS_DISETUJUI = 'disetujui';

    const STATUS_DITOLAK = 'ditolak';

    const STATUS_DIPROSES = 'diproses';

    const STATUS_SELESAI = 'selesai';

    // Relasi ke instansi
    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    // Relasi ke user yang upload
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke direktur yang validasi
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    // Relasi ke staff yang proses
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Status label dengan warna
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => ['text' => 'Menunggu', 'color' => 'yellow'],
            'review' => ['text' => 'Sedang Direview', 'color' => 'blue'],
            'disetujui' => ['text' => 'Disetujui', 'color' => 'green'],
            'ditolak' => ['text' => 'Ditolak', 'color' => 'red'],
            'diproses' => ['text' => 'Sedang Diproses', 'color' => 'purple'],
            'selesai' => ['text' => 'Selesai', 'color' => 'emerald'],
            default => ['text' => 'Unknown', 'color' => 'gray'],
        };
    }

    // Generate nomor dokumen otomatis
    public static function generateNomorDokumen($instansiKode)
    {
        $year = date('Y');
        $month = date('m');
        $prefix = sprintf('DOC/%s/%s%s/', $instansiKode, $year, $month);

        // Cari dokumen dengan nomor tertinggi yang sesuai prefix
        // Menggunakan orderBy nomor_dokumen untuk mendapatkan urutan terakhir yang benar
        // terlepas dari ID atau created_at
        $lastDoc = self::where('nomor_dokumen', 'like', $prefix.'%')
            ->orderByRaw('LENGTH(nomor_dokumen) DESC') // Antisipasi jika digit bertambah
            ->orderBy('nomor_dokumen', 'desc')
            ->first();

        if ($lastDoc) {
            $parts = explode('/', $lastDoc->nomor_dokumen);
            $lastNumber = intval(end($parts));
            $count = $lastNumber + 1;
        } else {
            $count = 1;
        }

        return sprintf('%s%04d', $prefix, $count);
    }
}
