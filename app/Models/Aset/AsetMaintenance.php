<?php

namespace App\Models\Aset;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsetMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'aset_id',
        'description',
        'cost',
        'status',
        'scheduled_date',
        'completion_date',
        'vendor',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completion_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }
}
