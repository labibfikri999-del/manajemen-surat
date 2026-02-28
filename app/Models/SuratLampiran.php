<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratLampiran extends Model
{
    use HasFactory;

    protected $fillable = [
        'suratable_type',
        'suratable_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
    ];

    public function suratable()
    {
        return $this->morphTo();
    }
}
