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
        'username',
        'password',
        'role',
        'instansi_id',
        'jabatan',
        'telepon',
        'avatar',
        'is_active',
        'telegram_chat_id',
        'plain_password',
        'module_access',
        'must_change_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'plain_password',
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
            'module_access' => 'array',
            'must_change_password' => 'boolean',
        ];
    }

    // Relasi ke instansi
    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public static function normalizeRoleValue(?string $role): string
    {
        return str_replace([' ', '-'], '_', strtolower(trim((string) $role)));
    }

    public function normalizedRole(): string
    {
        return self::normalizeRoleValue($this->role);
    }

    public function hasRole(string $role): bool
    {
        return match (self::normalizeRoleValue($role)) {
            self::ROLE_DIREKTUR => $this->isDirektur(),
            self::ROLE_STAFF => $this->isStaff(),
            self::ROLE_INSTANSI => $this->isInstansi(),
            default => $this->normalizedRole() === self::normalizeRoleValue($role),
        };
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
    // Alias modifiers for readability after rename
    public function isSekjen()
    {
        return $this->role === self::ROLE_DIREKTUR;
    }

    public function isUnitUsaha()
    {
        return $this->role === self::ROLE_INSTANSI;
    }

    public function isDirektur()
    {
        return $this->normalizedRole() === self::ROLE_DIREKTUR;
    }

    public function isStaff()
    {
        return in_array($this->normalizedRole(), [
            self::ROLE_STAFF,
            'staff_sekjen',
            'staff_sekretaris',
            'staff_direktur',
            'sekretaris',
        ], true);
    }

    public function isInstansi()
    {
        return in_array($this->normalizedRole(), [
            self::ROLE_INSTANSI,
            'unit_usaha',
        ], true);
    }

    // Get role label
    public function getRoleLabelAttribute()
    {
        return match ($this->normalizedRole()) {
            'direktur' => 'Sekjen Yayasan',
            'staff' => 'Staff Sekjen',
            'staff_sekjen' => 'Staff Sekjen',
            'staff_sekretaris' => 'Staff Sekretaris',
            'staff_direktur' => 'Staff Direktur',
            'sekretaris' => 'Staff Sekretaris',
            'instansi' => 'Unit Usaha',
            'unit_usaha' => 'Unit Usaha',
            'pegawai' => 'Pegawai',
            'staff_kepegawaian' => 'Staff Kepegawaian',
            'sekjen' => 'Sekjen',
            default => 'Unknown',
        };
    }
}
