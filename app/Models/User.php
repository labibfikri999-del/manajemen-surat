<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Role constants
    const ROLE_DIREKTUR = 'direktur';
    const ROLE_STAFF = 'staff';
    const ROLE_INSTANSI = 'instansi';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'instansi_id',
        'jabatan',
        'telepon',
        'avatar',
        'is_active',
        'telegram_chat_id',
        'plain_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relasi ke instansi
    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    // Dokumen yang diupload user
    public function dokumens()
    {
        return $this->hasMany(Dokumen::class);
    }

    // Dokumen yang divalidasi (untuk direktur)
    public function validatedDokumens()
    {
        return $this->hasMany(Dokumen::class, 'validated_by');
    }

    // Dokumen yang diproses (untuk staff)
    public function processedDokumens()
    {
        return $this->hasMany(Dokumen::class, 'processed_by');
    }

    // Check role
    public function isDirektur()
    {
        return $this->role === self::ROLE_DIREKTUR;
    }

    public function isStaff()
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function isInstansi()
    {
        return $this->role === self::ROLE_INSTANSI;
    }

    // Get role label
    public function getRoleLabelAttribute()
    {
        return match($this->role) {
            'direktur' => 'Direktur Yayasan',
            'staff' => 'Staff Direktur',
            'instansi' => 'User Instansi',
            default => 'Unknown',
        };
    }
}
